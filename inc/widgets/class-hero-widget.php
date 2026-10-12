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
 * MW Showband — Cinematic Hero Slider widget (multi-slide, Ken Burns zoom,
 * fade/slide transitions, dot + arrow navigation — built in-house so no
 * third-party slider plugin, like Slider Revolution, is required).
 * Author: Aqib Dev
 */
class MWSB_Hero_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-hero';
	}

	public function get_title() {
		return __( 'MW Hero Slider', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-slider-full-screen';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'slides_section',
			array(
				'label' => __( 'Slides', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'background_image', array( 'label' => __( 'Background Image', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => Utils::get_placeholder_image_src() ) ) );
		$repeater->add_control( 'badge_text', array( 'label' => __( 'Live Badge Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'LIVE AUDIO MATRIX ONLINE' ) );
		$repeater->add_control( 'headline_white', array( 'label' => __( 'Headline (White Line)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => "WE DON'T JUST PLAY." ) );
		$repeater->add_control( 'headline_gradient', array( 'label' => __( 'Headline (Gradient Line)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WE IGNITE THE ROOM.' ) );
		$repeater->add_control( 'subheadline', array( 'label' => __( 'Subheadline', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '10–14 PIECE LIVE SHOWBAND • WORLDWIDE TOUR • BESPOKE LUXURY' ) );

		$this->add_control(
			'slides',
			array(
				'label'       => __( 'Slides', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'badge_text'        => 'LIVE AUDIO MATRIX ONLINE',
						'headline_white'    => "WE DON'T JUST PLAY.",
						'headline_gradient' => 'WE IGNITE THE ROOM.',
						'subheadline'       => '10–14 PIECE LIVE SHOWBAND • WORLDWIDE TOUR • BESPOKE LUXURY',
					),
					array(
						'badge_text'        => '800+ SONGS ON CALL',
						'headline_white'    => 'YOUR NIGHT.',
						'headline_gradient' => 'YOUR SOUND.',
						'subheadline'       => 'FROM CHAMPAGNE JAZZ TO A 14-PIECE PARTY JUGGERNAUT',
					),
					array(
						'badge_text'        => 'GLOBAL TOURING ENSEMBLE',
						'headline_white'    => 'WHEREVER THE PARTY IS.',
						'headline_gradient' => "WE'LL BE THERE.",
						'subheadline'       => 'LONDON • MARBELLA • TEL AVIV • MONACO • DUBAI • NEW YORK',
					),
				),
				'title_field' => '{{{ headline_white }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slider_settings_section',
			array(
				'label' => __( 'Slider Settings', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => __( 'Autoplay', 'mw-showband' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => __( 'Autoplay Speed (ms)', 'mw-showband' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6000,
				'condition' => array( 'autoplay' => 'yes' ),
			)
		);

		$this->add_control(
			'transition_effect',
			array(
				'label'   => __( 'Transition Effect', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => array(
					'fade'  => __( 'Cinematic Fade', 'mw-showband' ),
					'slide' => __( 'Slide', 'mw-showband' ),
				),
			)
		);

		$this->add_control(
			'ken_burns',
			array(
				'label'   => __( 'Ken Burns Zoom Effect', 'mw-showband' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'   => __( 'Show Arrows', 'mw-showband' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_dots',
			array(
				'label'   => __( 'Show Dots', 'mw-showband' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'info_strip_section',
			array(
				'label' => __( 'Proposal Info Strip (optional)', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_info_strip',
			array(
				'label'       => __( 'Show Info Strip', 'mw-showband' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'A glass info bar below the headline for personalized proposals (e.g. client name, venue, date). Leave off for a normal marketing hero.', 'mw-showband' ),
			)
		);

		$info_repeater = new Repeater();
		$info_repeater->add_control( 'icon', array( 'label' => __( 'Icon', 'mw-showband' ), 'type' => Controls_Manager::ICONS, 'default' => array( 'value' => 'fas fa-star', 'library' => 'fa-solid' ) ) );
		$info_repeater->add_control( 'label', array( 'label' => __( 'Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'PREPARED EXCLUSIVELY FOR' ) );
		$info_repeater->add_control( 'value', array( 'label' => __( 'Value', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Paul & Victoria' ) );

		$this->add_control(
			'info_items',
			array(
				'label'       => __( 'Info Items', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $info_repeater->get_controls(),
				'default'     => array(
					array( 'icon' => array( 'value' => 'fas fa-star', 'library' => 'fa-solid' ), 'label' => 'PREPARED EXCLUSIVELY FOR', 'value' => 'Paul & Victoria' ),
					array( 'icon' => array( 'value' => 'fas fa-champagne-glasses', 'library' => 'fa-solid' ), 'label' => 'OCCASION', 'value' => 'Private Celebration Gala' ),
					array( 'icon' => array( 'value' => 'fas fa-location-dot', 'library' => 'fa-solid' ), 'label' => 'SANCTUARY VENUE', 'value' => 'Aynhoe Park, Oxfordshire' ),
					array( 'icon' => array( 'value' => 'fas fa-calendar-check', 'library' => 'fa-solid' ), 'label' => 'RESERVED TIMELINE', 'value' => 'Saturday, 18th July 2026' ),
				),
				'condition'   => array( 'show_info_strip' => 'yes' ),
				'title_field' => '{{{ label }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'actions_section',
			array(
				'label' => __( 'Buttons & Scroll Cue', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'show_actions', array( 'label' => __( 'Show Action Buttons', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => '' ) );
		$this->add_control( 'primary_button_text', array( 'label' => __( 'Primary Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CHECK AVAILABILITY', 'condition' => array( 'show_actions' => 'yes' ) ) );
		$this->add_control( 'primary_button_link', array( 'label' => __( 'Primary Button Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#event-builder' ), 'condition' => array( 'show_actions' => 'yes' ) ) );
		$this->add_control( 'ghost_button_text', array( 'label' => __( 'Secondary Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WATCH THE SHOW', 'condition' => array( 'show_actions' => 'yes' ) ) );
		$this->add_control( 'ghost_button_link', array( 'label' => __( 'Secondary Button Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#media-vault' ), 'condition' => array( 'show_actions' => 'yes' ) ) );
		$this->add_control( 'scroll_text', array( 'label' => __( 'Scroll Cue Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'SCROLL TO EXPERIENCE' ) );

		$this->end_controls_section();

		$this->register_section_style_controls();

		$this->start_controls_section(
			'mwsb_hero_headline_style',
			array(
				'label' => __( 'Headline & Badge', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'badge_heading',
			array(
				'label' => __( 'Live Badge', 'mw-showband' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .mwsb-hero__badge span',
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => __( 'Text Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mwsb-hero__badge span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'headline_heading',
			array(
				'label'     => __( 'Headline', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'headline_typography',
				'selector' => '{{WRAPPER}} .mwsb-hero__headline-line',
			)
		);

		$this->add_control(
			'headline_white_color',
			array(
				'label'     => __( 'White Line Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-white)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-hero__headline-line:not(.mwsb-gradient-text)' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'subheadline_heading',
			array(
				'label'     => __( 'Subheadline', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subheadline_typography',
				'selector' => '{{WRAPPER}} .mwsb-hero__subheadline',
			)
		);

		$this->add_control(
			'subheadline_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-muted)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-hero__subheadline' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_hero_buttons_style',
			array(
				'label'     => __( 'Buttons', 'mw-showband' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_actions' => 'yes' ),
			)
		);

		$this->add_control(
			'hero_primary_btn_heading',
			array(
				'label' => __( 'Primary Button', 'mw-showband' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'hero_primary_btn_typography',
				'selector' => '{{WRAPPER}} .mwsb-hero__actions .mwsb-btn--primary',
			)
		);

		$this->add_control(
			'hero_primary_btn_text_color',
			array(
				'label'     => __( 'Text Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mwsb-hero__actions .mwsb-btn--primary' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'hero_primary_btn_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mwsb-hero__actions .mwsb-btn--primary',
			)
		);

		$this->add_control(
			'hero_ghost_btn_heading',
			array(
				'label'     => __( 'Secondary (Ghost) Button', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'hero_ghost_btn_typography',
				'selector' => '{{WRAPPER}} .mwsb-hero__actions .mwsb-btn--ghost',
			)
		);

		$this->add_control(
			'hero_ghost_btn_text_color',
			array(
				'label'     => __( 'Text Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mwsb-hero__actions .mwsb-btn--ghost' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'hero_ghost_btn_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mwsb-hero__actions .mwsb-btn--ghost',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_hero_overlay_style',
			array(
				'label' => __( 'Default Dark Gradient', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_opacity',
			array(
				'label'     => __( 'Overlay Opacity', 'mw-showband' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array( 'min' => 0, 'max' => 1, 'step' => 0.05 ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mwsb-hero__scrim' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->end_controls_section();

		// Elementor-style colour/gradient overlay + image filters, on top of the default gradient.
		$this->register_bg_overlay_controls( '.mwsb-hero__bg' );
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$slides = ! empty( $s['slides'] ) ? $s['slides'] : array();
		$uid = 'mwsb-hero-' . $this->get_id();
		?>
		<section
			class="mwsb-widget mwsb-section mwsb-hero"
			id="<?php echo esc_attr( $uid ); ?>"
			data-mwsb-hero-slider
			data-autoplay="<?php echo esc_attr( 'yes' === $s['autoplay'] ? '1' : '0' ); ?>"
			data-autoplay-speed="<?php echo esc_attr( $s['autoplay_speed'] ); ?>"
			data-transition="<?php echo esc_attr( $s['transition_effect'] ); ?>"
		>
			<div class="mwsb-hero-slider__track">
				<?php foreach ( $slides as $i => $slide ) :
					$bg = ! empty( $slide['background_image']['url'] ) ? $slide['background_image']['url'] : '';
					?>
					<div class="mwsb-hero-slide <?php echo 0 === $i ? 'is-active' : ''; ?>" data-slide-index="<?php echo esc_attr( $i ); ?>">
						<div class="mwsb-hero__bg <?php echo 'yes' === $s['ken_burns'] ? 'mwsb-hero__bg--kenburns' : ''; ?>" style="<?php echo $bg ? 'background-image:url(' . esc_url( $bg ) . ');' : ''; ?>">
							<div class="mwsb-hero__scrim mwsb-hero__scrim--v"></div>
							<div class="mwsb-hero__scrim mwsb-hero__scrim--h"></div>
							<div class="mwsb-bg-overlay"></div>
							<div class="mwsb-hero__bloom"></div>
						</div>
						<div class="mwsb-hero__beam mwsb-hero__beam--left"></div>
						<div class="mwsb-hero__beam mwsb-hero__beam--right"></div>

						<div class="mwsb-container mwsb-hero__content">
							<div class="mwsb-hero__badge">
								<span class="mwsb-pulse-dot"></span>
								<span><?php echo esc_html( $slide['badge_text'] ); ?></span>
							</div>

							<h1 class="mwsb-hero__headline">
								<span class="mwsb-hero__headline-line"><?php echo esc_html( $slide['headline_white'] ); ?></span>
								<span class="mwsb-hero__headline-line mwsb-gradient-text"><?php echo esc_html( $slide['headline_gradient'] ); ?></span>
							</h1>

							<p class="mwsb-hero__subheadline"><?php echo esc_html( $slide['subheadline'] ); ?></p>

							<?php if ( 'yes' === $s['show_info_strip'] && ! empty( $s['info_items'] ) ) : ?>
								<div class="mwsb-hero__info-strip">
									<?php foreach ( $s['info_items'] as $item ) : ?>
										<div class="mwsb-hero__info-item">
											<span class="mwsb-hero__info-label"><?php echo esc_html( $item['label'] ); ?></span>
											<span class="mwsb-hero__info-value">
												<?php Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true', 'class' => 'mwsb-hero__info-icon' ) ); ?>
												<?php echo esc_html( $item['value'] ); ?>
											</span>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<?php if ( 'yes' === $s['show_actions'] ) : ?>
								<div class="mwsb-hero__actions">
									<a href="<?php echo esc_url( $s['primary_button_link']['url'] ); ?>" class="mwsb-btn mwsb-btn--primary"><?php echo esc_html( $s['primary_button_text'] ); ?></a>
									<a href="<?php echo esc_url( $s['ghost_button_link']['url'] ); ?>" class="mwsb-btn mwsb-btn--ghost">
										<span class="mwsb-icon-play">&#9658;</span>
										<?php echo esc_html( $s['ghost_button_text'] ); ?>
									</a>
								</div>
							<?php endif; ?>

							<div class="mwsb-hero__scroll">
								<span><?php echo esc_html( $s['scroll_text'] ); ?></span>
								<span class="mwsb-hero__scroll-arrow">&#8595;</span>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( 'yes' === $s['show_arrows'] && count( $slides ) > 1 ) : ?>
				<button type="button" class="mwsb-hero-slider__arrow mwsb-hero-slider__arrow--prev" data-mwsb-hero-prev aria-label="<?php esc_attr_e( 'Previous slide', 'mw-showband' ); ?>">&#10094;</button>
				<button type="button" class="mwsb-hero-slider__arrow mwsb-hero-slider__arrow--next" data-mwsb-hero-next aria-label="<?php esc_attr_e( 'Next slide', 'mw-showband' ); ?>">&#10095;</button>
			<?php endif; ?>

			<?php if ( 'yes' === $s['show_dots'] && count( $slides ) > 1 ) : ?>
				<div class="mwsb-hero-slider__dots">
					<?php foreach ( $slides as $i => $slide ) : ?>
						<button type="button" class="mwsb-hero-slider__dot <?php echo 0 === $i ? 'is-active' : ''; ?>" data-mwsb-hero-dot="<?php echo esc_attr( $i ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'mw-showband' ), $i + 1 ) ); ?>"></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>
		<?php
	}
}
