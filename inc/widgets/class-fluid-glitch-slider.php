<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

/**
 * MW Showband — Fluid Glitch Hero Slider.
 *
 * A cinematic hero slider combining:
 *  - Swiper.js as the slide engine
 *  - A per-slide typewriter-animated sub-heading
 *  - A canvas glitch transition between slides (RGB split + noise bands)
 *  - A WebGL mouse-reactive fluid/dye simulation overlay
 *  - A thumbnail navigation strip + autoplay progress ring
 *
 * Built in stages (see BUILD ORDER in project notes). This file currently
 * implements Stage 1: the widget shell, its Elementor controls, and a
 * static Swiper carousel render — no glitch/fluid/typewriter behaviour yet.
 * Author: Aqib Dev
 */
class MWSB_Fluid_Glitch_Slider_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-fluid-glitch-slider';
	}

	public function get_title() {
		return __( 'MW Fluid Glitch Slider', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-slider-3d';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	public function get_script_depends() {
		return array( 'swiper', 'mwsb-motion-engine', 'mwsb-glitch-engine', 'mwsb-fluid-simulation', 'mwsb-fluid-glitch-slider' );
	}

	public function get_style_depends() {
		return array( 'swiper', 'mwsb-fluid-glitch-slider' );
	}

	protected function register_controls() {
		$this->register_slides_controls();
		$this->register_slider_settings_controls();
		$this->register_style_controls();
		$this->register_section_style_controls();
	}

	/**
	 * ---------------------------------------------------------------
	 * CONTENT TAB — Slides repeater
	 * ---------------------------------------------------------------
	 */
	private function register_slides_controls() {
		$this->start_controls_section(
			'slides_section',
			array(
				'label' => __( 'Slides', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'background_type',
			array(
				'label'   => __( 'Background Type', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => array(
					'image' => __( 'Image', 'mw-showband' ),
					'video' => __( 'Video', 'mw-showband' ),
				),
			)
		);

		$repeater->add_control(
			'background_image',
			array(
				'label'     => __( 'Background Image', 'mw-showband' ),
				'type'      => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ),
				'default'   => array( 'url' => Utils::get_placeholder_image_src() ),
				'condition' => array( 'background_type' => 'image' ),
			)
		);

		$repeater->add_control(
			'background_video',
			array(
				'label'       => __( 'Background Video', 'mw-showband' ),
				'type'        => Controls_Manager::MEDIA,
				'media_type'  => 'video',
				'description' => __( 'Upload a video to the Media Library, or paste a hosted .mp4 URL.', 'mw-showband' ),
				'condition'   => array( 'background_type' => 'video' ),
			)
		);

		$repeater->add_control(
			'video_poster',
			array(
				'label'       => __( 'Video Poster (fallback image)', 'mw-showband' ),
				'type'        => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ),
				'description' => __( 'Shown while the video loads / on mobile if autoplay is blocked.', 'mw-showband' ),
				'condition'   => array( 'background_type' => 'video' ),
			)
		);

		$repeater->add_control(
			'thumbnail_image',
			array(
				'label'       => __( 'Thumbnail Image', 'mw-showband' ),
				'type'        => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ),
				'default'     => array( 'url' => Utils::get_placeholder_image_src() ),
				'description' => __( 'Small image used in the bottom navigation strip. Leave empty to reuse the background image.', 'mw-showband' ),
			)
		);

		$repeater->add_control(
			'eyebrow',
			array(
				'label'   => __( 'Eyebrow / Kicker', 'mw-showband' ),
				'type'    => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'default' => 'MW SHOWBAND',
			)
		);

		$repeater->add_control(
			'heading',
			array(
				'label'   => __( 'Heading', 'mw-showband' ),
				'type'    => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'default' => 'CyberFunk',
			)
		);

		$repeater->add_control(
			'nav_title',
			array(
				'label'       => __( 'Thumbnail Nav Title', 'mw-showband' ),
				'type'        => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'default'     => 'CyberFunk',
				'description' => __( 'Short label shown in the bottom thumbnail strip (can differ from the main heading).', 'mw-showband' ),
			)
		);

		$repeater->add_control(
			'subheading',
			array(
				'label'       => __( 'Sub-heading (typewriter text)', 'mw-showband' ),
				'type'        => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ),
				'default'     => 'Get the dystopian vibes of a cyberfunk future in your next production.',
				'description' => __( 'This text types out character-by-character when the slide becomes active.', 'mw-showband' ),
			)
		);

		$repeater->add_control(
			'button_text',
			array(
				'label'   => __( 'Primary Button Text', 'mw-showband' ),
				'type'    => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'default' => 'CONTINUE',
			)
		);
		$repeater->add_control(
			'button_link',
			array(
				'label'   => __( 'Primary Button Link', 'mw-showband' ),
				'type'    => Controls_Manager::URL, 'dynamic' => array( 'active' => true ),
				'default' => array( 'url' => '#' ),
			)
		);
		$repeater->add_control(
			'button2_text',
			array(
				'label'   => __( 'Secondary Button Text', 'mw-showband' ),
				'type'    => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'default' => 'WATCH THE SHOW',
			)
		);
		$repeater->add_control(
			'button2_link',
			array(
				'label'   => __( 'Secondary Button Link', 'mw-showband' ),
				'type'    => Controls_Manager::URL, 'dynamic' => array( 'active' => true ),
				'default' => array( 'url' => '#' ),
			)
		);

		$repeater->add_control(
			'heading_2',
			array(
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Fluid Colour Palette (this slide)', 'mw-showband' ),
				'separator' => 'before',
			)
		);
		$repeater->add_control( 'fluid_color_1', array( 'label' => __( 'Primary', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'default' => '#871f82' ) );
		$repeater->add_control( 'fluid_color_2', array( 'label' => __( 'Secondary', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'default' => '#e71f85' ) );
		$repeater->add_control( 'fluid_color_3', array( 'label' => __( 'Tertiary', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'default' => '#f0821a' ) );

		$repeater->add_control(
			'heading_3',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Cinematic Motion (this slide)', 'mw-showband' ),
				'separator' => 'before',
			)
		);
		$repeater->add_control(
			'accent_color',
			array(
				'label'   => __( 'Accent Colour (spotlight, glitch flash)', 'mw-showband' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#a47cb6',
			)
		);
		$repeater->add_control(
			'ken_burns',
			array(
				'label'   => __( 'Ken Burns Movement', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'zoom-in',
				'options' => array(
					'zoom-in'    => __( 'Slow Zoom In', 'mw-showband' ),
					'zoom-out'   => __( 'Slow Zoom Out', 'mw-showband' ),
					'pan-left'   => __( 'Pan Left → Right', 'mw-showband' ),
					'pan-right'  => __( 'Pan Right → Left', 'mw-showband' ),
					'pan-up'     => __( 'Pan Up → Down', 'mw-showband' ),
					'pan-down'   => __( 'Pan Down → Up', 'mw-showband' ),
					'diagonal'   => __( 'Diagonal Drift', 'mw-showband' ),
				),
			)
		);

		$this->add_control(
			'slides',
			array(
				'label'       => __( 'Slides', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $this->get_default_slides(),
				'title_field' => '{{{ heading }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Dummy seed content so the widget isn't empty on first insert.
	 * Clearly placeholder copy — replace per-project.
	 */
	private function get_default_slides() {
		$placeholder = Utils::get_placeholder_image_src();

		return array(
			array(
				'eyebrow'      => 'MW SHOWBAND',
				'heading'      => 'CyberFunk',
				'nav_title'    => 'CyberFunk',
				'subheading'   => 'Get the dystopian vibes of a cyberfunk future in your next production. (placeholder copy)',
				'button_text'  => 'CONTINUE',
				'button2_text' => 'WATCH THE SHOW',
				'fluid_color_1' => '#871f82',
				'fluid_color_2' => '#e71f85',
				'fluid_color_3' => '#f0821a',
				'accent_color'  => '#a47cb6',
				'ken_burns'     => 'zoom-in',
			),
			array(
				'eyebrow'      => 'MW SHOWBAND',
				'heading'      => 'Meet The Future',
				'nav_title'    => 'Meet the future',
				'subheading'   => 'A stadium-grade live show engineered for the next decade of events. (placeholder copy)',
				'button_text'  => 'CONTINUE',
				'button2_text' => 'WATCH THE SHOW',
				'fluid_color_1' => '#e71f85',
				'fluid_color_2' => '#f0821a',
				'fluid_color_3' => '#871f82',
				'accent_color'  => '#e71f85',
				'ken_burns'     => 'pan-left',
			),
			array(
				'eyebrow'      => 'MW SHOWBAND',
				'heading'      => 'Next Level GFX',
				'nav_title'    => 'Next level GFX',
				'subheading'   => 'Concert-grade visuals and lighting synced to every beat of the set. (placeholder copy)',
				'button_text'  => 'CONTINUE',
				'button2_text' => 'WATCH THE SHOW',
				'fluid_color_1' => '#f0821a',
				'fluid_color_2' => '#871f82',
				'fluid_color_3' => '#EDA117',
				'accent_color'  => '#f0821a',
				'ken_burns'     => 'pan-up',
			),
			array(
				'eyebrow'      => 'MW SHOWBAND',
				'heading'      => 'Team Of Teams',
				'nav_title'    => 'Team of teams',
				'subheading'   => 'Ten to fourteen world-class musicians, one relentless performance. (placeholder copy)',
				'button_text'  => 'CONTINUE',
				'button2_text' => 'WATCH THE SHOW',
				'fluid_color_1' => '#EDA117',
				'fluid_color_2' => '#871f82',
				'fluid_color_3' => '#e71f85',
				'accent_color'  => '#EDA117',
				'ken_burns'     => 'diagonal',
			),
		);
	}

	/**
	 * ---------------------------------------------------------------
	 * CONTENT TAB — Slider behaviour settings
	 * ---------------------------------------------------------------
	 */
	private function register_slider_settings_controls() {
		$this->start_controls_section(
			'slider_settings_section',
			array(
				'label' => __( 'Slider Settings', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'autoplay', array( 'label' => __( 'Autoplay', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'autoplay_delay', array( 'label' => __( 'Autoplay Delay (ms)', 'mw-showband' ), 'type' => Controls_Manager::NUMBER, 'default' => 6000, 'condition' => array( 'autoplay' => 'yes' ) ) );
		$this->add_control( 'pause_on_hover', array( 'label' => __( 'Pause Autoplay on Hover', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => array( 'autoplay' => 'yes' ) ) );
		$this->add_control( 'typing_speed', array( 'label' => __( 'Typewriter Speed (ms/char)', 'mw-showband' ), 'type' => Controls_Manager::NUMBER, 'min' => 20, 'max' => 120, 'default' => 40 ) );

		$this->add_control(
			'video_heading',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Video Background Behaviour', 'mw-showband' ),
				'separator' => 'before',
			)
		);
		$this->add_control( 'video_autoplay', array( 'label' => __( 'Autoplay Videos', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'video_muted', array( 'label' => __( 'Mute Videos', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'description' => __( 'Browsers block autoplay unless the video is muted.', 'mw-showband' ) ) );
		$this->add_control( 'video_loop', array( 'label' => __( 'Loop Videos', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'video_controls', array( 'label' => __( 'Show Native Video Controls', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => '' ) );

		$this->add_control(
			'enable_glitch',
			array(
				'label'   => __( 'Glitch Transition', 'mw-showband' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'enable_fluid',
			array(
				'label'   => __( 'Fluid Mouse Effect', 'mw-showband' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'disable_fluid_mobile',
			array(
				'label'     => __( 'Disable Fluid Effect on Mobile/Touch', 'mw-showband' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array( 'enable_fluid' => 'yes' ),
			)
		);

		$this->add_control(
			'enable_parallax',
			array( 'label' => __( 'Mouse Parallax', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' )
		);
		$this->add_control(
			'enable_spotlight',
			array( 'label' => __( 'Concert Spotlight Sweep', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' )
		);
		$this->add_control(
			'enable_waveform',
			array( 'label' => __( 'Decorative Waveform', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' )
		);
		$this->add_control(
			'enable_scanline',
			array( 'label' => __( 'Digital Scanline Texture', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' )
		);
		$this->add_control(
			'enable_particles',
			array( 'label' => __( 'CTA Particle Burst', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' )
		);
		$this->add_control(
			'enable_magnetic',
			array( 'label' => __( 'Magnetic CTA Buttons', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' )
		);
		$this->add_control(
			'enable_scramble',
			array( 'label' => __( 'Heading Scramble Entrance', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' )
		);

		$this->add_control(
			'show_live_signal',
			array( 'label' => __( 'Show "Live Signal" Badge', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' )
		);
		$this->add_control(
			'live_signal_text',
			array( 'label' => __( 'Live Signal Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'LIVE EXPERIENCE', 'condition' => array( 'show_live_signal' => 'yes' ) )
		);

		$this->add_control( 'show_thumbnails', array( 'label' => __( 'Show Thumbnail Nav', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_arrows', array( 'label' => __( 'Show Arrows', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_progress_ring', array( 'label' => __( 'Show Autoplay Progress Ring', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );

		$this->end_controls_section();
	}

	/**
	 * ---------------------------------------------------------------
	 * STYLE TAB — colours the accent (active thumbnail, buttons, etc.)
	 * ---------------------------------------------------------------
	 */
	private function register_style_controls() {
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Slider Style', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'accent_color',
			array(
				'label'     => __( 'Accent Colour (active thumbnail, cursor)', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#a47cb6',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-fgs' => '--mwsb-fgs-accent: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .mwsb-fgs__heading',
				'label'    => __( 'Heading Typography', 'mw-showband' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'subheading_typography',
				'selector' => '{{WRAPPER}} .mwsb-fgs__typewriter-text',
				'label'    => __( 'Sub-heading Typography', 'mw-showband' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$slides = ! empty( $s['slides'] ) ? $s['slides'] : array();
		$uid = 'mwsb-fgs-' . $this->get_id();

		if ( empty( $slides ) ) {
			return;
		}
		?>
		<section
			class="mwsb-widget mwsb-section mwsb-fgs"
			id="<?php echo esc_attr( $uid ); ?>"
			data-mwsb-fluid-glitch-slider
			data-autoplay="<?php echo esc_attr( 'yes' === $s['autoplay'] ? '1' : '0' ); ?>"
			data-autoplay-delay="<?php echo esc_attr( $s['autoplay_delay'] ); ?>"
			data-pause-on-hover="<?php echo esc_attr( 'yes' === $s['pause_on_hover'] ? '1' : '0' ); ?>"
			data-typing-speed="<?php echo esc_attr( $s['typing_speed'] ); ?>"
			data-enable-glitch="<?php echo esc_attr( 'yes' === $s['enable_glitch'] ? '1' : '0' ); ?>"
			data-enable-fluid="<?php echo esc_attr( 'yes' === $s['enable_fluid'] ? '1' : '0' ); ?>"
			data-disable-fluid-mobile="<?php echo esc_attr( 'yes' === $s['disable_fluid_mobile'] ? '1' : '0' ); ?>"
			data-enable-parallax="<?php echo esc_attr( 'yes' === $s['enable_parallax'] ? '1' : '0' ); ?>"
			data-enable-spotlight="<?php echo esc_attr( 'yes' === $s['enable_spotlight'] ? '1' : '0' ); ?>"
			data-enable-waveform="<?php echo esc_attr( 'yes' === $s['enable_waveform'] ? '1' : '0' ); ?>"
			data-enable-particles="<?php echo esc_attr( 'yes' === $s['enable_particles'] ? '1' : '0' ); ?>"
			data-enable-magnetic="<?php echo esc_attr( 'yes' === $s['enable_magnetic'] ? '1' : '0' ); ?>"
			data-enable-scramble="<?php echo esc_attr( 'yes' === $s['enable_scramble'] ? '1' : '0' ); ?>"
		>
			<div class="mwsb-fgs__atmosphere" aria-hidden="true"></div>
			<div class="mwsb-fgs__spotlights" aria-hidden="true">
				<span class="mwsb-fgs__spotlight mwsb-fgs__spotlight--a"></span>
				<span class="mwsb-fgs__spotlight mwsb-fgs__spotlight--b"></span>
			</div>
			<?php if ( 'yes' === $s['enable_scanline'] ) : ?>
				<div class="mwsb-fgs__scanline" aria-hidden="true"></div>
			<?php endif; ?>

			<div class="mwsb-fgs__swiper swiper">
				<div class="swiper-wrapper">
					<?php foreach ( $slides as $i => $slide ) :
						$is_video = 'video' === $slide['background_type'] && ! empty( $slide['background_video']['url'] );
						$bg       = ! empty( $slide['background_image']['url'] ) ? $slide['background_image']['url'] : '';
						$poster   = ! empty( $slide['video_poster']['url'] ) ? $slide['video_poster']['url'] : $bg;
						?>
						<div
							class="swiper-slide mwsb-fgs__slide"
							data-fluid-color-a="<?php echo esc_attr( $slide['fluid_color_1'] ); ?>"
							data-fluid-color-b="<?php echo esc_attr( $slide['fluid_color_2'] ); ?>"
							data-fluid-color-c="<?php echo esc_attr( $slide['fluid_color_3'] ); ?>"
							data-accent-color="<?php echo esc_attr( $slide['accent_color'] ); ?>"
							data-ken-burns="<?php echo esc_attr( $slide['ken_burns'] ); ?>"
						>
							<div class="mwsb-fgs__bg-layer">
								<?php if ( $is_video ) : ?>
									<video
										class="mwsb-fgs__bg mwsb-fgs__bg-video"
										<?php echo $poster ? 'poster="' . esc_url( $poster ) . '"' : ''; ?>
										<?php echo 'yes' === $s['video_autoplay'] ? 'autoplay' : ''; ?>
										<?php echo 'yes' === $s['video_muted'] ? 'muted' : ''; ?>
										<?php echo 'yes' === $s['video_loop'] ? 'loop' : ''; ?>
										<?php echo 'yes' === $s['video_controls'] ? 'controls' : ''; ?>
										playsinline
										preload="metadata"
									>
										<source src="<?php echo esc_url( $slide['background_video']['url'] ); ?>" type="video/mp4" />
									</video>
								<?php else : ?>
									<div class="mwsb-fgs__bg mwsb-fgs__kb-<?php echo esc_attr( $slide['ken_burns'] ); ?>" style="<?php echo $bg ? 'background-image:url(' . esc_url( $bg ) . ');' : ''; ?>"></div>
								<?php endif; ?>
							</div>
							<div class="mwsb-fgs__scrim"></div>

							<?php if ( 'yes' === $s['show_live_signal'] ) : ?>
								<div class="mwsb-fgs__live-signal">
									<span class="mwsb-fgs__live-dot"></span>
									<?php echo esc_html( $s['live_signal_text'] ); ?>
								</div>
							<?php endif; ?>

							<?php if ( 'yes' === $s['enable_waveform'] ) : ?>
								<div class="mwsb-fgs__waveform" aria-hidden="true"></div>
							<?php endif; ?>

							<div class="mwsb-fgs__content">
								<span class="mwsb-fgs__eyebrow"><?php echo esc_html( $slide['eyebrow'] ); ?></span>
								<h1 class="mwsb-fgs__heading mwsb-gradient-text"><?php echo esc_html( $slide['heading'] ); ?></h1>

								<div class="mwsb-fgs__typewriter-box">
									<p class="mwsb-fgs__typewriter-text" data-full-text="<?php echo esc_attr( $slide['subheading'] ); ?>"></p>
								</div>

								<div class="mwsb-fgs__actions">
									<a href="<?php echo esc_url( $slide['button_link']['url'] ); ?>" class="mwsb-fgs__btn mwsb-fgs__btn--primary"><?php echo esc_html( $slide['button_text'] ); ?></a>
									<?php if ( ! empty( $slide['button2_text'] ) ) : ?>
										<a href="<?php echo esc_url( $slide['button2_link']['url'] ); ?>" class="mwsb-fgs__btn mwsb-fgs__btn--ghost"><?php echo esc_html( $slide['button2_text'] ); ?></a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<canvas class="mwsb-fgs__glitch-canvas" aria-hidden="true"></canvas>
			<canvas class="mwsb-fgs__fluid-canvas" aria-hidden="true"></canvas>

			<div class="mwsb-fgs__controls">
				<?php if ( 'yes' === $s['show_progress_ring'] ) : ?>
					<svg class="mwsb-fgs__progress" viewBox="0 0 44 44" aria-hidden="true">
						<circle class="mwsb-fgs__progress-track" cx="22" cy="22" r="20"></circle>
						<circle class="mwsb-fgs__progress-fill" cx="22" cy="22" r="20"></circle>
					</svg>
				<?php endif; ?>
				<?php if ( 'yes' === $s['show_arrows'] ) : ?>
					<button type="button" class="mwsb-fgs__arrow mwsb-fgs__arrow--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'mw-showband' ); ?>">&#10094;</button>
					<button type="button" class="mwsb-fgs__arrow mwsb-fgs__arrow--next" aria-label="<?php esc_attr_e( 'Next slide', 'mw-showband' ); ?>">&#10095;</button>
				<?php endif; ?>
			</div>

			<?php if ( 'yes' === $s['show_thumbnails'] ) : ?>
				<div class="mwsb-fgs__thumbs">
					<?php foreach ( $slides as $i => $slide ) :
						$thumb = ! empty( $slide['thumbnail_image']['url'] ) ? $slide['thumbnail_image']['url'] : ( ! empty( $slide['background_image']['url'] ) ? $slide['background_image']['url'] : '' );
						?>
						<button type="button" class="mwsb-fgs__thumb <?php echo 0 === $i ? 'is-active' : ''; ?>" data-slide-index="<?php echo esc_attr( $i ); ?>">
							<?php if ( $thumb ) : ?>
								<img src="<?php echo esc_url( $thumb ); ?>" alt="" />
							<?php endif; ?>
							<span class="mwsb-fgs__thumb-num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
							<span class="mwsb-fgs__thumb-title"><?php echo esc_html( $slide['nav_title'] ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>
		<?php
	}
}
