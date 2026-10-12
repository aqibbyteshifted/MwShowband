<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

/**
 * MW Showband — "Real People. Real Nights." testimonials.
 * Author: Aqib Dev
 */
class MWSB_Testimonials_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-testimonials';
	}

	public function get_title() {
		return __( 'MW Testimonials', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Header', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'VERIFIED CLIENTS' ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'REAL PEOPLE. REAL NIGHTS.' ) );

		$this->end_controls_section();

		$this->start_controls_section(
			'featured_section',
			array(
				'label' => __( 'Featured Quote', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'featured_quote', array( 'label' => __( 'Quote', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'THE DANCE FLOOR WAS PACKED THE ENTIRE NIGHT. GUESTS FLEW IN FROM ACROSS THE WORLD AND COULD NOT STOP TALKING ABOUT THE BRASS SECTION.' ) );
		$this->add_control( 'featured_initials', array( 'label' => __( 'Avatar Initials', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'AS' ) );
		$this->add_control( 'featured_name', array( 'label' => __( 'Name', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Alexandra & David Stern' ) );
		$this->add_control( 'featured_meta', array( 'label' => __( 'Meta', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Destination Wedding • Villa Balbiano, Lake Como' ) );

		$this->end_controls_section();

		$this->start_controls_section(
			'reviews_section',
			array(
				'label' => __( 'Review Cards', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'quote', array( 'label' => __( 'Quote', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'MW Showband turned our corporate gala into an international stadium arena.' ) );
		$repeater->add_control( 'name', array( 'label' => __( 'Name', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Julian Vance-Carter' ) );
		$repeater->add_control( 'meta', array( 'label' => __( 'Meta', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Global Gala Chair • The Grosvenor House, London' ) );

		$this->add_control(
			'reviews',
			array(
				'label'       => __( 'Reviews', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'quote' => 'MW Showband turned our corporate gala into an international stadium arena. Their precision, song selection, and crowd interaction made our executives stay past 3am.', 'name' => 'Julian Vance-Carter', 'meta' => 'Global Gala Chair • The Grosvenor House, London' ),
					array( 'quote' => "They didn't just sing songs; they constructed an unforgettable sensory wave. The Israeli Simcha set was blistering and spiritual. Best band in Europe.", 'name' => 'Tal & Eytan Ronen', 'meta' => 'Private Celebration • Marbella Club Resort' ),
				),
				'title_field' => '{{{ name }}}',
			)
		);

		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'head' => '.mwsb-testimonials__head', 'content_gap' => '.mwsb-reviews-grid' ) );

		$this->start_controls_section(
			'mwsb_testimonials_style',
			array(
				'label' => __( 'Text Styles', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'testimonials_eyebrow_color',
			array(
				'label'     => __( 'Eyebrow Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-secondary)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-testimonials__eyebrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'stars_color',
			array(
				'label'     => __( 'Star Rating Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-stage-gold)',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-stars' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'featured_quote_typography',
				'label'     => __( 'Featured Quote Typography', 'mw-showband' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .mwsb-testimonial-featured__text',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'review_quote_typography',
				'label'     => __( 'Review Card Quote Typography', 'mw-showband' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .mwsb-review-card__quote',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="mwsb-widget mwsb-section mwsb-testimonials">
			<div class="mwsb-container">
				<div class="mwsb-testimonials__head">
					<span class="mwsb-eyebrow mwsb-testimonials__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
					<h2 class="mwsb-heading-display mwsb-heading-display--xl"><?php echo esc_html( $s['title'] ); ?></h2>
				</div>

				<div class="mwsb-testimonial-featured">
					<span class="mwsb-testimonial-featured__quote-icon">&#8220;</span>
					<div class="mwsb-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
					<p class="mwsb-testimonial-featured__text">"<?php echo esc_html( $s['featured_quote'] ); ?>"</p>
					<div class="mwsb-testimonial-featured__author">
						<span class="mwsb-avatar"><?php echo esc_html( $s['featured_initials'] ); ?></span>
						<div>
							<div class="mwsb-testimonial-featured__name"><?php echo esc_html( $s['featured_name'] ); ?></div>
							<div class="mwsb-testimonial-featured__meta"><?php echo esc_html( $s['featured_meta'] ); ?></div>
						</div>
					</div>
				</div>

				<div class="mwsb-reviews-grid">
					<?php foreach ( $s['reviews'] as $r ) : ?>
						<div class="mwsb-review-card">
							<div>
								<div class="mwsb-stars mwsb-stars--sm">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
								<p class="mwsb-review-card__quote">"<?php echo esc_html( $r['quote'] ); ?>"</p>
							</div>
							<div>
								<div class="mwsb-review-card__name"><?php echo esc_html( $r['name'] ); ?></div>
								<div class="mwsb-review-card__meta"><?php echo esc_html( $r['meta'] ); ?></div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
