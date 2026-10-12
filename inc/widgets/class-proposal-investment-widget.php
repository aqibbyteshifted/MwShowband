<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

/**
 * MW Showband — proposal page: "Investment & Payment Schedule" — a fee
 * breakdown card plus a 3-stage milestone roadmap. The data-mwsb-* hooks on
 * the dynamic figures are read by assets/js/proposal.js and live-updated
 * when a Packages widget "Select This Lineup" button is clicked elsewhere
 * on the page. One-off bespoke skin for the sales proposal page only —
 * see assets/css/proposal.css.
 * Author: Aqib Dev
 */
class MWSB_Proposal_Investment_Widget extends Widget_Base {

	use MWSB_Section_Controls;
	use MWSB_Proposal_Style_Controls;

	public function get_name() {
		return 'mwsb-proposal-investment';
	}

	public function get_title() {
		return __( 'MW Proposal — Investment Schedule', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-price-list';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	public function get_style_depends() {
		return array( 'mwsb-tokens', 'mwsb-proposal' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'content_head', array( 'label' => __( 'Heading', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '// 04 / FINANCIAL TRANSPARENCY & SCHEDULE' ) );
		$this->add_control( 'title_tag', array( 'label' => __( 'Title HTML Tag', 'mw-showband' ), 'type' => Controls_Manager::SELECT, 'default' => 'h2', 'options' => array( 'h2' => 'H2', 'h3' => 'H3', 'div' => 'div' ) ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'INVESTMENT & PAYMENT SCHEDULE.' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Complete fee transparency with no hidden riders. Clear three-stage milestone payments aligned with your event planning roadmap.' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'content_breakdown', array( 'label' => __( 'Breakdown Card', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'tier_tag', array( 'label' => __( 'Selected Tier Tag', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '10-PIECE HIGH ENERGY (SELECTED)' ) );
		$this->add_control( 'base_label', array( 'label' => __( 'Base Fee Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Base Performance Ensemble Fee' ) );
		$this->add_control( 'base_amount', array( 'label' => __( 'Base Fee Amount', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '£9,500.00' ) );

		$repeater = new Repeater();
		$repeater->add_control( 'label', array( 'label' => __( 'Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Sound, Production & Backline Logistics' ) );
		$this->add_control(
			'included_rows',
			array(
				'label'       => __( '"Included" Rows', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'label' => 'Sound, Production & Backline Logistics' ),
					array( 'label' => 'UK Nationwide Travel & Crew Tour Logistics' ),
					array( 'label' => 'Bespoke Song Orchestrations (up to 3 tracks)' ),
				),
				'title_field' => '{{{ label }}}',
			)
		);

		$this->add_control( 'vat_label', array( 'label' => __( 'VAT Row Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'UK Standard VAT (20%)' ) );
		$this->add_control( 'vat_amount', array( 'label' => __( 'VAT Amount', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '£1,900.00' ) );
		$this->add_control( 'total_label', array( 'label' => __( 'Total Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'TOTAL ALL-INCLUSIVE PROPOSAL' ) );
		$this->add_control( 'total_note', array( 'label' => __( 'Total Sub-note', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Guaranteed fixed rate through 2026' ) );
		$this->add_control( 'total_amount', array( 'label' => __( 'Total Amount', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '£11,400.00' ) );
		$this->add_control( 'note_text', array( 'label' => __( 'Footnote', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Includes comprehensive public liability insurance (£10M), PAT certified audio apparatus, venue liaison, sound limiter compliance protocol and discrete VIP rider handling.' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'content_milestones', array( 'label' => __( 'Milestone Roadmap', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'phases_label', array( 'label' => __( 'Phases Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '3 EQUITABLE PHASES' ) );
		$ms = new Repeater();
		$ms->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '40% BOOKING DEPOSIT' ) );
		$ms->add_control( 'amount', array( 'label' => __( 'Amount', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '£3,800 + VAT' ) );
		$ms->add_control( 'due', array( 'label' => __( 'Due Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'UPON FORMAL ENGAGEMENT' ) );
		$ms->add_control( 'text', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Secures the exclusive date lock. Locks musicians, front-line vocalists, and initiates client concierge onboarding.' ) );
		$ms->add_control( 'active', array( 'label' => __( 'Highlight as Active', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => '' ) );
		$this->add_control(
			'milestones',
			array(
				'label'       => __( 'Milestones', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $ms->get_controls(),
				'default'     => array(
					array( 'title' => '40% BOOKING DEPOSIT', 'amount' => '£3,800 + VAT', 'due' => 'UPON FORMAL ENGAGEMENT', 'text' => 'Secures the exclusive date lock for Saturday, 18th July 2026. Locks musicians, front-line vocalists, and initiates client concierge onboarding.', 'active' => 'yes' ),
					array( 'title' => '40% MID-TERM PAYMENT', 'amount' => '£3,800 + VAT', 'due' => 'DUE 18TH MAY 2026 (2 MONTHS PRIOR)', 'text' => 'Initiates bespoke song orchestrations, rehearsal block bookings, and technical site survey coordination with the venue manager.', 'active' => '' ),
					array( 'title' => '20% FINAL BALANCE', 'amount' => '£1,900 + VAT', 'due' => 'DUE 4TH JULY 2026 (2 WEEKS PRIOR)', 'text' => 'Final setlist sign-off, sound technician call-sheet lockdown, and logistics dispatch for flawless event execution.', 'active' => '' ),
				),
				'title_field' => '{{{ title }}}',
			)
		);
		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_proposal_style_controls();
	}

	/** Style tab: layout, section heading, then one section per part of the two cards. */
	private function register_proposal_style_controls() {
		// Layout.
		$this->mwsb_prop_section_start( 'style_layout', __( 'Layout', 'mw-showband' ) );
		$this->mwsb_prop_layout_controls( 'layout', '.mwsb-prop-invest' );
		$this->add_responsive_control(
			'layout_columns',
			array(
				'label'     => __( 'Card Columns', 'mw-showband' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array( '' => __( 'Default', 'mw-showband' ), '1' => '1', '2' => '2' ),
				'selectors' => array( '{{WRAPPER}} .mwsb-prop-invest__grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ),
			)
		);
		$this->mwsb_prop_slider( 'layout_gap', __( 'Gap Between Cards', 'mw-showband' ), '.mwsb-prop-invest__grid', 'gap', array( 'px' => array( 'min' => 0, 'max' => 100 ) ) );
		$this->mwsb_prop_section_end();

		// Section heading.
		$this->mwsb_prop_section_head_controls( 'head' );

		// Both cards.
		$this->mwsb_prop_section_start( 'style_card', __( 'Cards', 'mw-showband' ) );
		$this->mwsb_prop_box_controls( 'card_box', '.mwsb-prop-invest__card' );
		$this->mwsb_prop_slider( 'card_gap', __( 'Gap Inside Card', 'mw-showband' ), '.mwsb-prop-invest__card', 'gap', array( 'px' => array( 'min' => 0, 'max' => 60 ) ) );
		$this->mwsb_prop_heading( 'card_head_h', __( 'Card Heading', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'card_head', '.mwsb-prop-invest__card-head > span:first-child' );
		$this->mwsb_prop_heading( 'card_tag_h', __( 'Selected-Tier Tag', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'card_tag', '.mwsb-prop-invest__tag' );
		$this->mwsb_prop_box_controls( 'card_tag_box', '.mwsb-prop-invest__tag' );
		$this->mwsb_prop_heading( 'card_phases_h', __( 'Phases Label', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'card_phases', '.mwsb-prop-invest__phases' );
		$this->mwsb_prop_section_end();

		// Fee rows.
		$this->mwsb_prop_section_start( 'style_rows', __( 'Fee Rows', 'mw-showband' ) );
		$this->mwsb_prop_heading( 'row_label_h', __( 'Row Label', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'row_label', '.mwsb-prop-invest__row > span:first-child' );
		$this->mwsb_prop_heading( 'row_amount_h', __( 'Amount', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'row_amount', '.mwsb-prop-invest__row strong' );
		$this->mwsb_prop_heading( 'row_included_h', __( '"INCLUDED" Label', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'row_included', '.mwsb-prop-invest__row .mwsb-prop-invest__included' );
		$this->mwsb_prop_heading( 'row_line_h', __( 'Row Lines & Spacing', 'mw-showband' ) );
		$this->add_control( 'row_line_color', array( 'label' => __( 'Line Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-invest__row' => 'border-bottom-color: {{VALUE}};' ) ) );
		$this->mwsb_prop_dimensions( 'row_padding', __( 'Row Padding', 'mw-showband' ), '.mwsb-prop-invest__row', 'padding' );
		$this->mwsb_prop_section_end();

		// Total box.
		$this->mwsb_prop_section_start( 'style_total', __( 'Total Box', 'mw-showband' ) );
		$this->mwsb_prop_box_controls( 'total_box', '.mwsb-prop-invest__total' );
		$this->mwsb_prop_heading( 'total_label_h', __( 'Label', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'total_label', '.mwsb-prop-invest__total-label span' );
		$this->mwsb_prop_heading( 'total_note_h', __( 'Note', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'total_note', '.mwsb-prop-invest__total-label small' );
		$this->mwsb_prop_heading( 'total_amount_h', __( 'Total Amount', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'total_amount', '.mwsb-prop-invest__total strong' );
		$this->mwsb_prop_section_end();

		// Insurance note.
		$this->mwsb_prop_section_start( 'style_note', __( 'Info Note', 'mw-showband' ) );
		$this->mwsb_prop_box_controls( 'note_box', '.mwsb-prop-invest__note' );
		$this->mwsb_prop_text_controls( 'note_text', '.mwsb-prop-invest__note p' );
		$this->mwsb_prop_heading( 'note_icon_h', __( 'Icon', 'mw-showband' ) );
		$this->add_control( 'note_icon_color', array( 'label' => __( 'Icon Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-invest__note .mwsb-prop-icon' => 'color: {{VALUE}};' ) ) );
		$this->mwsb_prop_slider( 'note_icon_size', __( 'Icon Size', 'mw-showband' ), '.mwsb-prop-invest__note .mwsb-prop-icon', 'font-size', array( 'px' => array( 'min' => 8, 'max' => 48 ) ) );
		$this->mwsb_prop_hide_switch( 'note_hide', __( 'Hide Info Note', 'mw-showband' ), '.mwsb-prop-invest__note' );
		$this->mwsb_prop_section_end();

		// Milestones.
		$this->mwsb_prop_section_start( 'style_milestones', __( 'Payment Milestones', 'mw-showband' ) );
		$this->mwsb_prop_heading( 'ms_num_h', __( 'Number Circle', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'ms_num', '.mwsb-prop-milestone__num' );
		$this->add_control( 'ms_num_bg', array( 'label' => __( 'Circle Background', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-milestone__num' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'ms_num_active_bg', array( 'label' => __( 'Active Circle Background', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-milestone--active .mwsb-prop-milestone__num' => 'background-color: {{VALUE}};' ) ) );
		$this->add_responsive_control(
			'ms_num_size',
			array(
				'label'      => __( 'Circle Size', 'mw-showband' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 24, 'max' => 80 ) ),
				// Width AND height together so the circle stays a circle.
				'selectors'  => array( '{{WRAPPER}} .mwsb-prop-milestone__num' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_control( 'ms_line_color', array( 'label' => __( 'Connecting Line Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-milestone__line' => 'background-color: {{VALUE}};' ) ) );
		$this->mwsb_prop_slider( 'ms_gap', __( 'Gap Between Circle & Text', 'mw-showband' ), '.mwsb-prop-milestone', 'gap', array( 'px' => array( 'min' => 0, 'max' => 60 ) ) );
		$this->mwsb_prop_heading( 'ms_title_h', __( 'Milestone Title', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'ms_title', '.mwsb-prop-milestone__top span' );
		$this->mwsb_prop_heading( 'ms_amt_h', __( 'Milestone Amount', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'ms_amt', '.mwsb-prop-milestone__amt' );
		$this->mwsb_prop_heading( 'ms_due_h', __( 'Due Date', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'ms_due', '.mwsb-prop-milestone__due' );
		$this->mwsb_prop_heading( 'ms_text_h', __( 'Description', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'ms_text', '.mwsb-prop-milestone__body p' );
		$this->mwsb_prop_hide_switch( 'ms_text_hide', __( 'Hide Milestone Description', 'mw-showband' ), '.mwsb-prop-milestone__body p' );
		$this->mwsb_prop_section_end();
	}

	protected function render() {
		$s          = $this->get_settings_for_display();
		$tag        = in_array( $s['title_tag'], array( 'h2', 'h3', 'div' ), true ) ? $s['title_tag'] : 'h2';
		$included   = ! empty( $s['included_rows'] ) ? $s['included_rows'] : array();
		$milestones = ! empty( $s['milestones'] ) ? $s['milestones'] : array();
		$shield     = array( 'value' => 'fas fa-shield-halved', 'library' => 'fa-solid' );
		$last_key   = count( $milestones ) - 1;
		?>
		<section class="mwsb-widget mwsb-section mwsb-prop mwsb-prop-invest">
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

				<div class="mwsb-prop-invest__grid">
					<div class="mwsb-prop-invest__card">
						<div class="mwsb-prop-invest__card-head">
							<span><?php esc_html_e( 'INVESTMENT BREAKDOWN', 'mw-showband' ); ?></span>
							<span class="mwsb-prop-invest__tag" data-mwsb-active-tier="1"><?php echo esc_html( $s['tier_tag'] ); ?></span>
						</div>
						<div class="mwsb-prop-invest__rows">
							<div class="mwsb-prop-invest__row">
								<span><?php echo esc_html( $s['base_label'] ); ?></span>
								<strong data-mwsb-breakdown-base="1"><?php echo esc_html( $s['base_amount'] ); ?></strong>
							</div>
							<?php foreach ( $included as $row ) : ?>
								<div class="mwsb-prop-invest__row">
									<span><?php echo esc_html( $row['label'] ); ?></span>
									<span class="mwsb-prop-invest__included"><?php esc_html_e( 'INCLUDED', 'mw-showband' ); ?></span>
								</div>
							<?php endforeach; ?>
							<div class="mwsb-prop-invest__row">
								<span><?php echo esc_html( $s['vat_label'] ); ?></span>
								<strong data-mwsb-breakdown-vat="1"><?php echo esc_html( $s['vat_amount'] ); ?></strong>
							</div>
						</div>
						<div class="mwsb-prop-invest__total">
							<div class="mwsb-prop-invest__total-label">
								<span><?php echo esc_html( $s['total_label'] ); ?></span>
								<small><?php echo esc_html( $s['total_note'] ); ?></small>
							</div>
							<strong data-mwsb-breakdown-total="1"><?php echo esc_html( $s['total_amount'] ); ?></strong>
						</div>
						<?php if ( ! empty( $s['note_text'] ) ) : ?>
							<div class="mwsb-prop-invest__note">
								<?php Icons_Manager::render_icon( $shield, array( 'aria-hidden' => 'true', 'class' => 'mwsb-prop-icon' ) ); ?>
								<p><?php echo esc_html( $s['note_text'] ); ?></p>
							</div>
						<?php endif; ?>
					</div>

					<div class="mwsb-prop-invest__card">
						<div class="mwsb-prop-invest__card-head">
							<span><?php esc_html_e( 'MILESTONE PAYMENT ROADMAP', 'mw-showband' ); ?></span>
							<span class="mwsb-prop-invest__phases"><?php echo esc_html( $s['phases_label'] ); ?></span>
						</div>
						<div class="mwsb-prop-milestones">
							<?php foreach ( $milestones as $i => $m ) : ?>
								<div class="mwsb-prop-milestone<?php echo 'yes' === $m['active'] ? ' mwsb-prop-milestone--active' : ''; ?>">
									<div class="mwsb-prop-milestone__col">
										<div class="mwsb-prop-milestone__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></div>
										<?php if ( $i !== $last_key ) : ?>
											<div class="mwsb-prop-milestone__line"></div>
										<?php endif; ?>
									</div>
									<div class="mwsb-prop-milestone__body">
										<div class="mwsb-prop-milestone__top">
											<span><?php echo esc_html( $m['title'] ); ?></span>
											<strong class="mwsb-prop-milestone__amt" data-mwsb-milestone-<?php echo (int) ( $i + 1 ); ?>="1"><?php echo esc_html( $m['amount'] ); ?></strong>
										</div>
										<span class="mwsb-prop-milestone__due"><?php echo esc_html( $m['due'] ); ?></span>
										<?php if ( ! empty( $m['text'] ) ) : ?>
											<p><?php echo esc_html( $m['text'] ); ?></p>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
