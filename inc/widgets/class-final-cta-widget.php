<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

/**
 * MW Showband — final crescendo CTA + contact hub cards.
 * Author: Aqib Dev
 */
class MWSB_Final_Cta_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-final-cta';
	}

	public function get_title() {
		return __( 'MW Final CTA', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'LOCK IN YOUR 2025/2026 DATE' ) );
		$this->add_control( 'title_line1', array( 'label' => __( 'Title Line 1', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'YOUR EVENT.' ) );
		$this->add_control( 'title_line2', array( 'label' => __( 'Title Line 2 (gradient)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'OUR STAGE.' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Ready to create an unforgettable night? Contact our creative directors directly to reserve dates and discuss custom show engineering.' ) );
		$this->add_control( 'button_text', array( 'label' => __( 'Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CHECK AVAILABILITY' ) );
		$this->add_control( 'button_link', array( 'label' => __( 'Button Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => 'mailto:info@mw-showband.com' ) ) );

		$this->end_controls_section();

		$this->start_controls_section(
			'contact_section',
			array(
				'label' => __( 'Contact Cards', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'icon_char', array( 'label' => __( 'Icon', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '&#9742;' ) );
		$repeater->add_control( 'label', array( 'label' => __( 'Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'DIRECT PHONE' ) );
		$repeater->add_control( 'value', array( 'label' => __( 'Value', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '+44 7707 159384' ) );
		$repeater->add_control( 'link', array( 'label' => __( 'Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => 'tel:+447707159384' ) ) );

		$this->add_control(
			'contacts',
			array(
				'label'       => __( 'Contact Cards', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'icon_char' => '&#9742;', 'label' => 'DIRECT PHONE', 'value' => '+44 7707 159384', 'link' => array( 'url' => 'tel:+447707159384' ) ),
					array( 'icon_char' => '&#9993;', 'label' => 'BOOKING ENQUIRIES', 'value' => 'info@mw-showband.com', 'link' => array( 'url' => 'mailto:info@mw-showband.com' ) ),
					array( 'icon_char' => '&#127760;', 'label' => 'GLOBAL AGENCY HQ', 'value' => 'London • Marbella • Tel Aviv', 'link' => array( 'url' => '#' ) ),
				),
				'title_field' => '{{{ label }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'signoff_section',
			array(
				'label' => __( 'Second Button & Sign-off (optional)', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control( 'button2_text', array( 'label' => __( 'Second Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '' ) );
		$this->add_control( 'button2_link', array( 'label' => __( 'Second Button Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '' ), 'condition' => array( 'button2_text!' => '' ) ) );
		$this->add_control( 'show_signoff', array( 'label' => __( 'Show Personal Sign-off', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => '' ) );
		$this->add_control( 'signoff_name', array( 'label' => __( 'Name(s)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Marlon & Wayne', 'condition' => array( 'show_signoff' => 'yes' ) ) );
		$this->add_control( 'signoff_role', array( 'label' => __( 'Role', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Musical & Creative Directors', 'condition' => array( 'show_signoff' => 'yes' ) ) );
		$this->add_control( 'signoff_note', array( 'label' => __( 'Note', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MW Showband Ltd. • Confidential Client Copy • Valid for 30 days from date of presentation.', 'condition' => array( 'show_signoff' => 'yes' ) ) );
		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'desc' => '.mwsb-final-cta__desc', 'content_gap' => '.mwsb-final-cta__btns' ) );

		$this->start_controls_section(
			'mwsb_final_cta_style',
			array(
				'label' => __( 'Text & Contact Cards', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'final_cta_eyebrow_color',
			array(
				'label'     => __( 'Eyebrow Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-tertiary)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-final-cta__eyebrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'final_cta_desc_typography',
				'label'     => __( 'Description Typography', 'mw-showband' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .mwsb-final-cta__desc',
			)
		);

		$this->add_control(
			'contact_card_icon_color',
			array(
				'label'     => __( 'Contact Card Icon Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-tertiary)',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-contact-card__icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="mwsb-widget mwsb-section mwsb-final-cta">
			<div class="mwsb-final-cta__bloom"></div>
			<div class="mwsb-container mwsb-final-cta__content">
				<span class="mwsb-eyebrow mwsb-final-cta__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
				<h2 class="mwsb-heading-display mwsb-heading-display--hero">
					<?php echo esc_html( $s['title_line1'] ); ?><br />
					<span class="mwsb-gradient-text"><?php echo esc_html( $s['title_line2'] ); ?></span>
				</h2>
				<p class="mwsb-final-cta__desc"><?php echo esc_html( $s['description'] ); ?></p>
				<div class="mwsb-final-cta__btns">
					<a href="<?php echo esc_url( $s['button_link']['url'] ); ?>" class="mwsb-btn mwsb-btn--primary mwsb-final-cta__btn"><?php echo esc_html( $s['button_text'] ); ?></a>
					<?php if ( ! empty( $s['button2_text'] ) ) : ?>
						<a href="<?php echo esc_url( $s['button2_link']['url'] ); ?>" class="mwsb-btn mwsb-btn--ghost mwsb-final-cta__btn"><?php echo esc_html( $s['button2_text'] ); ?></a>
					<?php endif; ?>
				</div>

				<div class="mwsb-final-cta__contacts">
					<?php foreach ( $s['contacts'] as $c ) : ?>
						<a href="<?php echo esc_url( $c['link']['url'] ); ?>" class="mwsb-contact-card">
							<span class="mwsb-contact-card__icon"><?php echo wp_kses_post( $c['icon_char'] ); ?></span>
							<span class="mwsb-contact-card__label"><?php echo esc_html( $c['label'] ); ?></span>
							<span class="mwsb-contact-card__value"><?php echo esc_html( $c['value'] ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>

				<?php if ( 'yes' === $s['show_signoff'] ) : ?>
					<div class="mwsb-final-cta__signoff">
						<span class="mwsb-final-cta__signoff-name"><?php echo esc_html( $s['signoff_name'] ); ?></span>
						<span class="mwsb-final-cta__signoff-role"><?php echo esc_html( $s['signoff_role'] ); ?></span>
						<p class="mwsb-final-cta__signoff-note"><?php echo esc_html( $s['signoff_note'] ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
