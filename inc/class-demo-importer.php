<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * One-Click Demo Import — recreates the full MW Showband homepage (all 16
 * sections, in the approved design order) as a real Elementor page and sets
 * it as the site's static front page. No third-party importer plugin
 * required; everything runs natively inside the theme.
 *
 * Author: Aqib Dev
 */
final class MWSB_Demo_Importer {

	const OPTION_KEY = 'mwsb_demo_imported_page_id';

	public static function instance() {
		static $instance = null;
		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}

	private function __construct() {
		add_action( 'admin_menu', array( $this, 'register_admin_page' ) );
		add_action( 'admin_post_mwsb_import_demo', array( $this, 'handle_import' ) );
		add_action( 'admin_post_mwsb_reset_demo', array( $this, 'handle_reset' ) );
	}

	public function register_admin_page() {
		add_theme_page(
			__( 'Import Demo Data', 'mw-showband' ),
			__( 'Import Demo Data', 'mw-showband' ),
			'manage_options',
			'mwsb-import-demo',
			array( $this, 'render_admin_page' )
		);
	}

	private function eid() {
		return substr( str_replace( '.', '', uniqid( '', true ) ), -7 );
	}

	private function widget( $widget_type, $settings = array() ) {
		return array(
			'id'         => $this->eid(),
			'elType'     => 'widget',
			'isInner'    => false,
			'settings'   => (object) $settings,
			'elements'   => array(),
			'widgetType' => $widget_type,
		);
	}

	private function section( $widget_node ) {
		return array(
			'id'       => $this->eid(),
			'elType'   => 'section',
			'isInner'  => false,
			'settings' => (object) array(),
			'elements' => array(
				array(
					'id'       => $this->eid(),
					'elType'   => 'column',
					'isInner'  => false,
					'settings' => (object) array( '_column_size' => 100 ),
					'elements' => array( $widget_node ),
				),
			),
		);
	}

	/**
	 * Builds the exact homepage structure in the approved design order.
	 * Every widget relies on its own registered defaults, so this recreates
	 * the full MW Showband homepage without duplicating content here.
	 */
	private function build_page_data() {
		$widget_types = array(
			'mwsb-header',
			'mwsb-hero',
			'mwsb-experience-cards',
			'mwsb-stats',
			'mwsb-feature-split',
			'mwsb-energy-timeline',
			'mwsb-song-list',
			'mwsb-gallery',
			'mwsb-video-gallery',
			'mwsb-testimonials',
			'mwsb-founders',
			'mwsb-worldwide',
			'mwsb-event-planner',
			'mwsb-faq',
			'mwsb-final-cta',
			'mwsb-footer',
		);

		$sections = array();
		foreach ( $widget_types as $type ) {
			$sections[] = $this->section( $this->widget( $type ) );
		}

		return $sections;
	}

	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$imported_id = (int) get_option( self::OPTION_KEY );
		$elementor_active = did_action( 'elementor/loaded' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'MW Showband — Import Demo Data', 'mw-showband' ); ?></h1>
			<p><?php esc_html_e( 'This builds the full approved homepage (Header, Hero, Experience, Stats, and every other section) as a real, fully editable Elementor page, and sets it as your site\'s homepage — in one click.', 'mw-showband' ); ?></p>

			<?php if ( ! $elementor_active ) : ?>
				<div class="notice notice-error"><p><?php esc_html_e( 'Elementor is not active. Please install and activate Elementor before importing the demo.', 'mw-showband' ); ?></p></div>
			<?php endif; ?>

			<?php if ( isset( $_GET['mwsb_status'] ) && 'imported' === $_GET['mwsb_status'] ) : ?>
				<div class="notice notice-success"><p><?php esc_html_e( 'Demo homepage imported successfully and set as your front page.', 'mw-showband' ); ?></p></div>
			<?php elseif ( isset( $_GET['mwsb_status'] ) && 'reset' === $_GET['mwsb_status'] ) : ?>
				<div class="notice notice-success"><p><?php esc_html_e( 'Demo homepage removed.', 'mw-showband' ); ?></p></div>
			<?php endif; ?>

			<?php if ( $imported_id && get_post( $imported_id ) ) : ?>
				<p>
					<strong><?php esc_html_e( 'Status:', 'mw-showband' ); ?></strong>
					<?php esc_html_e( 'Demo homepage already imported.', 'mw-showband' ); ?>
					&nbsp;
					<a class="button" href="<?php echo esc_url( get_permalink( $imported_id ) ); ?>" target="_blank"><?php esc_html_e( 'View Homepage', 'mw-showband' ); ?></a>
					<a class="button" href="<?php echo esc_url( admin_url( 'post.php?post=' . $imported_id . '&action=elementor' ) ); ?>"><?php esc_html_e( 'Edit with Elementor', 'mw-showband' ); ?></a>
				</p>

				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'This deletes the currently imported demo page. Continue?', 'mw-showband' ) ); ?>');">
					<?php wp_nonce_field( 'mwsb_reset_demo' ); ?>
					<input type="hidden" name="action" value="mwsb_reset_demo" />
					<?php submit_button( __( 'Remove Demo Homepage', 'mw-showband' ), 'delete' ); ?>
				</form>
			<?php else : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'mwsb_import_demo' ); ?>
					<input type="hidden" name="action" value="mwsb_import_demo" />
					<?php submit_button( __( 'Import Demo Content', 'mw-showband' ), 'primary', 'submit', false, $elementor_active ? array() : array( 'disabled' => 'disabled' ) ); ?>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}

	public function handle_import() {
		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'mwsb_import_demo' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'mw-showband' ) );
		}

		if ( ! did_action( 'elementor/loaded' ) ) {
			wp_die( esc_html__( 'Elementor must be active to import the demo.', 'mw-showband' ) );
		}

		// Remove any previously imported demo page first.
		$existing_id = (int) get_option( self::OPTION_KEY );
		if ( $existing_id && get_post( $existing_id ) ) {
			wp_delete_post( $existing_id, true );
		}

		$page_id = wp_insert_post(
			array(
				'post_title'   => __( 'Home', 'mw-showband' ),
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			)
		);

		if ( is_wp_error( $page_id ) || ! $page_id ) {
			wp_die( esc_html__( 'Could not create the demo homepage.', 'mw-showband' ) );
		}

		$elementor_data = $this->build_page_data();

		update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $elementor_data ) ) );
		update_post_meta( $page_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $page_id, '_elementor_template_type', 'wp-page' );
		update_post_meta( $page_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_id );
		update_option( self::OPTION_KEY, $page_id );

		if ( class_exists( '\Elementor\Plugin' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		wp_safe_redirect( admin_url( 'themes.php?page=mwsb-import-demo&mwsb_status=imported' ) );
		exit;
	}

	public function handle_reset() {
		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'mwsb_reset_demo' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'mw-showband' ) );
		}

		$page_id = (int) get_option( self::OPTION_KEY );
		if ( $page_id && get_post( $page_id ) ) {
			wp_delete_post( $page_id, true );
		}
		delete_option( self::OPTION_KEY );

		if ( (int) get_option( 'page_on_front' ) === $page_id ) {
			update_option( 'show_on_front', 'posts' );
			update_option( 'page_on_front', 0 );
		}

		wp_safe_redirect( admin_url( 'themes.php?page=mwsb-import-demo&mwsb_status=reset' ) );
		exit;
	}
}

MWSB_Demo_Importer::instance();
