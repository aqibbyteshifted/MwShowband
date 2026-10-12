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

/**
 * MW Showband — Cinematic "About" page hero: telemetry badge row, headline
 * (with a gradient accent line), description, and a row of mini info cards.
 * Author: Aqib Dev
 */
class MWSB_About_Hero_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-about-hero';
	}

	public function get_title() {
		return __( 'MW About Hero', 'mw-showband' );
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
			'telemetry_section',
			array(
				'label' => __( 'Telemetry Bar', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'show_telemetry', array( 'label' => __( 'Show Telemetry Bar', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'telemetry_left', array( 'label' => __( 'Left Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '01 / 05 // ARCHIVE: ABOUT MW SHOWBAND // EST. SHOW BUSINESS // WORLDWIDE DEPLOYMENT', 'condition' => array( 'show_telemetry' => 'yes' ) ) );
		$this->add_control( 'telemetry_right', array( 'label' => __( 'Right Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'AUDIO ENGINE: ACTIVE', 'condition' => array( 'show_telemetry' => 'yes' ) ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'headline_section',
			array(
				'label' => __( 'Headline', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'kicker_icon',
			array(
				'label'   => __( 'Kicker Icon', 'mw-showband' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array( 'value' => 'fas fa-wave-square', 'library' => 'fa-solid' ),
			)
		);
		$this->add_control( 'kicker_text', array( 'label' => __( 'Kicker Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'THE ARCHITECTS OF MODERN LUXURY LIVE SOUND' ) );
		$this->add_control( 'heading_line1', array( 'label' => __( 'Heading Line 1', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'THE PEOPLE BEHIND' ) );
		$this->add_control( 'heading_line2', array( 'label' => __( 'Heading Line 2 (gradient)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'THE EXPERIENCE.' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Two decades of international live entertainment, engineered for unforgettable nights. Blending stadium-grade touring production with bespoke private gala intimacy.' ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'cards_section',
			array(
				'label' => __( 'Info Cards', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'Icon', 'mw-showband' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array( 'value' => 'fas fa-users', 'library' => 'fa-solid' ),
			)
		);
		$repeater->add_control( 'label', array( 'label' => __( 'Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '[LIVE ENSEMBLE: 14-PIECE]' ) );
		$repeater->add_control( 'sublabel', array( 'label' => __( 'Sublabel', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Horns, 4 Vocal Leads & Stadium Rhythm' ) );

		$this->add_control(
			'cards',
			array(
				'label'       => __( 'Cards', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'icon' => array( 'value' => 'fas fa-users', 'library' => 'fa-solid' ), 'label' => '[LIVE ENSEMBLE: 14-PIECE]', 'sublabel' => 'Horns, 4 Vocal Leads & Stadium Rhythm' ),
					array( 'icon' => array( 'value' => 'fas fa-globe', 'library' => 'fa-solid' ), 'label' => '[WORLDWIDE: 40+ COUNTRIES]', 'sublabel' => 'Private Air Logistics & Global Venues' ),
					array( 'icon' => array( 'value' => 'fas fa-arrow-down', 'library' => 'fa-solid' ), 'label' => '[SCROLL TO DISCOVER]', 'sublabel' => 'Narrative Chronology' ),
				),
				'title_field' => '{{{ label }}}',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->register_section_style_controls();

		$this->start_controls_section(
			'mwsb_ah_overlay_style',
			array(
				'label' => __( 'Default Gradient Overlay', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_opacity',
			array(
				'label'     => __( 'Overlay Darkness', 'mw-showband' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
				'default'   => array( 'unit' => 'px', 'size' => 80 ),
				'selectors' => array(
					'{{WRAPPER}} .mwsb-about-hero__scrim' => 'opacity: calc( {{SIZE}} / 100 );',
				),
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'       => __( 'Overlay Color (flat)', 'mw-showband' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Optional. Replaces the default dark gradient with one solid colour; Overlay Darkness still controls how strong it is.', 'mw-showband' ),
				'selectors'   => array(
					'{{WRAPPER}} .mwsb-about-hero__scrim' => 'background: {{VALUE}};',
				),
			)
		);

		// The photo itself is dimmed by this filter, independent of the overlay above — it used to be
		// hard-coded at 55%, so moving "Overlay Darkness" to 0 still left the image dark and the control
		// looked broken. The default (55) matches the old look exactly.
		$this->add_control(
			'image_brightness',
			array(
				'label'     => __( 'Image Brightness', 'mw-showband' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 10, 'max' => 150 ) ),
				'default'   => array( 'unit' => 'px', 'size' => 55 ),
				'selectors' => array(
					'{{WRAPPER}} .mwsb-about-hero__bg' => 'filter: brightness( calc( {{SIZE}} / 100 ) ) contrast(1.1);',
				),
			)
		);

		$this->end_controls_section();

		$this->register_hero_layout_controls( '.mwsb-about-hero' );
		$this->register_bg_overlay_controls( '.mwsb-about-hero__bg' );

		// Heading / description colours + gaps (shared helper). The kicker already has its own colour
		// control and all three text blocks already have typography controls below, so only the missing
		// parts are added here.
		$this->register_text_style_controls(
			array(
				'eyebrow'       => '',
				'heading'       => '.mwsb-about-hero__heading',
				'desc'          => '.mwsb-about-hero__desc',
				'flow'          => '.mwsb-about-hero__headline-block',
				'content_gap'   => '.mwsb-about-hero__content',
				'typography'    => false,
			)
		);

		$this->start_controls_section(
			'mwsb_ah_heading_style',
			array(
				'label' => __( 'Heading', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'kicker_color',
			array(
				'label'     => __( 'Kicker Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'placeholder' => 'var(--mwsb-primary)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-about-hero__kicker' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .mwsb-about-hero__heading',
				'label'    => __( 'Heading Typography', 'mw-showband' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'selector' => '{{WRAPPER}} .mwsb-about-hero__desc',
				'label'    => __( 'Description Typography', 'mw-showband' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_ah_cards_style',
			array(
				'label' => __( 'Info Cards', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'cards_icon_color',
			array(
				'label'       => __( 'Icon Color', 'mw-showband' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'placeholder' => 'var(--mwsb-primary)',
				'selectors'   => array(
					'{{WRAPPER}} .mwsb-about-hero__card-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'card_label_typography',
				'label'     => __( 'Card Label Typography', 'mw-showband' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .mwsb-about-hero__card-label',
			)
		);

		$this->add_control( 'card_label_color', array( 'label' => __( 'Card Label Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-about-hero__card-label' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'card_sublabel_color', array( 'label' => __( 'Card Sublabel Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-about-hero__card-sublabel' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'card_bg_color', array( 'label' => __( 'Card Background', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-about-hero__card' => 'background-color: {{VALUE}};' ) ) );
		$this->add_responsive_control(
			'cards_gap',
			array(
				'label'      => __( 'Gap Between Cards', 'mw-showband' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 80 ) ),
				'selectors'  => array( '{{WRAPPER}} .mwsb-about-hero__cards' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_ah_telemetry_style',
			array(
				'label' => __( 'Telemetry Bar', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control( 'telemetry_left_color', array( 'label' => __( 'Left Text Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-about-hero__telemetry-badge' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'telemetry_left_bg', array( 'label' => __( 'Left Badge Background', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-about-hero__telemetry-badge' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'telemetry_right_color', array( 'label' => __( 'Right Text Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-about-hero__telemetry-right' => 'color: {{VALUE}};' ) ) );

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_ah_gradient_style',
			array(
				'label' => __( 'Heading Gradient Line', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'heading_gradient',
				'label'    => __( 'Gradient Colors', 'mw-showband' ),
				'types'    => array( 'gradient' ),
				'selector' => '{{WRAPPER}} .mwsb-about-hero__heading .mwsb-gradient-text',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s  = $this->get_settings_for_display();
		$bg = ! empty( $s['background_image']['url'] ) ? $s['background_image']['url'] : '';
		?>
		<section class="mwsb-widget mwsb-section mwsb-about-hero">
			<div class="mwsb-about-hero__bg" style="<?php echo $bg ? 'background-image:url(' . esc_url( $bg ) . ');' : ''; ?>"></div>
			<div class="mwsb-about-hero__scrim"></div>
			<div class="mwsb-bg-overlay"></div>
			<div class="mwsb-about-hero__bloom mwsb-about-hero__bloom--a"></div>
			<div class="mwsb-about-hero__bloom mwsb-about-hero__bloom--b"></div>

			<div class="mwsb-container mwsb-about-hero__content">

				<?php if ( 'yes' === $s['show_telemetry'] ) : ?>
					<div class="mwsb-about-hero__telemetry">
						<div class="mwsb-about-hero__telemetry-badge">
							<span class="mwsb-pulse-dot"></span>
							<span><?php echo esc_html( $s['telemetry_left'] ); ?></span>
						</div>
						<div class="mwsb-about-hero__telemetry-right"><?php echo esc_html( $s['telemetry_right'] ); ?></div>
					</div>
				<?php endif; ?>

				<div class="mwsb-about-hero__headline-block">
					<div class="mwsb-about-hero__kicker">
						<?php Icons_Manager::render_icon( $s['kicker_icon'], array( 'aria-hidden' => 'true' ) ); ?>
						<span><?php echo esc_html( $s['kicker_text'] ); ?></span>
					</div>
					<h1 class="mwsb-heading-display mwsb-about-hero__heading">
						<?php echo esc_html( $s['heading_line1'] ); ?><br />
						<span class="mwsb-gradient-text"><?php echo esc_html( $s['heading_line2'] ); ?></span>
					</h1>
					<?php if ( ! empty( $s['description'] ) ) : ?>
						<p class="mwsb-about-hero__desc"><?php echo esc_html( $s['description'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $s['cards'] ) ) : ?>
					<div class="mwsb-about-hero__cards">
						<?php foreach ( $s['cards'] as $card ) : ?>
							<div class="mwsb-about-hero__card">
								<span class="mwsb-about-hero__card-icon"><?php Icons_Manager::render_icon( $card['icon'], array( 'aria-hidden' => 'true' ) ); ?></span>
								<div>
									<div class="mwsb-about-hero__card-label"><?php echo esc_html( $card['label'] ); ?></div>
									<div class="mwsb-about-hero__card-sublabel"><?php echo esc_html( $card['sublabel'] ); ?></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

			</div>
		</section>
		<?php
	}
}
