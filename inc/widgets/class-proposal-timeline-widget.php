<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

/**
 * MW Showband — proposal page: "An Experience Designed Around You" 4-stage
 * connected event timeline grid. One-off bespoke skin for the sales
 * proposal page only — see assets/css/proposal.css.
 * Author: Aqib Dev
 */
class MWSB_Proposal_Timeline_Widget extends Widget_Base {

	use MWSB_Section_Controls;
	use MWSB_Proposal_Style_Controls;

	public function get_name() {
		return 'mwsb-proposal-timeline';
	}

	public function get_title() {
		return __( 'MW Proposal — Event Timeline', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	public function get_style_depends() {
		return array( 'mwsb-tokens', 'mwsb-proposal' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'content_head', array( 'label' => __( 'Heading', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'anchor_id', array( 'label' => __( 'Section Anchor ID (for the hero scroll link)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'default' => 'mwsb-event-timeline' ) );
		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '// 02 / BESPOKE EVENT TIMELINE' ) );
		$this->add_control( 'title_tag', array( 'label' => __( 'Title HTML Tag', 'mw-showband' ), 'type' => Controls_Manager::SELECT, 'default' => 'h2', 'options' => array( 'h2' => 'H2', 'h3' => 'H3', 'div' => 'div' ) ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'AN EXPERIENCE DESIGNED AROUND YOU.' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Four curated musical chapters engineered to elevate every phase of your celebration, from atmospheric arrival to stadium-scale after-party.' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'content_stages', array( 'label' => __( 'Stages', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$repeater = new Repeater();
		$repeater->add_control( 'image', array( 'label' => __( 'Image', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => '' ) ) );
		$repeater->add_control( 'stage_num', array( 'label' => __( 'Stage Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'STAGE 01' ) );
		$repeater->add_control( 'time_range', array( 'label' => __( 'Time Range', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '17:30 – 19:00' ) );
		$repeater->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'GUEST ARRIVAL' ) );
		$repeater->add_control( 'text', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Intimate, sophisticated acoustic lounge & champagne jazz to welcome guests with bespoke warmth.' ) );
		$repeater->add_control( 'setup_icon', array( 'label' => __( 'Setup Icon', 'mw-showband' ), 'type' => Controls_Manager::ICONS, 'default' => array( 'value' => 'fas fa-music', 'library' => 'fa-solid' ) ) );
		$repeater->add_control( 'setup_text', array( 'label' => __( 'Curated Setup', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Live Sax & Keys Duo' ) );
		$this->add_control(
			'stages',
			array(
				'label'       => __( 'Stages', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'stage_num' => 'STAGE 01', 'time_range' => '17:30 – 19:00', 'title' => 'GUEST ARRIVAL', 'text' => 'Intimate, sophisticated acoustic lounge & champagne jazz to welcome guests with bespoke warmth.', 'setup_icon' => array( 'value' => 'fas fa-music', 'library' => 'fa-solid' ), 'setup_text' => 'Live Sax & Keys Duo' ),
					array( 'stage_num' => 'STAGE 02', 'time_range' => '19:30 – 21:30', 'title' => 'DINNER MUSIC', 'text' => 'Cinematic Motown, Soul & contemporary neo-jazz ballads engineered at conversation-friendly acoustic perfection.', 'setup_icon' => array( 'value' => 'fas fa-compact-disc', 'library' => 'fa-solid' ), 'setup_text' => '4-Piece Semi-Acoustic' ),
					array( 'stage_num' => 'STAGE 03', 'time_range' => '22:00 – 00:00', 'title' => 'THE PARTY', 'text' => 'Explosive arena-energy showband. Non-stop mashups, high-octane brass, synchronized choreography and epic vocal power.', 'setup_icon' => array( 'value' => 'fas fa-users', 'library' => 'fa-solid' ), 'setup_text' => 'Full Showband Ensemble' ),
					array( 'stage_num' => 'STAGE 04', 'time_range' => '00:00 – 02:00', 'title' => 'AFTER PARTY', 'text' => 'Club-scale festival finish. Live brass & roaming percussion infused over seamless hybrid DJ sets.', 'setup_icon' => array( 'value' => 'fas fa-headphones', 'library' => 'fa-solid' ), 'setup_text' => 'Live Band DJ Fusion + LED Sax' ),
				),
				'title_field' => '{{{ title }}}',
			)
		);
		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_proposal_style_controls();
	}

	/** Style tab: layout, section heading, then one section per element of a stage card. */
	private function register_proposal_style_controls() {
		// Layout.
		$this->mwsb_prop_section_start( 'style_layout', __( 'Layout', 'mw-showband' ) );
		$this->mwsb_prop_layout_controls( 'layout', '.mwsb-prop-timeline' );
		$this->add_responsive_control(
			'layout_columns',
			array(
				'label'     => __( 'Stage Columns', 'mw-showband' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array( '' => __( 'Default', 'mw-showband' ), '1' => '1', '2' => '2', '3' => '3', '4' => '4' ),
				'selectors' => array( '{{WRAPPER}} .mwsb-prop-stages' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ),
			)
		);
		$this->mwsb_prop_slider( 'layout_gap', __( 'Gap Between Stages', 'mw-showband' ), '.mwsb-prop-stages', 'gap', array( 'px' => array( 'min' => 0, 'max' => 80 ) ) );
		$this->mwsb_prop_section_end();

		// Section heading (eyebrow / title / description / gaps).
		$this->mwsb_prop_section_head_controls( 'head' );

		// Stage card.
		$this->mwsb_prop_section_start( 'style_stage', __( 'Stage Card', 'mw-showband' ) );
		$this->mwsb_prop_box_controls( 'stage_box', '.mwsb-prop-stage', array( 'hover_bg' => true, 'padding' => false ) );
		$this->mwsb_prop_transition_control( 'stage_transition', '.mwsb-prop-stage' );
		$this->mwsb_prop_heading( 'stage_body_h', __( 'Card Body', 'mw-showband' ) );
		$this->mwsb_prop_dimensions( 'stage_body_padding', __( 'Padding', 'mw-showband' ), '.mwsb-prop-stage__body', 'padding' );
		$this->mwsb_prop_slider( 'stage_body_gap', __( 'Gap Between Text & Setup Box', 'mw-showband' ), '.mwsb-prop-stage__body', 'gap', array( 'px' => array( 'min' => 0, 'max' => 60 ) ) );
		$this->mwsb_prop_section_end();

		// Image.
		$this->mwsb_prop_section_start( 'style_image', __( 'Stage Image', 'mw-showband' ) );
		$this->mwsb_prop_image_controls( 'stage_img', '.mwsb-prop-stage__media', '.mwsb-prop-stage__media .mwsb-prop-stage__img', '.mwsb-prop-stage' );
		$this->mwsb_prop_section_end();

		// Stage number badge + time.
		$this->mwsb_prop_section_start( 'style_badge', __( 'Stage Number & Time', 'mw-showband' ) );
		$this->mwsb_prop_heading( 'stage_num_h', __( 'Stage Number Badge', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'stage_num', '.mwsb-prop-stage__num' );
		$this->mwsb_prop_box_controls( 'stage_num_box', '.mwsb-prop-stage__num' );
		$this->mwsb_prop_heading( 'stage_time_h', __( 'Time Range', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'stage_time', '.mwsb-prop-stage__time' );
		$this->mwsb_prop_section_end();

		// Title + description.
		$this->mwsb_prop_section_start( 'style_stage_text', __( 'Stage Title & Text', 'mw-showband' ) );
		$this->mwsb_prop_heading( 'stage_title_h', __( 'Title', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'stage_title', '.mwsb-prop-stage__title', array( 'spacing' => true ) );
		$this->mwsb_prop_heading( 'stage_text_h', __( 'Description', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'stage_text', '.mwsb-prop-stage__text' );
		$this->mwsb_prop_section_end();

		// Curated setup box.
		$this->mwsb_prop_section_start( 'style_setup', __( 'Curated Setup Box', 'mw-showband' ) );
		$this->mwsb_prop_box_controls( 'setup_box', '.mwsb-prop-stage__setup' );
		$this->mwsb_prop_slider( 'setup_gap', __( 'Gap Between Label & Value', 'mw-showband' ), '.mwsb-prop-stage__setup', 'gap', array( 'px' => array( 'min' => 0, 'max' => 40 ) ) );
		$this->mwsb_prop_heading( 'setup_label_h', __( 'Label', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'setup_label', '.mwsb-prop-stage__setup-label' );
		$this->mwsb_prop_heading( 'setup_value_h', __( 'Value', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'setup_value', '.mwsb-prop-stage__setup-value' );
		$this->mwsb_prop_heading( 'setup_icon_h', __( 'Icon', 'mw-showband' ) );
		$this->add_control( 'setup_icon_color', array( 'label' => __( 'Icon Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-stage__setup-value .mwsb-prop-icon' => 'color: {{VALUE}};' ) ) );
		$this->mwsb_prop_slider( 'setup_icon_size', __( 'Icon Size', 'mw-showband' ), '.mwsb-prop-stage__setup-value .mwsb-prop-icon', 'font-size', array( 'px' => array( 'min' => 8, 'max' => 48 ) ) );
		$this->mwsb_prop_section_end();

		// Visibility.
		$this->mwsb_prop_section_start( 'style_visibility', __( 'Show / Hide Elements', 'mw-showband' ) );
		$this->mwsb_prop_hide_switch( 'hide_stage_num', __( 'Hide Stage Number', 'mw-showband' ), '.mwsb-prop-stage__num' );
		$this->mwsb_prop_hide_switch( 'hide_stage_time', __( 'Hide Time Range', 'mw-showband' ), '.mwsb-prop-stage__time' );
		$this->mwsb_prop_hide_switch( 'hide_stage_text', __( 'Hide Stage Description', 'mw-showband' ), '.mwsb-prop-stage__text' );
		$this->mwsb_prop_hide_switch( 'hide_stage_setup', __( 'Hide Curated Setup Box', 'mw-showband' ), '.mwsb-prop-stage__setup' );
		$this->mwsb_prop_section_end();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$tag    = in_array( $s['title_tag'], array( 'h2', 'h3', 'div' ), true ) ? $s['title_tag'] : 'h2';
		$stages = ! empty( $s['stages'] ) ? $s['stages'] : array();
		$anchor = ! empty( $s['anchor_id'] ) ? $s['anchor_id'] : 'mwsb-event-timeline';
		?>
		<section id="<?php echo esc_attr( $anchor ); ?>" class="mwsb-widget mwsb-section mwsb-prop mwsb-prop-timeline">
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

				<?php if ( $stages ) : ?>
					<div class="mwsb-prop-stages">
						<?php foreach ( $stages as $stage ) : ?>
							<div class="mwsb-prop-stage">
								<?php if ( ! empty( $stage['image']['url'] ) ) : ?>
									<div class="mwsb-prop-stage__media">
										<img class="mwsb-prop-stage__img" src="<?php echo esc_url( $stage['image']['url'] ); ?>" alt="<?php echo esc_attr( $stage['title'] ); ?>" />
										<div class="mwsb-prop-stage__scrim"></div>
										<span class="mwsb-prop-stage__num"><?php echo esc_html( $stage['stage_num'] ); ?></span>
										<span class="mwsb-prop-stage__time"><?php echo esc_html( $stage['time_range'] ); ?></span>
									</div>
								<?php endif; ?>
								<div class="mwsb-prop-stage__body">
									<div>
										<h3 class="mwsb-prop-stage__title"><?php echo esc_html( $stage['title'] ); ?></h3>
										<p class="mwsb-prop-stage__text"><?php echo esc_html( $stage['text'] ); ?></p>
									</div>
									<div class="mwsb-prop-stage__setup">
										<span class="mwsb-prop-stage__setup-label"><?php esc_html_e( 'CURATED SETUP', 'mw-showband' ); ?></span>
										<span class="mwsb-prop-stage__setup-value">
											<?php Icons_Manager::render_icon( $stage['setup_icon'], array( 'aria-hidden' => 'true', 'class' => 'mwsb-prop-icon' ) ); ?>
											<?php echo esc_html( $stage['setup_text'] ); ?>
										</span>
									</div>
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
