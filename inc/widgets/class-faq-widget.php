<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

/**
 * MW Showband — futuristic FAQ accordion.
 * Author: Aqib Dev
 */
class MWSB_Faq_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-faq';
	}

	public function get_title() {
		return __( 'MW FAQ', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-faq';
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

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'TECHNICAL & BOOKING SPECS' ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'FREQUENTLY ASKED QUESTIONS' ) );

		$this->end_controls_section();

		$this->start_controls_section(
			'faqs_section',
			array(
				'label' => __( 'Questions', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'question', array( 'label' => __( 'Question', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Question?' ) );
		$repeater->add_control( 'answer', array( 'label' => __( 'Answer', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Answer text.' ) );

		$this->add_control(
			'faqs',
			array(
				'label'       => __( 'FAQ Items', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'question' => 'What types of events does MW Showband perform at?',
						'answer'   => "MW Showband delivers world-class energy and show-stopping entertainment for an exceptional range of events. We're best known for luxury destination weddings—with a special expertise in Jewish weddings—and a variety of multicultural and traditional celebrations, such as English and European-style weddings.\n\nBeyond weddings, we regularly perform at high-end corporate events such as brand launches, gala dinners, award ceremonies, and store or venue openings. We're also the go-to band for milestone birthdays, bar and bat mitzvahs, private parties, charity balls, and international destination events.\n\nWhatever the occasion, MW Showband knows how to set the tone and keep the energy sky-high from the very first note.",
					),
					array(
						'question' => 'Is MW Showband available for international events?',
						'answer'   => 'Absolutely. We regularly perform at events all over the world and are fully equipped to travel. Whether your event is in London, Marbella, Tel Aviv, New York, Maldives, Cape Town, or beyond, MW Showband is ready to bring the party to you.',
					),
					array(
						'question' => "What's included when booking MW Showband?",
						'answer'   => "Our standard booking includes a full day/evening of entertainment—from acoustic welcome set and elegant dinner music to high-energy party sets and even an optional DJ-led afterparty. We provide a diverse repertoire across almost all genres. Very interactive performances with wireless microphones. Sound/PA system costs are not included in our prices, we do work with our supplier in every other country but if you have picked a sound company already we are happy to coordinate.",
					),
					array(
						'question' => 'How far in advance should I book MW Showband?',
						'answer'   => 'We recommend booking as early as possible, especially for peak wedding seasons and international events. Many of our clients reserve their date 6 to 12 months in advance to ensure availability, but feel free to get in touch—we\'ll always do our best to accommodate you.',
					),
					array(
						'question' => 'How big is the band, and can it be customized for my event?',
						'answer'   => "MW Showband exclusively performs live music—we don't do DJ Live sets. This means every show features real musicians and vocalists delivering an electrifying, authentic performance from start to finish.\n\nAt minimum, we require a 4-piece rhythm section (drums, bass, guitar, and keyboard) as the foundation of our sound. From there, the band scales up depending on the type of event, number of guests, musical genres requested, and overall flow of the evening.\n\nWe typically perform as a 10 to 12-piece band, which includes four dynamic vocalists, a powerhouse horn section, and our full rhythm lineup. For events with around 100 guests, we recommend at least a 10-piece band to maintain the energy and impact we're known for.\n\nMW Showband is scalable from an 8-piece to a full 14-piece setup. However, we determine the ideal band size for your event—always with your budget, space, and vision in mind—to ensure the performance exceeds expectations without compromise.",
					),
					array(
						'question' => 'Do you offer DJ services as well?',
						'answer'   => "Yes—we can keep the celebration going during breaks between our live sets even after the band wraps up or. We work closely with one of the UK's top wedding DJs (multi-award-winning and highly experienced) to deliver a seamless musical experience from start to finish.\nPlease note that our DJ service is not included in our standard pricing and is available as an optional add-on.",
					),
					array(
						'question' => 'Can I choose the music you play at my event?',
						'answer'   => "Absolutely. Creating a soundtrack that reflects your taste and keeps your guests engaged is at the heart of what we do. With a repertoire of over 800 songs spanning genres like pop, soul, R&B, Motown, rock, and Israeli music, we carefully craft a setlist that fits your event's energy, crowd, and vision.\n\nTo ensure we get it just right, we'll ask you to share a Spotify playlist of songs you love—and just as importantly, songs you'd prefer to avoid. We'll also schedule a series of planning meetings in the lead-up to your event to talk through your preferences, the event flow, and key musical moments.\n\nWhile we can't take minute-by-minute song requests on stage, we work closely with you to make sure the music feels personal, purposeful, and unforgettable.\n\nPlease note that our DJ service is not included in our standard pricing and is available as an optional add-on.",
					),
					array(
						'question' => 'Can you perform specific songs or first dances on request?',
						'answer'   => "Absolutely. If there's a special song you'd like us to perform—whether it's a first dance, or a family favorite—just let us know in advance.",
					),
				),
				'title_field' => '{{{ question }}}',
			)
		);

		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'head' => '.mwsb-faq__head', 'content_gap' => '.mwsb-faq__list' ) );

		$this->start_controls_section(
			'mwsb_faq_style',
			array(
				'label' => __( 'Question & Answer', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'faq_question_typography',
				'label'    => __( 'Question Typography', 'mw-showband' ),
				'selector' => '{{WRAPPER}} .mwsb-faq__question',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'faq_answer_typography',
				'label'     => __( 'Answer Typography', 'mw-showband' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .mwsb-faq__answer p',
			)
		);

		$this->add_control(
			'faq_icon_color',
			array(
				'label'     => __( 'Toggle Icon Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-accent-purple)',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-faq__icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="mwsb-widget mwsb-section mwsb-faq">
			<div class="mwsb-container mwsb-faq__container">
				<div class="mwsb-faq__head">
					<span class="mwsb-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
					<h2 class="mwsb-heading-display mwsb-heading-display--xl"><?php echo esc_html( $s['title'] ); ?></h2>
				</div>

				<div class="mwsb-faq__list">
					<?php foreach ( $s['faqs'] as $i => $faq ) : ?>
						<div class="mwsb-faq__item">
							<button type="button" class="mwsb-faq__question" data-mwsb-faq-toggle>
								<span><?php echo esc_html( $faq['question'] ); ?></span>
								<span class="mwsb-faq__icon">&#8595;</span>
							</button>
							<div class="mwsb-faq__answer">
								<p><?php echo wp_kses_post( nl2br( esc_html( $faq['answer'] ) ) ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
