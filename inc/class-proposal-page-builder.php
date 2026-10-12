<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * One-click builder for the "UK Event — Paul & Victoria" sales proposal page,
 * rebuilt from the client's second ("2030 Futuristic Spectacle") Stitch
 * redesign (D:\shopify\website-files\showband\stitch band\propsal page\).
 *
 * This revision replaces the first attempt, which reused the main site's
 * existing widgets and tokens.css palette — the client found that version
 * not representative of the new Stitch design (different palette, type,
 * card layouts and an interactive pricing calculator). This version is
 * built from five dedicated widgets (inc/widgets/class-proposal-*.php) with
 * their own bespoke, page-scoped skin (assets/css/proposal.css,
 * assets/js/proposal.js) that cannot bleed into or break the main site —
 * every other page's widgets and tokens.css are completely untouched.
 *
 * Deliberately does NOT include header/footer widgets — the page relies on
 * whatever already renders the header/footer on every other page (Home,
 * About, Service, Contact), so there is exactly one source of truth for
 * site-wide chrome.
 *
 * Safety: this builder only ever creates/deletes the ONE page tracked by
 * self::OPTION_KEY. It never touches any other page, the header/footer
 * templates, or site-wide options (front page, menus, etc).
 *
 * Section -> widget mapping:
 *   Cinematic hero (personalized)   -> mwsb-proposal-hero
 *   Event timeline (4 stages)       -> mwsb-proposal-timeline
 *   Packages / pricing (3 tiers)    -> mwsb-proposal-packages
 *   Investment & payment schedule   -> mwsb-proposal-investment (live-updated
 *                                      by a package card's select button via
 *                                      assets/js/proposal.js)
 *   Closing CTA + sign-off          -> mwsb-proposal-cta
 *
 * Author: Aqib Dev
 */
final class MWSB_Proposal_Page_Builder {

	const OPTION_KEY = 'mwsb_proposal_page_id';
	const SLUG       = 'proposal-uk-event-paul';

