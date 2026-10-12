<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the "MW Showband" Elementor widget category, enqueues the
 * bundled design-system assets (fonts, CSS tokens, shared JS), and loads
 * every widget class shipped with this theme.
 *
 * Author: Aqib Dev
 */
final class MWSB_Elementor_Widgets {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		// NOTE: a theme's functions.php is required by WordPress core AFTER the
		// 'plugins_loaded' action has already fired, so hooking 'plugins_loaded'
		// from here would never run. 'after_setup_theme' is the correct point.
		add_action( 'after_setup_theme', array( $this, 'init' ) );
	}

	public function init() {
		if ( ! did_action( 'elementor/loaded' ) && ! class_exists( '\Elementor\Plugin' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_elementor' ) );
			return;
		}

		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_frontend_assets' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue_frontend_assets' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'admin_notices', array( $this, 'admin_notice_widget_errors' ) );
	}

	public function admin_notice_widget_errors() {
		$errors = get_option( 'mwsb_widget_load_errors' );
		if ( empty( $errors ) || ! is_array( $errors ) ) {
			return;
		}
		echo '<div class="notice notice-error"><p><strong>MW Showband — widget loading errors:</strong></p><ul style="list-style:disc;margin-left:20px;">';
		foreach ( $errors as $error ) {
			echo '<li>' . esc_html( $error ) . '</li>';
		}
		echo '</ul></div>';
	}

	public function admin_notice_missing_elementor() {
		echo '<div class="notice notice-warning"><p>' .
			esc_html__( 'The MW Showband theme requires Elementor (and Elementor Pro for the Theme Builder) to be installed and activated.', 'mw-showband' ) .
			'</p></div>';
	}

	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			'mw-showband',
			array(
				'title' => __( 'MW Showband', 'mw-showband' ),
				'icon'  => 'eicon-theme-style',
			)
		);
	}

	public function enqueue_frontend_assets() {
		wp_enqueue_style(
			'mwsb-google-fonts',
			'https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500&display=swap',
			array(),
			null
		);

		wp_enqueue_style(
			'mwsb-material-symbols',
			'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap',
			array(),
			null
		);

		wp_enqueue_style(
			'mwsb-tokens',
			MWSB_THEME_URL . '/assets/css/tokens.css',
			array(),
			MWSB_THEME_VERSION
		);

		/*
		 * Proposal page bespoke skin — registered (not enqueued) so it only
		 * loads on the one page that actually uses these widgets, via each
		 * widget's get_style_depends()/get_script_depends(). Never touches
		 * the main site's own tokens.css palette.
		 */
		wp_register_style(
			'mwsb-proposal-fonts',
			'https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap',
			array(),
			null
		);
		wp_register_style(
			'mwsb-proposal',
			MWSB_THEME_URL . '/assets/css/proposal.css',
			array( 'mwsb-tokens', 'mwsb-proposal-fonts' ),
			MWSB_THEME_VERSION
		);
		// Thank-you page skin — registered only; the widget pulls it in through get_style_depends().
		wp_register_style(
			'mwsb-thankyou',
			MWSB_THEME_URL . '/assets/css/thankyou.css',
			array( 'mwsb-tokens' ),
			MWSB_THEME_VERSION
		);
		wp_register_script(
			'mwsb-proposal',
			MWSB_THEME_URL . '/assets/js/proposal.js',
			array(),
			MWSB_THEME_VERSION,
			true
		);

		wp_enqueue_style(
			'mwsb-widgets',
			MWSB_THEME_URL . '/assets/css/widgets.css',
			array( 'mwsb-tokens' ),
			MWSB_THEME_VERSION
		);

		wp_enqueue_script(
			'mwsb-widgets',
			MWSB_THEME_URL . '/assets/js/widgets.js',
			array( 'jquery' ),
			MWSB_THEME_VERSION,
			true
		);

		wp_localize_script(
			'mwsb-widgets',
			'mwsbData',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			)
		);

		// Fluid Glitch Slider — Swiper.js engine + our own slider/fluid modules.
		wp_enqueue_style( 'swiper', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.1.4/swiper-bundle.min.css', array(), '11.1.4' );
		wp_enqueue_script( 'swiper', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.1.4/swiper-bundle.min.js', array(), '11.1.4', true );

		wp_enqueue_style(
			'mwsb-fluid-glitch-slider',
			MWSB_THEME_URL . '/assets/css/fluid-glitch-slider.css',
			array( 'mwsb-tokens', 'swiper' ),
			MWSB_THEME_VERSION
		);

		wp_enqueue_script( 'mwsb-fluid-simulation', MWSB_THEME_URL . '/assets/js/fluid-simulation.js', array(), MWSB_THEME_VERSION, true );
		wp_enqueue_script( 'mwsb-motion-engine', MWSB_THEME_URL . '/assets/js/motion-engine.js', array(), MWSB_THEME_VERSION, true );
		wp_enqueue_script( 'mwsb-glitch-engine', MWSB_THEME_URL . '/assets/js/glitch-engine.js', array(), MWSB_THEME_VERSION, true );

		wp_enqueue_script(
			'mwsb-fluid-glitch-slider',
			MWSB_THEME_URL . '/assets/js/fluid-glitch-slider.js',
			array( 'swiper', 'mwsb-fluid-simulation', 'mwsb-motion-engine', 'mwsb-glitch-engine' ),
			MWSB_THEME_VERSION,
			true
		);

		// Video Gallery widget's optional "Instagram Carousel" layout — a plain Swiper row of
		// embedded Instagram players, no API/access token involved (see the widget's own
		// mwsb_instagram_embed_url() for the oEmbed iframe it points each slide at).
		wp_enqueue_script(
			'mwsb-video-gallery-carousel',
			MWSB_THEME_URL . '/assets/js/video-gallery-carousel.js',
			array( 'swiper' ),
			MWSB_THEME_VERSION,
			true
		);
	}

	public function register_widgets( $widgets_manager ) {
		$errors = array();

		try {
			require_once MWSB_THEME_PATH . '/inc/trait-section-controls.php';
		} catch ( \Throwable $e ) {
			$errors[] = 'trait-section-controls.php: ' . $e->getMessage() . ' (line ' . $e->getLine() . ')';
		}

		try {
			require_once MWSB_THEME_PATH . '/inc/trait-proposal-style-controls.php';
		} catch ( \Throwable $e ) {
			$errors[] = 'trait-proposal-style-controls.php: ' . $e->getMessage() . ' (line ' . $e->getLine() . ')';
		}

		$widget_files = array(
			'class-header-widget.php'          => 'MWSB_Header_Widget',
			'class-hero-widget.php'             => 'MWSB_Hero_Widget',
			'class-about-hero-widget.php'       => 'MWSB_About_Hero_Widget',
			'class-dual-founders-widget.php'    => 'MWSB_Dual_Founders_Widget',
			'class-service-hero-widget.php'     => 'MWSB_Service_Hero_Widget',
			'class-service-cards-widget.php'    => 'MWSB_Service_Cards_Widget',
			'class-contact-hero-widget.php'     => 'MWSB_Contact_Hero_Widget',
			'class-location-map-widget.php'     => 'MWSB_Location_Map_Widget',
			'class-experience-cards-widget.php' => 'MWSB_Experience_Cards_Widget',
			'class-stats-widget.php'            => 'MWSB_Stats_Widget',
			'class-feature-split-widget.php'    => 'MWSB_Feature_Split_Widget',
			'class-energy-timeline-widget.php'  => 'MWSB_Energy_Timeline_Widget',
			'class-song-list-widget.php'        => 'MWSB_Song_List_Widget',
			'class-gallery-widget.php'          => 'MWSB_Gallery_Widget',
			'class-video-gallery-widget.php'    => 'MWSB_Video_Gallery_Widget',
			'class-testimonials-widget.php'     => 'MWSB_Testimonials_Widget',
			'class-founders-widget.php'         => 'MWSB_Founders_Widget',
			'class-worldwide-widget.php'        => 'MWSB_Worldwide_Widget',
			'class-event-planner-widget.php'    => 'MWSB_Event_Planner_Widget',
			'class-faq-widget.php'              => 'MWSB_Faq_Widget',
			'class-final-cta-widget.php'        => 'MWSB_Final_Cta_Widget',
			'class-contact-form-widget.php'     => 'MWSB_Contact_Form_Widget',
			'class-fluid-glitch-slider.php'     => 'MWSB_Fluid_Glitch_Slider_Widget',
			'class-footer-widget.php'           => 'MWSB_Footer_Widget',
			'class-thank-you-widget.php'        => 'MWSB_Thank_You_Widget',
			// Sales proposal page — bespoke "2030 Futuristic Spectacle" widgets.
			'class-proposal-hero-widget.php'       => 'MWSB_Proposal_Hero_Widget',
			'class-proposal-timeline-widget.php'   => 'MWSB_Proposal_Timeline_Widget',
			'class-proposal-packages-widget.php'   => 'MWSB_Proposal_Packages_Widget',
			'class-proposal-investment-widget.php' => 'MWSB_Proposal_Investment_Widget',
			'class-proposal-cta-widget.php'        => 'MWSB_Proposal_Cta_Widget',
		);

		foreach ( $widget_files as $file => $class_name ) {
			$path = MWSB_THEME_PATH . '/inc/widgets/' . $file;

			if ( ! file_exists( $path ) ) {
				$errors[] = $file . ': file not found at ' . $path;
				continue;
			}

			try {
				require_once $path;
			} catch ( \Throwable $e ) {
				$errors[] = $file . ': ' . $e->getMessage() . ' (line ' . $e->getLine() . ')';
				continue;
			}

			if ( ! class_exists( $class_name ) ) {
				$errors[] = $file . ': class ' . $class_name . ' was not defined after include.';
				continue;
			}

			try {
				$widgets_manager->register( new $class_name() );
			} catch ( \Throwable $e ) {
				$errors[] = $class_name . ': ' . $e->getMessage() . ' (line ' . $e->getLine() . ')';
			}
		}

		update_option( 'mwsb_widget_load_errors', $errors, false );
	}
}

MWSB_Elementor_Widgets::instance();
