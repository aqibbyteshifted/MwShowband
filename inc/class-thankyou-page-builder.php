<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * One-click builder for the "Thank You" page that the contact form sends visitors to after a
 * successful enquiry. Appearance → Thank You Page.
 *
 * The page is created on Elementor's "Canvas" template (blank, edge-to-edge, no theme header or
 * footer), holds one MW Thank You widget, and is marked noindex so it never shows in search
 * results (a thank-you URL should only be reachable after a real submission — also keeps
 * conversion tracking clean).
 *
 * Safety: like the proposal builder, this only ever creates/deletes the ONE page tracked by
 * self::OPTION_KEY. It never touches any other page, template or site-wide option.
 *
 * Author: Aqib Dev
 */
final class MWSB_Thankyou_Page_Builder {

	const OPTION_KEY = 'mwsb_thankyou_page_id';
	const SLUG       = 'thank-you';

	public static function instance() {
		static $instance = null;
		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}

	private function __construct() {
		add_action( 'admin_menu', array( $this, 'register_admin_page' ) );
		add_action( 'admin_post_mwsb_build_thankyou', array( $this, 'handle_build' ) );
		add_action( 'admin_post_mwsb_remove_thankyou', array( $this, 'handle_remove' ) );
		add_filter( 'wp_robots', array( $this, 'noindex_thankyou' ) );
	}

	/** ID of the built Thank You page, or 0 when it hasn't been built / was deleted. */
	public static function get_page_id() {
		$page_id = (int) get_option( self::OPTION_KEY );
		if ( $page_id && 'publish' === get_post_status( $page_id ) ) {
			return $page_id;
		}
		return 0;
	}

	/** Permalink of the built Thank You page ('' when there isn't one). Used by the contact form. */
	public static function get_url() {
		$page_id = self::get_page_id();
		return $page_id ? get_permalink( $page_id ) : '';
	}

	public function noindex_thankyou( $robots ) {
		$page_id = self::get_page_id();
		if ( $page_id && is_page( $page_id ) ) {
			$robots['noindex']  = true;
			$robots['nofollow'] = true;
			unset( $robots['index'], $robots['follow'] );
		}
		return $robots;
	}

	public function register_admin_page() {
		add_theme_page(
			__( 'Thank You Page', 'mw-showband' ),
			__( 'Thank You Page', 'mw-showband' ),
			'manage_options',
			'mwsb-thankyou-page',
			array( $this, 'render_admin_page' )
		);
	}

	private function eid() {
		return substr( str_replace( '.', '', uniqid( '', true ) ), -7 );
	}

