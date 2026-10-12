<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

/**
 * MW Showband — "Experience" asymmetric card grid.
 * Author: Aqib Dev
 */
class MWSB_Experience_Cards_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-experience-cards';
	}

	public function get_title() {
		return __( 'MW Experience Cards', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
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

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'ENTERTAINMENT ARCHITECTURE' ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'THE MW EXPERIENCE' ) );

		$this->end_controls_section();

		$this->start_controls_section(
			'cards_section',
			array(
				'label' => __( 'Cards', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'image', array( 'label' => __( 'Image', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => Utils::get_placeholder_image_src() ) ) );
		$repeater->add_control( 'badge', array( 'label' => __( 'Badge', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'ICONIC OCCASIONS' ) );
		$repeater->add_control( 'card_title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WEDDINGS' ) );
		$repeater->add_control( 'quote', array( 'label' => __( 'Quote', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Elegant. Emotional. Explosive.' ) );
		$repeater->add_control( 'text', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'From cinematic orchestral ceremony aisle walks to an earth-shaking 14-piece party juggernaut.' ) );
		$repeater->add_control( 'link_text', array( 'label' => __( 'Link Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Explore Weddings' ) );
		$repeater->add_control( 'link', array( 'label' => __( 'Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#' ) ) );
		$repeater->add_control(
			'accent',
			array(
				'label'   => __( 'Accent Color', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'purple',
				'options' => array(
					'purple'  => __( 'Purple', 'mw-showband' ),
					'magenta' => __( 'Magenta', 'mw-showband' ),
					'cyan'    => __( 'Cyan', 'mw-showband' ),
					'gold'    => __( 'Gold', 'mw-showband' ),
				),
			)
		);

		$repeater->add_control( 'pricing_heading', array( 'type' => Controls_Manager::HEADING, 'label' => __( 'Pricing Card (optional — fills in Price to switch this card to a pricing-tier layout)', 'mw-showband' ), 'separator' => 'before' ) );
		$repeater->add_control( 'price', array( 'label' => __( 'Price', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '' ) );
		$repeater->add_control( 'price_note', array( 'label' => __( 'Price Note', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '(+ VAT)', 'condition' => array( 'price!' => '' ) ) );
		$repeater->add_control( 'capacity_badge', array( 'label' => __( 'Capacity Badge', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '', 'condition' => array( 'price!' => '' ) ) );
		$repeater->add_control( 'list1_title', array( 'label' => __( 'List 1 Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'ENSEMBLE COMPOSITION', 'condition' => array( 'price!' => '' ) ) );
		$repeater->add_control( 'list1_items', array( 'label' => __( 'List 1 Items (one per line)', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => '', 'condition' => array( 'price!' => '' ) ) );
		$repeater->add_control( 'list2_title', array( 'label' => __( 'List 2 Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CORE INCLUSIONS', 'condition' => array( 'price!' => '' ) ) );
		$repeater->add_control( 'list2_items', array( 'label' => __( 'List 2 Items (one per line)', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => '', 'condition' => array( 'price!' => '' ) ) );
		$repeater->add_control( 'button2_text', array( 'label' => __( 'Select Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'SELECT THIS LINEUP', 'condition' => array( 'price!' => '' ) ) );
		$repeater->add_control( 'tier_id', array( 'label' => __( 'Tier ID (for payment calculator, optional)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '', 'condition' => array( 'price!' => '' ) ) );

		$this->add_control(
			'cards',
			array(
				'label'       => __( 'Cards', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'card_title' => 'WEDDINGS', 'badge' => 'ICONIC OCCASIONS', 'quote' => 'Elegant. Emotional. Explosive.', 'accent' => 'purple', 'link_text' => 'Explore Weddings' ),
					array( 'card_title' => 'CORPORATE', 'badge' => 'GLOBAL GALAS', 'quote' => 'Polished. Powerful. Unforgettable.', 'accent' => 'magenta', 'link_text' => 'Explore Corporate' ),
					array( 'card_title' => 'PRIVATE EVENTS', 'badge' => 'VIP CELEBRATIONS', 'quote' => 'Personal. Interactive. High Energy.', 'accent' => 'cyan', 'link_text' => 'Explore Private' ),
				),
				'title_field' => '{{{ card_title }}}',
			)
		);

		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'head' => '.mwsb-experience__head', 'desc' => '.mwsb-experience__desc', 'content_gap' => '.mwsb-experience__grid' ) );

		$this->start_controls_section(
			'mwsb_exp_header_style',
			array(
				'label' => __( 'Header', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'eyebrow_heading',
			array(
				'label' => __( 'Eyebrow', 'mw-showband' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eyebrow_typography',
				'selector' => '{{WRAPPER}} .mwsb-experience .mwsb-eyebrow',
			)
		);

		$this->add_control(
			'eyebrow_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mwsb-experience .mwsb-eyebrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label'     => __( 'Title', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mwsb-experience__head h2',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-white)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-experience__head h2' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_exp_card_style',
			array(
				'label' => __( 'Cards', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'card_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mwsb-card',
			)
		);

		$this->add_responsive_control(
			'card_border_radius',
			array(
				'label'      => __( 'Border Radius', 'mw-showband' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array( 'min' => 0, 'max' => 60 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mwsb-card' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_title_heading',
			array(
				'label'     => __( 'Card Title', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_title_typography',
				'selector' => '{{WRAPPER}} .mwsb-card__title',
			)
		);

		$this->add_control(
			'card_title_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-white)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-card__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_quote_heading',
			array(
				'label'     => __( 'Card Quote', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_quote_typography',
				'selector' => '{{WRAPPER}} .mwsb-card__quote',
			)
		);

		$this->add_control(
			'card_text_heading',
			array(
				'label'     => __( 'Card Description', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_text_typography',
				'selector' => '{{WRAPPER}} .mwsb-card__text',
			)
		);

		$this->add_control(
			'card_text_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-muted)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-card__text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_link_heading',
			array(
				'label'     => __( 'Card Link', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'card_link_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mwsb-card__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_link_hover_color',
			array(
				'label'     => __( 'Hover Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mwsb-card:hover .mwsb-card__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="mwsb-widget mwsb-section mwsb-experience" id="experience">
			<div class="mwsb-container">
				<div class="mwsb-experience__head">
					<div>
						<span class="mwsb-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
						<h2 class="mwsb-heading-display mwsb-heading-display--xl"><?php echo esc_html( $s['title'] ); ?></h2>
					</div>
				</div>

				<div class="mwsb-experience__grid">
					<?php foreach ( $s['cards'] as $card ) : ?>
						<?php if ( ! empty( $card['price'] ) ) : ?>
							<?php
							$list1 = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $card['list1_items'] ) ) );
							$list2 = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $card['list2_items'] ) ) );
							?>
							<div class="mwsb-card mwsb-card--pricing mwsb-card--accent-<?php echo esc_attr( $card['accent'] ); ?>">
								<div class="mwsb-card__pricing-top">
									<span class="mwsb-card__badge mwsb-card__badge--inline"><?php echo esc_html( $card['badge'] ); ?></span>
									<?php if ( ! empty( $card['capacity_badge'] ) ) : ?>
										<span class="mwsb-card__capacity"><?php echo esc_html( $card['capacity_badge'] ); ?></span>
									<?php endif; ?>
								</div>
								<h3 class="mwsb-card__title"><?php echo esc_html( $card['card_title'] ); ?></h3>
								<div class="mwsb-card__price">
									<span class="mwsb-card__price-amount"><?php echo esc_html( $card['price'] ); ?></span>
									<?php if ( ! empty( $card['price_note'] ) ) : ?>
										<span class="mwsb-card__price-note"><?php echo esc_html( $card['price_note'] ); ?></span>
									<?php endif; ?>
								</div>
								<p class="mwsb-card__text"><?php echo esc_html( $card['text'] ); ?></p>
								<?php if ( $list1 ) : ?>
									<div class="mwsb-card__divider"></div>
									<div class="mwsb-card__list">
										<span class="mwsb-card__list-title"><?php echo esc_html( $card['list1_title'] ); ?></span>
										<ul>
											<?php foreach ( $list1 as $item ) : ?>
												<li><?php echo esc_html( $item ); ?></li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>
								<?php if ( $list2 ) : ?>
									<div class="mwsb-card__divider"></div>
									<div class="mwsb-card__list">
										<span class="mwsb-card__list-title"><?php echo esc_html( $card['list2_title'] ); ?></span>
										<ul class="mwsb-card__list--check">
											<?php foreach ( $list2 as $item ) : ?>
												<li><?php echo esc_html( $item ); ?></li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>
								<div class="mwsb-card__pricing-actions">
									<a href="<?php echo esc_url( $card['link']['url'] ); ?>" class="mwsb-card__ghost-btn"><?php echo esc_html( $card['link_text'] ); ?></a>
									<button type="button" class="mwsb-card__select-btn" data-mwsb-package-select data-tier="<?php echo esc_attr( $card['tier_id'] ? $card['tier_id'] : $card['_id'] ); ?>" data-price="<?php echo esc_attr( preg_replace( '/[^0-9.]/', '', $card['price'] ) ); ?>"><?php echo esc_html( $card['button2_text'] ); ?></button>
								</div>
							</div>
						<?php else : ?>
							<div class="mwsb-card mwsb-card--accent-<?php echo esc_attr( $card['accent'] ); ?>">
								<div class="mwsb-card__media">
									<?php if ( ! empty( $card['image']['url'] ) ) : ?>
										<img src="<?php echo esc_url( $card['image']['url'] ); ?>" alt="<?php echo esc_attr( $card['card_title'] ); ?>" />
									<?php endif; ?>
									<span class="mwsb-card__badge"><?php echo esc_html( $card['badge'] ); ?></span>
								</div>
								<div class="mwsb-card__body">
									<div>
										<h3 class="mwsb-card__title"><?php echo esc_html( $card['card_title'] ); ?></h3>
										<p class="mwsb-card__quote">"<?php echo esc_html( $card['quote'] ); ?>"</p>
										<p class="mwsb-card__text"><?php echo esc_html( $card['text'] ); ?></p>
									</div>
									<a href="<?php echo esc_url( $card['link']['url'] ); ?>" class="mwsb-card__link">
										<span><?php echo esc_html( $card['link_text'] ); ?></span>
										<span class="mwsb-card__arrow">&#8594;</span>
									</a>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