	public static function instance() {
		static $instance = null;
		if ( is_null( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}

	private function __construct() {
		add_action( 'admin_menu', array( $this, 'register_admin_page' ) );
		add_action( 'admin_post_mwsb_build_proposal', array( $this, 'handle_build' ) );
		add_action( 'admin_post_mwsb_remove_proposal', array( $this, 'handle_remove' ) );
	}

	public function register_admin_page() {
		add_theme_page(
			__( 'Proposal Page (Paul)', 'mw-showband' ),
			__( 'Proposal Page (Paul)', 'mw-showband' ),
			'manage_options',
			'mwsb-proposal-page',
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
		// A full-width, zero-padding flex Container (not a classic Section > Column). A classic
		// Section defaults to a boxed .elementor-container (site content width) plus 10px column
		// padding, which squeezed every widget — and clipped each widget's full-bleed section
		// background — narrower than the header/footer. The widget's own .mwsb-container already
		// handles the boxed content width + gutter, so the wrapper must be edge to edge.
		$zero = array(
			'unit'     => 'px',
			'top'      => '0',
			'right'    => '0',
			'bottom'   => '0',
			'left'     => '0',
			'isLinked' => true,
		);
		return array(
			'id'       => $this->eid(),
			'elType'   => 'container',
			'isInner'  => false,
			'settings' => (object) array(
				'content_width'  => 'boxed',
				'boxed_width'    => array( 'unit' => 'px', 'size' => 1600, 'sizes' => array() ),
				'flex_direction' => 'column',
				'padding'        => $zero,
				'margin'         => $zero,
			),
			'elements' => array( $widget_node ),
		);
	}

	/** The real proposal content, pulled from the "2030 Futuristic Spectacle" Stitch redesign. */
	private function build_page_data() {
		$sections = array();

		$sections[] = $this->section(
			$this->widget(
				'mwsb-proposal-hero',
				array(
					'live_text'     => 'PRIVATE EVENT PROPOSAL // MW SHOWBAND',
					'status_text'   => '[ STATUS: CONFIDENTIAL // EXCLUSIVELY PREPARED ]',
					'eyebrow'       => 'BESPOKE ENTERTAINMENT CURATION',
					'title_white'   => "LET'S MAKE THIS",
					'title_gradient' => 'NIGHT UNFORGETTABLE.',
					'show_strip'    => 'yes',
					'strip_items'   => array(
						array( 'icon' => array( 'value' => 'fas fa-star', 'library' => 'fa-solid' ), 'label' => 'PREPARED EXCLUSIVELY FOR', 'value' => 'Paul & Victoria' ),
						array( 'icon' => array( 'value' => 'fas fa-champagne-glasses', 'library' => 'fa-solid' ), 'label' => 'OCCASION', 'value' => 'Private Celebration Gala' ),
						array( 'icon' => array( 'value' => 'fas fa-location-dot', 'library' => 'fa-solid' ), 'label' => 'SANCTUARY VENUE', 'value' => 'Aynhoe Park, Oxfordshire' ),
						array( 'icon' => array( 'value' => 'fas fa-calendar-check', 'library' => 'fa-solid' ), 'label' => 'RESERVED TIMELINE', 'value' => 'Saturday, 18th July 2026' ),
					),
					'primary_text'  => 'VIEW PACKAGES',
					'primary_link'  => array( 'url' => '#packages' ),
					'ghost_text'    => 'CONTACT US',
					'ghost_link'    => array( 'url' => 'mailto:info@mw-showband.com' ),
					'scroll_text'   => 'SCROLL TO DISCOVER THE PROPOSAL',
					'scroll_target' => '#mwsb-event-timeline',
				)
			)
		);

		$sections[] = $this->section(
			$this->widget(
				'mwsb-proposal-timeline',
				array(
					'anchor_id'   => 'mwsb-event-timeline',
					'eyebrow'     => '// 02 / BESPOKE EVENT TIMELINE',
					'title'       => 'AN EXPERIENCE DESIGNED AROUND YOU.',
					'description' => 'Four curated musical chapters engineered to elevate every phase of your celebration, from atmospheric arrival to stadium-scale after-party.',
					'stages'      => array(
						array( 'stage_num' => 'STAGE 01', 'time_range' => '17:30 – 19:00', 'title' => 'GUEST ARRIVAL', 'text' => 'Intimate, sophisticated acoustic lounge & champagne jazz to welcome guests with bespoke warmth.', 'setup_icon' => array( 'value' => 'fas fa-music', 'library' => 'fa-solid' ), 'setup_text' => 'Live Sax & Keys Duo' ),
						array( 'stage_num' => 'STAGE 02', 'time_range' => '19:30 – 21:30', 'title' => 'DINNER MUSIC', 'text' => 'Cinematic Motown, Soul & contemporary neo-jazz ballads engineered at conversation-friendly acoustic perfection.', 'setup_icon' => array( 'value' => 'fas fa-compact-disc', 'library' => 'fa-solid' ), 'setup_text' => '4-Piece Semi-Acoustic' ),
						array( 'stage_num' => 'STAGE 03', 'time_range' => '22:00 – 00:00', 'title' => 'THE PARTY', 'text' => 'Explosive arena-energy showband. Non-stop mashups, high-octane brass, synchronized choreography and epic vocal power.', 'setup_icon' => array( 'value' => 'fas fa-users', 'library' => 'fa-solid' ), 'setup_text' => 'Full Showband Ensemble' ),
						array( 'stage_num' => 'STAGE 04', 'time_range' => '00:00 – 02:00', 'title' => 'AFTER PARTY', 'text' => 'Club-scale festival finish. Live brass & roaming percussion infused over seamless hybrid DJ sets.', 'setup_icon' => array( 'value' => 'fas fa-headphones', 'library' => 'fa-solid' ), 'setup_text' => 'Live Band DJ Fusion + LED Sax' ),
					),
				)
			)
		);

		$sections[] = $this->section(
			$this->widget(
				'mwsb-proposal-packages',
				array(
					'anchor_id'   => 'packages',
					'eyebrow'     => '// 03 / PERFORMANCE ARCHITECTURE',
					'title'       => 'THREE WAYS TO OWN THE NIGHT.',
					'description' => 'Every package includes our uncompromising sound engineering, custom musical arrangements, and dedicated production management. Select the line-up that matches your venue scale.',
					'cards'       => array(
						array(
							'accent'           => 'purple',
							'badge'            => 'CONFIGURATION 01',
							'capacity'         => 'UP TO 150 GUESTS',
							'title'            => 'DYNAMIC 8-PIECE',
							'price'            => '£8,000',
							'price_note'       => '(+ VAT)',
							'text'             => 'Ideal for intimate luxury celebrations & boutique estates requiring pristine acoustic agility and electric presence.',
							'ensemble_title'   => 'ENSEMBLE COMPOSITION',
							'ensemble_items'   => "3 Lead Vocalists (2 Female, 1 Male Frontman)\n4-Piece Rhythm Section (Drums, Bass, Guitar, Keys)\n1 Horn (Saxophone / Multi-instrumentalist)",
							'inclusions_title' => 'CORE INCLUSIONS',
							'inclusions_items' => "2 x 60 min explosive live sets\nDedicated Front-of-House Sound Engineer\nWireless microphone system for toasts & speeches\nStandard architectural stage lighting package",
							'spec_text'        => 'VIEW SPECIFICATION',
							'spec_link'        => array( 'url' => '#' ),
							'select_text'      => 'SELECT THIS LINEUP',
							'tier_price'       => 8000,
						),
						array(
							'accent'           => 'magenta',
							'badge'            => 'CONFIGURATION 02',
							'capacity'         => '150–350 GUESTS',
							'title'            => 'HIGH ENERGY 10-PIECE',
							'price'            => '£9,500',
							'price_note'       => '(+ VAT)',
							'text'             => 'The definitive showband experience for grand ballrooms & marquee galas requiring massive vocal depth and brass punch.',
							'ensemble_title'   => 'ENSEMBLE COMPOSITION',
							'ensemble_items'   => "4 Vocalists (4-part harmonies, rap/soul versatile)\n4-Piece Rhythm Section (Drums, Bass, Guitar, Keys)\n2-Piece Power Horn Section (Saxophone & Trumpet)",
							'inclusions_title' => 'CORE INCLUSIONS',
							'inclusions_items' => "2 x 60 min or 1 x 120 min seamless headline show\nFull digital sound desk & timecoded lighting stems\nBespoke First Dance arrangement orchestrations\nIntegrated DJ service during performer intervals",
							'spec_text'        => 'VIEW SPECIFICATION',
							'spec_link'        => array( 'url' => '#' ),
							'select_text'      => 'SELECT THIS LINEUP',
							'tier_price'       => 9500,
						),
						array(
							'accent'           => 'cyan',
							'badge'            => 'CONFIGURATION 03',
							'capacity'         => '250+ GUESTS',
							'title'            => 'FULL EXPERIENCE 12-PIECE',
							'price'            => '£10,500',
							'price_note'       => '(+ VAT)',
							'text'             => 'Stadium-grade sonic power for high-impact international productions requiring full horn brass section and live percussion.',
							'ensemble_title'   => 'ENSEMBLE COMPOSITION',
							'ensemble_items'   => "5 Frontline Vocalists\n4-Piece Rhythm Section (Drums, Bass, Guitar, Keys)\n3-Piece Horns (Trumpet, Tenor Sax, Trombone) + Live Percussion",
							'inclusions_title' => 'CORE INCLUSIONS',
							'inclusions_items' => "Cocktail acoustic duo + Dinner ambiance set included\n2 x 75 min headline concert encore\nFull festival audio-visual tech rider execution\nComprehensive musical direction & custom orchestrations",
							'spec_text'        => 'VIEW SPECIFICATION',
							'spec_link'        => array( 'url' => '#' ),
							'select_text'      => 'SELECT THIS LINEUP',
							'tier_price'       => 10500,
						),
					),
				)
			)
		);

		$sections[] = $this->section(
			$this->widget(
				'mwsb-proposal-investment',
				array(
					'eyebrow'       => '// 04 / FINANCIAL TRANSPARENCY & SCHEDULE',
					'title'         => 'INVESTMENT & PAYMENT SCHEDULE.',
					'description'   => 'Complete fee transparency with no hidden riders. Clear three-stage milestone payments aligned with your event planning roadmap.',
					'tier_tag'      => '10-PIECE HIGH ENERGY (SELECTED)',
					'base_label'    => 'Base Performance Ensemble Fee',
					'base_amount'   => '£9,500.00',
					'included_rows' => array(
						array( 'label' => 'Sound, Production & Backline Logistics' ),
						array( 'label' => 'UK Nationwide Travel & Crew Tour Logistics' ),
						array( 'label' => 'Bespoke Song Orchestrations (up to 3 tracks)' ),
					),
					'vat_label'     => 'UK Standard VAT (20%)',
					'vat_amount'    => '£1,900.00',
					'total_label'   => 'TOTAL ALL-INCLUSIVE PROPOSAL',
					'total_note'    => 'Guaranteed fixed rate through 2026',
					'total_amount'  => '£11,400.00',
					'note_text'     => 'Includes comprehensive public liability insurance (£10M), PAT certified audio apparatus, venue liaison, sound limiter compliance protocol and discrete VIP rider handling.',
					'phases_label'  => '3 EQUITABLE PHASES',
					'milestones'    => array(
						array( 'title' => '40% BOOKING DEPOSIT', 'amount' => '£3,800 + VAT', 'due' => 'UPON FORMAL ENGAGEMENT', 'text' => 'Secures the exclusive date lock for Saturday, 18th July 2026. Locks musicians, front-line vocalists, and initiates client concierge onboarding.', 'active' => 'yes' ),
						array( 'title' => '40% MID-TERM PAYMENT', 'amount' => '£3,800 + VAT', 'due' => 'DUE 18TH MAY 2026 (2 MONTHS PRIOR)', 'text' => 'Initiates bespoke song orchestrations, rehearsal block bookings, and Aynhoe Park technical site survey coordination with the venue manager.', 'active' => '' ),
						array( 'title' => '20% FINAL BALANCE', 'amount' => '£1,900 + VAT', 'due' => 'DUE 4TH JULY 2026 (2 WEEKS PRIOR)', 'text' => 'Final setlist sign-off, sound technician call-sheet lockdown, and logistics dispatch for flawless event execution.', 'active' => '' ),
					),
				)
			)
		);

		$sections[] = $this->section(
			$this->widget(
				'mwsb-proposal-cta',
				array(
					'badge_text'     => 'PROPOSAL PROTOCOL READY',
					'title_white'    => 'YOUR NIGHT.',
					'title_gradient' => 'OUR NEXT SHOW.',
					'description'    => "Everything starts with a conversation. Let's bring your vision to life and build the ultimate musical experience for Paul & Victoria.",
					'primary_text'   => 'DISCUSS YOUR PROPOSAL',
					'primary_link'   => array( 'url' => 'mailto:info@mw-showband.com?subject=Private%20Proposal%20Confirmation%20-%20Paul%20%26%20Victoria' ),
					'secondary_icon' => array( 'value' => 'fas fa-phone', 'library' => 'fa-solid' ),
					'secondary_text' => '+44 7707 159384',
					'secondary_link' => array( 'url' => 'tel:+447707159384' ),
					'show_signoff'   => 'yes',
					'signoff_name'   => 'Marlon & Wayne',
					'signoff_role'   => 'Musical & Creative Directors',
					'signoff_note'   => 'MW Showband Ltd. • Confidential Client Copy • Valid for 30 days from date of presentation.',
				)
			)
		);

		return $sections;
	}

	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$page_id           = (int) get_option( self::OPTION_KEY );
		$elementor_active  = did_action( 'elementor/loaded' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Build Proposal Page — UK Event (Paul & Victoria)', 'mw-showband' ); ?></h1>
			<p><?php esc_html_e( 'Recreates the client-approved Stitch redesign (personalized hero, event timeline, 3-tier pricing with a live payment calculator, closing CTA) as a real, fully editable Elementor page — built entirely from existing MW Showband widgets, using the site\'s own header/footer.', 'mw-showband' ); ?></p>

			<?php if ( ! $elementor_active ) : ?>
				<div class="notice notice-error"><p><?php esc_html_e( 'Elementor is not active. Please install and activate Elementor before building this page.', 'mw-showband' ); ?></p></div>
			<?php endif; ?>

			<?php if ( isset( $_GET['mwsb_status'] ) && 'built' === $_GET['mwsb_status'] ) : ?>
				<div class="notice notice-success"><p><?php esc_html_e( 'Proposal page built successfully.', 'mw-showband' ); ?></p></div>
			<?php elseif ( isset( $_GET['mwsb_status'] ) && 'removed' === $_GET['mwsb_status'] ) : ?>
				<div class="notice notice-success"><p><?php esc_html_e( 'Proposal page removed.', 'mw-showband' ); ?></p></div>
			<?php endif; ?>

			<?php if ( $page_id && get_post( $page_id ) ) : ?>
				<p>
					<strong><?php esc_html_e( 'Status:', 'mw-showband' ); ?></strong>
					<?php esc_html_e( 'Proposal page already built.', 'mw-showband' ); ?>
					&nbsp;
					<a class="button" href="<?php echo esc_url( get_permalink( $page_id ) ); ?>" target="_blank"><?php esc_html_e( 'View Page', 'mw-showband' ); ?></a>
					<a class="button" href="<?php echo esc_url( admin_url( 'post.php?post=' . $page_id . '&action=elementor' ) ); ?>"><?php esc_html_e( 'Edit with Elementor', 'mw-showband' ); ?></a>
				</p>

				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'This deletes the built proposal page. Continue?', 'mw-showband' ) ); ?>');">
					<?php wp_nonce_field( 'mwsb_remove_proposal' ); ?>
					<input type="hidden" name="action" value="mwsb_remove_proposal" />
					<?php submit_button( __( 'Remove Proposal Page', 'mw-showband' ), 'delete' ); ?>
				</form>
			<?php else : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'mwsb_build_proposal' ); ?>
					<input type="hidden" name="action" value="mwsb_build_proposal" />
					<?php submit_button( __( 'Build Proposal Page', 'mw-showband' ), 'primary', 'submit', false, $elementor_active ? array() : array( 'disabled' => 'disabled' ) ); ?>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}

