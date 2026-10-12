<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

/**
 * MW Showband — proposal page: "Three Ways To Own The Night" pricing cards.
 * Each card's "Select This Lineup" button carries data-mwsb-package-select +
 * data-price + data-tier-name, read by assets/js/proposal.js to live-update
 * the Investment & Payment Schedule widget elsewhere on the page.
 * One-off bespoke skin for the sales proposal page only — see
 * assets/css/proposal.css.
 * Author: Aqib Dev
 */
class MWSB_Proposal_Packages_Widget extends Widget_Base {

	use MWSB_Section_Controls;
	use MWSB_Proposal_Style_Controls;

	public function get_name() {
		return 'mwsb-proposal-packages';
	}

	public function get_title() {
		return __( 'MW Proposal — Packages', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	public function get_script_depends() {
		return array( 'mwsb-proposal' );
	}

	public function get_style_depends() {
		return array( 'mwsb-tokens', 'mwsb-proposal' );
	}

	/** One newline-per-item textarea -> list of trimmed, non-empty lines. */
	private function lines( $text ) {
		return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $text ) ) ) );
	}

	protected function register_controls() {
		$this->start_controls_section( 'content_head', array( 'label' => __( 'Heading', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'anchor_id', array( 'label' => __( 'Section Anchor ID', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'default' => 'packages' ) );
		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '// 03 / PERFORMANCE ARCHITECTURE' ) );
		$this->add_control( 'title_tag', array( 'label' => __( 'Title HTML Tag', 'mw-showband' ), 'type' => Controls_Manager::SELECT, 'default' => 'h2', 'options' => array( 'h2' => 'H2', 'h3' => 'H3', 'div' => 'div' ) ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'THREE WAYS TO OWN THE NIGHT.' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Every package includes our uncompromising sound engineering, custom musical arrangements, and dedicated production management. Select the line-up that matches your venue scale.' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'content_cards', array( 'label' => __( 'Package Cards', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$repeater = new Repeater();
		$repeater->add_control(
			'accent',
			array(
				'label'   => __( 'Accent Color', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'purple',
				'options' => array( 'purple' => __( 'Purple', 'mw-showband' ), 'magenta' => __( 'Magenta', 'mw-showband' ), 'cyan' => __( 'Cyan', 'mw-showband' ) ),
			)
		);
		$repeater->add_control( 'badge', array( 'label' => __( 'Configuration Badge', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CONFIGURATION 01' ) );
		$repeater->add_control( 'capacity', array( 'label' => __( 'Capacity Badge', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'UP TO 150 GUESTS' ) );
		$repeater->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'DYNAMIC 8-PIECE' ) );
		$repeater->add_control( 'price', array( 'label' => __( 'Price', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '£8,000' ) );
		$repeater->add_control( 'price_note', array( 'label' => __( 'Price Note', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '(+ VAT)' ) );
		$repeater->add_control( 'text', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Ideal for intimate luxury celebrations & boutique estates requiring pristine acoustic agility and electric presence.' ) );
		$repeater->add_control( 'ensemble_title', array( 'label' => __( 'Ensemble List Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'ENSEMBLE COMPOSITION' ) );
		$repeater->add_control( 'ensemble_items', array( 'label' => __( 'Ensemble Items (one per line)', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => "3 Lead Vocalists (2 Female, 1 Male Frontman)\n4-Piece Rhythm Section (Drums, Bass, Guitar, Keys)\n1 Horn (Saxophone / Multi-instrumentalist)", 'rows' => 4 ) );
		$repeater->add_control( 'inclusions_title', array( 'label' => __( 'Inclusions List Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CORE INCLUSIONS' ) );
		$repeater->add_control( 'inclusions_items', array( 'label' => __( 'Inclusions Items (one per line)', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => "2 x 60 min explosive live sets\nDedicated Front-of-House Sound Engineer\nWireless microphone system for toasts & speeches\nStandard architectural stage lighting package", 'rows' => 5 ) );
		$repeater->add_control( 'spec_text', array( 'label' => __( 'View Specification Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'VIEW SPECIFICATION' ) );
		$repeater->add_control( 'spec_link', array( 'label' => __( 'View Specification Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#' ) ) );
		$repeater->add_control( 'select_text', array( 'label' => __( 'Select Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'SELECT THIS LINEUP' ) );
		$repeater->add_control( 'tier_price', array( 'label' => __( 'Tier Price (number, no symbols — drives the live calculator)', 'mw-showband' ), 'type' => Controls_Manager::NUMBER, 'default' => 8000 ) );
		$this->add_control(
			'cards',
			array(
				'label'       => __( 'Cards', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'accent' => 'purple', 'badge' => 'CONFIGURATION 01', 'capacity' => 'UP TO 150 GUESTS', 'title' => 'DYNAMIC 8-PIECE', 'price' => '£8,000', 'price_note' => '(+ VAT)', 'text' => 'Ideal for intimate luxury celebrations & boutique estates requiring pristine acoustic agility and electric presence.', 'ensemble_title' => 'ENSEMBLE COMPOSITION', 'ensemble_items' => "3 Lead Vocalists (2 Female, 1 Male Frontman)\n4-Piece Rhythm Section (Drums, Bass, Guitar, Keys)\n1 Horn (Saxophone / Multi-instrumentalist)", 'inclusions_title' => 'CORE INCLUSIONS', 'inclusions_items' => "2 x 60 min explosive live sets\nDedicated Front-of-House Sound Engineer\nWireless microphone system for toasts & speeches\nStandard architectural stage lighting package", 'spec_text' => 'VIEW SPECIFICATION', 'select_text' => 'SELECT THIS LINEUP', 'tier_price' => 8000 ),
					array( 'accent' => 'magenta', 'badge' => 'CONFIGURATION 02', 'capacity' => '150–350 GUESTS', 'title' => 'HIGH ENERGY 10-PIECE', 'price' => '£9,500', 'price_note' => '(+ VAT)', 'text' => 'The definitive showband experience for grand ballrooms & marquee galas requiring massive vocal depth and brass punch.', 'ensemble_title' => 'ENSEMBLE COMPOSITION', 'ensemble_items' => "4 Vocalists (4-part harmonies, rap/soul versatile)\n4-Piece Rhythm Section (Drums, Bass, Guitar, Keys)\n2-Piece Power Horn Section (Saxophone & Trumpet)", 'inclusions_title' => 'CORE INCLUSIONS', 'inclusions_items' => "2 x 60 min or 1 x 120 min seamless headline show\nFull digital sound desk & timecoded lighting stems\nBespoke First Dance arrangement orchestrations\nIntegrated DJ service during performer intervals", 'spec_text' => 'VIEW SPECIFICATION', 'select_text' => 'SELECT THIS LINEUP', 'tier_price' => 9500 ),
					array( 'accent' => 'cyan', 'badge' => 'CONFIGURATION 03', 'capacity' => '250+ GUESTS', 'title' => 'FULL EXPERIENCE 12-PIECE', 'price' => '£10,500', 'price_note' => '(+ VAT)', 'text' => 'Stadium-grade sonic power for high-impact international productions requiring full horn brass section and live percussion.', 'ensemble_title' => 'ENSEMBLE COMPOSITION', 'ensemble_items' => "5 Frontline Vocalists\n4-Piece Rhythm Section (Drums, Bass, Guitar, Keys)\n3-Piece Horns (Trumpet, Tenor Sax, Trombone) + Live Percussion", 'inclusions_title' => 'CORE INCLUSIONS', 'inclusions_items' => "Cocktail acoustic duo + Dinner ambiance set included\n2 x 75 min headline concert encore\nFull festival audio-visual tech rider execution\nComprehensive musical direction & custom orchestrations", 'spec_text' => 'VIEW SPECIFICATION', 'select_text' => 'SELECT THIS LINEUP', 'tier_price' => 10500 ),
				),
				'title_field' => '{{{ title }}}',
			)
		);
		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_proposal_style_controls();
	}

	/** Style tab: layout, section heading, then one section per element of a pricing card. */
	private function register_proposal_style_controls() {
		// Layout.
		$this->mwsb_prop_section_start( 'style_layout', __( 'Layout', 'mw-showband' ) );
		$this->mwsb_prop_layout_controls( 'layout', '.mwsb-prop-packages-section' );
		$this->add_responsive_control(
			'layout_columns',
			array(
				'label'     => __( 'Card Columns', 'mw-showband' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array( '' => __( 'Default', 'mw-showband' ), '1' => '1', '2' => '2', '3' => '3', '4' => '4' ),
				'selectors' => array( '{{WRAPPER}} .mwsb-prop-packages' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ),
			)
		);
		$this->mwsb_prop_slider( 'layout_gap', __( 'Gap Between Cards', 'mw-showband' ), '.mwsb-prop-packages', 'gap', array( 'px' => array( 'min' => 0, 'max' => 80 ) ) );
		$this->mwsb_prop_section_end();

		// Section heading.
		$this->mwsb_prop_section_head_controls( 'head' );

		// Card box + the three accent colours.
		$this->mwsb_prop_section_start( 'style_card', __( 'Pricing Card', 'mw-showband' ) );
		$this->mwsb_prop_box_controls( 'card_box', '.mwsb-prop-card', array( 'hover_bg' => true ) );
		$this->mwsb_prop_transition_control( 'card_transition', '.mwsb-prop-card' );
		$this->mwsb_prop_heading( 'card_accent_h', __( 'Accent Colors (badge + ensemble icons)', 'mw-showband' ) );
		foreach ( array( 'purple' => __( 'Purple Card', 'mw-showband' ), 'magenta' => __( 'Magenta Card', 'mw-showband' ), 'cyan' => __( 'Cyan Card', 'mw-showband' ) ) as $accent => $label ) {
			$this->add_control(
				'card_accent_' . $accent,
				array(
					'label'     => $label,
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mwsb-prop-card--' . $accent . ' .mwsb-prop-card__badge' => 'color: {{VALUE}};',
						'{{WRAPPER}} .mwsb-prop-card--' . $accent . ' .mwsb-prop-card__list--ensemble .mwsb-prop-icon' => 'color: {{VALUE}};',
					),
				)
			);
		}
		$this->mwsb_prop_section_end();

		// Top row: badge + capacity pill.
		$this->mwsb_prop_section_start( 'style_card_top', __( 'Card Badge & Capacity', 'mw-showband' ) );
		$this->mwsb_prop_heading( 'card_badge_h', __( 'Badge Text', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'card_badge', '.mwsb-prop-card__badge', array( 'color' => false ) );
		$this->mwsb_prop_heading( 'card_capacity_h', __( 'Capacity Pill', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'card_capacity', '.mwsb-prop-card__capacity' );
		$this->mwsb_prop_box_controls( 'card_capacity_box', '.mwsb-prop-card__capacity' );
		$this->mwsb_prop_section_end();

		// Title / price / description.
		$this->mwsb_prop_section_start( 'style_card_text', __( 'Card Title, Price & Text', 'mw-showband' ) );
		$this->mwsb_prop_heading( 'card_title_h', __( 'Title', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'card_title', '.mwsb-prop-card__title', array( 'spacing' => true ) );
		$this->mwsb_prop_heading( 'card_price_h', __( 'Price', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'card_price', '.mwsb-prop-card__price' );
		$this->mwsb_prop_heading( 'card_price_note_h', __( 'Price Note (+ VAT)', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'card_price_note', '.mwsb-prop-card__price-note' );
		$this->mwsb_prop_heading( 'card_text_h', __( 'Description', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'card_text', '.mwsb-prop-card__text', array( 'spacing' => true ) );
		$this->mwsb_prop_section_end();

		// Lists.
		$this->mwsb_prop_section_start( 'style_card_lists', __( 'Card Lists', 'mw-showband' ) );
		$this->mwsb_prop_heading( 'card_hr_h', __( 'Divider Lines', 'mw-showband' ) );
		$this->add_control( 'card_hr_color', array( 'label' => __( 'Divider Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-card__hr' => 'background-color: {{VALUE}};' ) ) );
		$this->mwsb_prop_dimensions( 'card_hr_margin', __( 'Divider Spacing', 'mw-showband' ), '.mwsb-prop-card__hr', 'margin' );
		$this->mwsb_prop_heading( 'card_list_label_h', __( 'List Heading', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'card_list_label', '.mwsb-prop-card__list-label', array( 'spacing' => true ) );
		$this->mwsb_prop_heading( 'card_list_item_h', __( 'List Items', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'card_list_item', '.mwsb-prop-card__list li' );
		$this->mwsb_prop_slider( 'card_list_gap', __( 'Gap Between Items', 'mw-showband' ), '.mwsb-prop-card__list', 'gap', array( 'px' => array( 'min' => 0, 'max' => 40 ) ) );
		$this->mwsb_prop_heading( 'card_list_icon_h', __( 'Icons', 'mw-showband' ) );
		$this->add_control( 'card_incl_icon_color', array( 'label' => __( 'Inclusions Icon Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-card__list--inclusions .mwsb-prop-icon' => 'color: {{VALUE}};' ) ) );
		$this->mwsb_prop_slider( 'card_list_icon_size', __( 'Icon Size', 'mw-showband' ), '.mwsb-prop-card__list .mwsb-prop-icon', 'font-size', array( 'px' => array( 'min' => 8, 'max' => 40 ) ) );
		$this->mwsb_prop_section_end();

		// Buttons.
		$this->mwsb_prop_section_start( 'style_card_buttons', __( 'Card Buttons', 'mw-showband' ) );
		$this->mwsb_prop_slider( 'card_buttons_gap', __( 'Gap Between Buttons', 'mw-showband' ), '.mwsb-prop-card__actions', 'gap', array( 'px' => array( 'min' => 0, 'max' => 40 ) ) );
		$this->mwsb_prop_heading( 'card_spec_btn_h', __( 'Specification Button', 'mw-showband' ) );
		$this->mwsb_prop_button_controls( 'card_spec_btn', '.mwsb-prop-card__btn-outline' );
		$this->mwsb_prop_heading( 'card_select_btn_h', __( 'Select Button', 'mw-showband' ) );
		$this->mwsb_prop_button_controls( 'card_select_btn', '.mwsb-prop-card__actions .mwsb-prop-btn--primary' );
		$this->add_control( 'card_select_selected_bg', array( 'label' => __( 'Selected State Background', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-card__actions .mwsb-prop-btn--primary.is-selected' => 'background: {{VALUE}};' ) ) );
		$this->mwsb_prop_section_end();

		// Visibility.
		$this->mwsb_prop_section_start( 'style_visibility', __( 'Show / Hide Elements', 'mw-showband' ) );
		$this->mwsb_prop_hide_switch( 'hide_card_badge', __( 'Hide Badge', 'mw-showband' ), '.mwsb-prop-card__badge' );
		$this->mwsb_prop_hide_switch( 'hide_card_capacity', __( 'Hide Capacity Pill', 'mw-showband' ), '.mwsb-prop-card__capacity' );
		$this->mwsb_prop_hide_switch( 'hide_card_price_note', __( 'Hide Price Note', 'mw-showband' ), '.mwsb-prop-card__price-note' );
		$this->mwsb_prop_hide_switch( 'hide_card_text', __( 'Hide Card Description', 'mw-showband' ), '.mwsb-prop-card__text' );
		$this->mwsb_prop_hide_switch( 'hide_card_hr', __( 'Hide Divider Lines', 'mw-showband' ), '.mwsb-prop-card__hr' );
		$this->mwsb_prop_hide_switch( 'hide_card_spec', __( 'Hide Specification Button', 'mw-showband' ), '.mwsb-prop-card__btn-outline' );
		$this->mwsb_prop_section_end();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$tag    = in_array( $s['title_tag'], array( 'h2', 'h3', 'div' ), true ) ? $s['title_tag'] : 'h2';
		$cards  = ! empty( $s['cards'] ) ? $s['cards'] : array();
		$anchor = ! empty( $s['anchor_id'] ) ? $s['anchor_id'] : 'packages';
		$check  = array( 'value' => 'fas fa-circle-check', 'library' => 'fa-solid' );
		?>
		<section id="<?php echo esc_attr( $anchor ); ?>" class="mwsb-widget mwsb-section mwsb-prop mwsb-prop-packages-section">
			<div class="mwsb-container">
				<div class="mwsb-prop-head">
					<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
						<span class="mwsb-prop-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $s['title'] ) ) : ?>
						<<?php echo esc_html( $tag ); ?> class="mwsb-prop-heading mwsb-prop-heading--xl"><?php echo esc_html( $s['title'] ); ?></<?php echo esc_html( $tag ); ?>>
					<?php endif; ?>
					<?php if ( ! empty( $s['description'] ) ) : ?>
						<p class="mwsb-prop-desc"><?php echo esc_html( $s['description'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( $cards ) : ?>
					<div class="mwsb-prop-packages">
						<?php foreach ( $cards as $i => $card ) : ?>
							<?php
							$accent   = in_array( $card['accent'], array( 'purple', 'magenta', 'cyan' ), true ) ? $card['accent'] : 'purple';
							$ensemble = $this->lines( $card['ensemble_items'] );
							$inclusions = $this->lines( $card['inclusions_items'] );
							$mic_icon = array( 'value' => 'fas fa-microphone', 'library' => 'fa-solid' );
							$check    = array( 'value' => 'fas fa-circle-check', 'library' => 'fa-solid' );
							?>
							<div class="mwsb-prop-card mwsb-prop-card--<?php echo esc_attr( $accent ); ?>">
								<div>
									<div class="mwsb-prop-card__top">
										<span class="mwsb-prop-card__badge"><?php echo esc_html( $card['badge'] ); ?></span>
										<?php if ( ! empty( $card['capacity'] ) ) : ?>
											<span class="mwsb-prop-card__capacity"><?php echo esc_html( $card['capacity'] ); ?></span>
										<?php endif; ?>
									</div>
									<h3 class="mwsb-prop-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
									<div class="mwsb-prop-card__price-row">
										<span class="mwsb-prop-card__price"><?php echo esc_html( $card['price'] ); ?></span>
										<?php if ( ! empty( $card['price_note'] ) ) : ?>
											<span class="mwsb-prop-card__price-note"><?php echo esc_html( $card['price_note'] ); ?></span>
										<?php endif; ?>
									</div>
									<?php if ( ! empty( $card['text'] ) ) : ?>
										<p class="mwsb-prop-card__text"><?php echo esc_html( $card['text'] ); ?></p>
									<?php endif; ?>

									<?php if ( $ensemble ) : ?>
										<div class="mwsb-prop-card__hr"></div>
										<span class="mwsb-prop-card__list-label"><?php echo esc_html( $card['ensemble_title'] ); ?></span>
										<ul class="mwsb-prop-card__list mwsb-prop-card__list--ensemble">
											<?php foreach ( $ensemble as $line ) : ?>
												<li><?php Icons_Manager::render_icon( $mic_icon, array( 'aria-hidden' => 'true', 'class' => 'mwsb-prop-icon' ) ); ?><span><?php echo esc_html( $line ); ?></span></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>

									<?php if ( $inclusions ) : ?>
										<div class="mwsb-prop-card__hr"></div>
										<span class="mwsb-prop-card__list-label"><?php echo esc_html( $card['inclusions_title'] ); ?></span>
										<ul class="mwsb-prop-card__list mwsb-prop-card__list--inclusions">
											<?php foreach ( $inclusions as $line ) : ?>
												<li><?php Icons_Manager::render_icon( $check, array( 'aria-hidden' => 'true', 'class' => 'mwsb-prop-icon' ) ); ?><span><?php echo esc_html( $line ); ?></span></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>

								<div class="mwsb-prop-card__actions">
									<?php if ( ! empty( $card['spec_text'] ) ) : ?>
										<a class="mwsb-prop-card__btn-outline" href="<?php echo esc_url( $card['spec_link']['url'] ); ?>"><?php echo esc_html( $card['spec_text'] ); ?></a>
									<?php endif; ?>
									<?php if ( ! empty( $card['select_text'] ) ) : ?>
										<button
											type="button"
											class="mwsb-prop-btn mwsb-prop-btn--primary mwsb-prop-btn--block"
											data-mwsb-package-select="1"
											data-price="<?php echo esc_attr( $card['tier_price'] ); ?>"
											data-tier-name="<?php echo esc_attr( $card['title'] ); ?>"
										><?php echo esc_html( $card['select_text'] ); ?></button>
									<?php endif; ?>
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
