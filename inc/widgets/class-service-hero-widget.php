<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

/**
 * MW Showband — "Services" page hero: telemetry status row, headline (with
 * a gradient accent line), description + CTA, and a bottom HUD info bar.
 * Author: Aqib Dev
 */
class MWSB_Service_Hero_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-service-hero';
	}

	public function get_title() {
		return __( 'MW Service Hero', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-header';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	protected function register_controls() {

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'bg_section',
			array(
				'label' => __( 'Background', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'background_image', array( 'label' => __( 'Background Image', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => Utils::get_placeholder_image_src() ) ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'status_section',
			array(
				'label' => __( 'Status Row', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'show_status_row', array( 'label' => __( 'Show Status Row', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'badge_left_text', array( 'label' => __( 'Left Badge Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'SERVICES / MW SHOWBAND', 'condition' => array( 'show_status_row' => 'yes' ) ) );
		$this->add_control( 'badge_meta_text', array( 'label' => __( 'Left Badge Meta', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '[01 / 03 · CORE ARCHITECTURE]', 'condition' => array( 'show_status_row' => 'yes' ) ) );
		$this->add_control(
			'status_icon',
			array(
				'label'     => __( 'Status Icon', 'mw-showband' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array( 'value' => 'fas fa-wave-square', 'library' => 'fa-solid' ),
				'condition' => array( 'show_status_row' => 'yes' ),
			)
		);
		$this->add_control( 'status_text', array( 'label' => __( 'Status Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '[AUDIO ENGINE: ACTIVE]', 'condition' => array( 'show_status_row' => 'yes' ) ) );
		$this->add_control( 'status_latency_text', array( 'label' => __( 'Latency Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'LATENCY: 0.12ms', 'condition' => array( 'show_status_row' => 'yes' ) ) );
		$this->add_control( 'status_vip_text', array( 'label' => __( 'VIP Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'VIP CALIBRATION: ON', 'condition' => array( 'show_status_row' => 'yes' ) ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'headline_section',
			array(
				'label' => __( 'Headline', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'kicker_text', array( 'label' => __( 'Kicker Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'GLOBAL CONCERT ARCHITECTURE · 2050 HORIZON' ) );
		$this->add_control( 'heading_line1', array( 'label' => __( 'Heading Line 1', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'BUILT FOR' ) );
		$this->add_control( 'heading_line2', array( 'label' => __( 'Heading Line 2 (gradient)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'THE MOMENT.' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'From intimate celebrations to high-impact international events, we build the entertainment around the room. Precision acoustics, visceral brass, and stadium-caliber showmanship.' ) );
		$this->add_control( 'button_text', array( 'label' => __( 'Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'EXPLORE SERVICES' ) );
		$this->add_control( 'button_link', array( 'label' => __( 'Button Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#services-grid' ) ) );
		$this->add_control(
			'badge_icon',
			array(
				'label'   => __( 'Note Icon', 'mw-showband' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array( 'value' => 'fas fa-shield-alt', 'library' => 'fa-solid' ),
			)
		);
		$this->add_control( 'badge_text', array( 'label' => __( 'Note Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CURATED FOR LUXURY GALAS & PRIVATE ISLANDS' ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'hud_section',
			array(
				'label' => __( 'Bottom HUD Bar', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'label', array( 'label' => __( 'Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'ROSTER:' ) );
		$repeater->add_control( 'value', array( 'label' => __( 'Value', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '14–32 PERFORMERS' ) );

		$this->add_control(
			'hud_items',
			array(
				'label'       => __( 'HUD Items', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'label' => 'ROSTER:', 'value' => '14–32 PERFORMERS' ),
					array( 'label' => 'FORMAT:', 'value' => 'HYBRID LIVE + DIGITAL' ),
					array( 'label' => 'DISPATCH:', 'value' => 'WORLDWIDE' ),
				),
				'title_field' => '{{{ label }}}',
			)
		);

		$this->add_control( 'scroll_text', array( 'label' => __( 'Scroll Cue Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'SCROLL TO ARCHITECTURE' ) );
		$this->add_control( 'scroll_link', array( 'label' => __( 'Scroll Cue Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#services-grid' ) ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->register_section_style_controls();
		$this->register_hero_layout_controls( '.mwsb-service-hero' );
		$this->register_bg_overlay_controls( '.mwsb-service-hero__bg' );
		$this->register_text_style_controls( array( 'eyebrow' => '', 'heading' => '.mwsb-service-hero__heading', 'desc' => '.mwsb-service-hero__desc', 'flow' => '.mwsb-service-hero__headline-block', 'content_gap' => '.mwsb-service-hero__content' ) );

		$this->start_controls_section(
			'mwsb_sh_overlay_style',
			array(
				'label' => __( 'Default Gradient Overlay', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_opacity',
			array(
				'label'     => __( 'Darkness', 'mw-showband' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
				'default'   => array( 'unit' => 'px', 'size' => 65 ),
				'selectors' => array(
					'{{WRAPPER}} .mwsb-service-hero__scrim' => 'opacity: calc( {{SIZE}} / 100 );',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_sh_heading_style',
			array(
				'label' => __( 'Heading', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'kicker_color',
			array(
				'label'       => __( 'Kicker Color', 'mw-showband' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'placeholder' => 'var(--mwsb-primary)',
				'selectors'   => array(
					'{{WRAPPER}} .mwsb-service-hero__kicker' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .mwsb-service-hero__heading',
				'label'    => __( 'Heading Typography', 'mw-showband' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'selector' => '{{WRAPPER}} .mwsb-service-hero__desc',
				'label'    => __( 'Description Typography', 'mw-showband' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_sh_button_style',
			array(
				'label' => __( 'Button & HUD', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sh_button_typography',
				'label'    => __( 'Button Typography', 'mw-showband' ),
				'selector' => '{{WRAPPER}} .mwsb-service-hero__actions .mwsb-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'sh_button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mwsb-service-hero__actions .mwsb-btn',
			)
		);

		$this->add_control(
			'sh_hud_value_color',
			array(
				'label'     => __( 'HUD Value Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-white)',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-service-hero__hud-item span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s  = $this->get_settings_for_display();
		$bg = ! empty( $s['background_image']['url'] ) ? $s['background_image']['url'] : '';
		?>
		<section class="mwsb-widget mwsb-section mwsb-service-hero">
			<div class="mwsb-service-hero__bg" style="<?php echo $bg ? 'background-image:url(' . esc_url( $bg ) . ');' : ''; ?>"></div>
			<div class="mwsb-service-hero__scrim"></div>
			<div class="mwsb-bg-overlay"></div>
			<div class="mwsb-service-hero__bloom mwsb-service-hero__bloom--a"></div>
			<div class="mwsb-service-hero__bloom mwsb-service-hero__bloom--b"></div>

			<div class="mwsb-container mwsb-service-hero__content">

				<?php if ( 'yes' === $s['show_status_row'] ) : ?>
					<div class="mwsb-service-hero__status-row">
						<div class="mwsb-service-hero__badge">
							<span class="mwsb-pulse-dot"></span>
							<span><?php echo esc_html( $s['badge_left_text'] ); ?></span>
							<?php if ( ! empty( $s['badge_meta_text'] ) ) : ?>
								<span class="mwsb-service-hero__badge-sep">|</span>
								<span class="mwsb-service-hero__badge-meta"><?php echo esc_html( $s['badge_meta_text'] ); ?></span>
							<?php endif; ?>
						</div>
						<div class="mwsb-service-hero__status">
							<span class="mwsb-service-hero__status-item mwsb-service-hero__status-item--icon">
								<?php Icons_Manager::render_icon( $s['status_icon'], array( 'aria-hidden' => 'true' ) ); ?>
								<?php echo esc_html( $s['status_text'] ); ?>
							</span>
							<span class="mwsb-service-hero__status-item"><?php echo esc_html( $s['status_latency_text'] ); ?></span>
							<span class="mwsb-service-hero__status-item mwsb-service-hero__status-item--vip"><?php echo esc_html( $s['status_vip_text'] ); ?></span>
						</div>
					</div>
				<?php endif; ?>

				<div class="mwsb-service-hero__headline-block">
					<div class="mwsb-service-hero__kicker">
						<span class="mwsb-service-hero__kicker-line"></span>
						<span><?php echo esc_html( $s['kicker_text'] ); ?></span>
					</div>
					<h1 class="mwsb-heading-display mwsb-service-hero__heading">
						<?php echo esc_html( $s['heading_line1'] ); ?><br />
						<span class="mwsb-gradient-text"><?php echo esc_html( $s['heading_line2'] ); ?></span>
					</h1>
					<?php if ( ! empty( $s['description'] ) ) : ?>
						<p class="mwsb-service-hero__desc"><?php echo esc_html( $s['description'] ); ?></p>
					<?php endif; ?>

					<div class="mwsb-service-hero__actions">
						<a href="<?php echo esc_url( $s['button_link']['url'] ); ?>" class="mwsb-btn mwsb-btn--primary"><?php echo esc_html( $s['button_text'] ); ?></a>
						<?php if ( ! empty( $s['badge_text'] ) ) : ?>
							<span class="mwsb-service-hero__note">
								<?php Icons_Manager::render_icon( $s['badge_icon'], array( 'aria-hidden' => 'true' ) ); ?>
								<?php echo esc_html( $s['badge_text'] ); ?>
							</span>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( ! empty( $s['hud_items'] ) ) : ?>
					<div class="mwsb-service-hero__hud">
						<div class="mwsb-service-hero__hud-items">
							<?php foreach ( $s['hud_items'] as $item ) : ?>
								<div class="mwsb-service-hero__hud-item"><span><?php echo esc_html( $item['label'] ); ?></span> <?php echo esc_html( $item['value'] ); ?></div>
							<?php endforeach; ?>
						</div>
						<?php if ( ! empty( $s['scroll_text'] ) ) : ?>
							<a href="<?php echo esc_url( $s['scroll_link']['url'] ); ?>" class="mwsb-service-hero__scroll">
								<span><?php echo esc_html( $s['scroll_text'] ); ?></span>
								<span class="mwsb-service-hero__scroll-arrow">&#8595;</span>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</div>
		</section>
		<?php
	}
}