	public function handle_build() {
		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'mwsb_build_proposal' ) ) {
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
				'post_title'   => __( 'Proposal — UK Event (Paul & Victoria)', 'mw-showband' ),
				'post_name'    => self::SLUG,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			)
		);

		if ( is_wp_error( $page_id ) || ! $page_id ) {
			wp_die( esc_html__( 'Could not create the proposal page.', 'mw-showband' ) );
		}

		$elementor_data = $this->build_page_data();

		update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $elementor_data ) ) );
		update_post_meta( $page_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $page_id, '_elementor_template_type', 'wp-page' );
		update_post_meta( $page_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );

		update_option( self::OPTION_KEY, $page_id );

		if ( class_exists( '\Elementor\Plugin' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		wp_safe_redirect( admin_url( 'themes.php?page=mwsb-proposal-page&mwsb_status=built' ) );
		exit;
	}

	public function handle_remove() {
		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'mwsb_remove_proposal' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'mw-showband' ) );
		}

		$page_id = (int) get_option( self::OPTION_KEY );
		if ( $page_id && get_post( $page_id ) ) {
			wp_delete_post( $page_id, true );
		}
		delete_option( self::OPTION_KEY );

		wp_safe_redirect( admin_url( 'themes.php?page=mwsb-proposal-page&mwsb_status=removed' ) );
		exit;
	}
}

MWSB_Proposal_Page_Builder::instance();