	/** Full-width, zero-padding Container holding the single Thank You widget. */
	private function build_page_data() {
		$zero = array(
			'unit'     => 'px',
			'top'      => '0',
			'right'    => '0',
			'bottom'   => '0',
			'left'     => '0',
			'isLinked' => true,
		);

		return array(
			array(
				'id'       => $this->eid(),
				'elType'   => 'container',
				'isInner'  => false,
				'settings' => (object) array(
					'content_width'  => 'full',
					'flex_direction' => 'column',
					'padding'        => $zero,
					'margin'         => $zero,
				),
				'elements' => array(
					array(
						'id'         => $this->eid(),
						'elType'     => 'widget',
						'isInner'    => false,
						'settings'   => (object) array(),
						'elements'   => array(),
						'widgetType' => 'mwsb-thank-you',
					),
				),
			),
		);
	}

	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$page_id          = self::get_page_id();
		$elementor_active = did_action( 'elementor/loaded' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Thank You Page', 'mw-showband' ); ?></h1>
			<p><?php esc_html_e( 'Creates a full-width Elementor Canvas page with the MW Thank You widget (confirmation animation, "what happens next" steps, buttons and contact links) in the same design as the rest of the site. Your contact forms send visitors here after a successful enquiry (set per form under Form Settings → After Submit).', 'mw-showband' ); ?></p>

			<?php if ( ! $elementor_active ) : ?>
				<div class="notice notice-error"><p><?php esc_html_e( 'Elementor is not active. Please install and activate Elementor before building this page.', 'mw-showband' ); ?></p></div>
			<?php endif; ?>

			<?php if ( isset( $_GET['mwsb_status'] ) && 'built' === $_GET['mwsb_status'] ) : ?>
				<div class="notice notice-success"><p><?php esc_html_e( 'Thank You page built. Contact forms now redirect to it after a successful submit.', 'mw-showband' ); ?></p></div>
			<?php elseif ( isset( $_GET['mwsb_status'] ) && 'removed' === $_GET['mwsb_status'] ) : ?>
				<div class="notice notice-success"><p><?php esc_html_e( 'Thank You page removed. Contact forms will show their inline success message again.', 'mw-showband' ); ?></p></div>
			<?php endif; ?>

			<?php if ( $page_id ) : ?>
				<p>
					<strong><?php esc_html_e( 'Status:', 'mw-showband' ); ?></strong>
					<?php esc_html_e( 'Thank You page is live.', 'mw-showband' ); ?>
					&nbsp;
					<a class="button" href="<?php echo esc_url( get_permalink( $page_id ) ); ?>" target="_blank"><?php esc_html_e( 'View Page', 'mw-showband' ); ?></a>
					<a class="button" href="<?php echo esc_url( admin_url( 'post.php?post=' . $page_id . '&action=elementor' ) ); ?>"><?php esc_html_e( 'Edit with Elementor', 'mw-showband' ); ?></a>
				</p>
				<p class="description"><?php echo esc_html( get_permalink( $page_id ) ); ?></p>

				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'This deletes the Thank You page. Continue?', 'mw-showband' ) ); ?>');">
					<?php wp_nonce_field( 'mwsb_remove_thankyou' ); ?>
					<input type="hidden" name="action" value="mwsb_remove_thankyou" />
					<?php submit_button( __( 'Remove Thank You Page', 'mw-showband' ), 'delete' ); ?>
				</form>
			<?php else : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'mwsb_build_thankyou' ); ?>
					<input type="hidden" name="action" value="mwsb_build_thankyou" />
					<?php submit_button( __( 'Build Thank You Page', 'mw-showband' ), 'primary', 'submit', false, $elementor_active ? array() : array( 'disabled' => 'disabled' ) ); ?>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}

	public function handle_build() {
		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'mwsb_build_thankyou' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'mw-showband' ) );
		}
		if ( ! did_action( 'elementor/loaded' ) ) {
			wp_die( esc_html__( 'Elementor must be active to build this page.', 'mw-showband' ) );
		}

		$existing_id = (int) get_option( self::OPTION_KEY );
		if ( $existing_id && get_post( $existing_id ) ) {
			wp_delete_post( $existing_id, true );
		}

		$page_id = wp_insert_post(
			array(
				'post_title'   => __( 'Thank You', 'mw-showband' ),
				'post_name'    => self::SLUG,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			)
		);

		if ( is_wp_error( $page_id ) || ! $page_id ) {
			wp_die( esc_html__( 'Could not create the Thank You page.', 'mw-showband' ) );
		}

		update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $this->build_page_data() ) ) );
		update_post_meta( $page_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $page_id, '_elementor_template_type', 'wp-page' );
		update_post_meta( $page_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
		// Elementor Canvas = blank page: no theme header/footer, full width.
		update_post_meta( $page_id, '_wp_page_template', 'elementor_canvas' );

		update_option( self::OPTION_KEY, $page_id );

		if ( class_exists( '\Elementor\Plugin' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		wp_safe_redirect( admin_url( 'themes.php?page=mwsb-thankyou-page&mwsb_status=built' ) );
		exit;
	}

	public function handle_remove() {
		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'mwsb_remove_thankyou' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'mw-showband' ) );
		}

		$page_id = (int) get_option( self::OPTION_KEY );
		if ( $page_id && get_post( $page_id ) ) {
			wp_delete_post( $page_id, true );
		}
		delete_option( self::OPTION_KEY );

		wp_safe_redirect( admin_url( 'themes.php?page=mwsb-thankyou-page&mwsb_status=removed' ) );
		exit;
	}
}

MWSB_Thankyou_Page_Builder::instance();
