<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

/**
 * MW Showband — "2030 Futuristic Spectacle" proposal page: cinematic hero
 * with status pills, monumental headline, a personalized info strip and a
 * scroll-to-discover link. One-off bespoke skin for the sales proposal
 * page only — see assets/css/proposal.css.
 * Author: Aqib Dev
 */
class MWSB_Proposal_Hero_Widget extends Widget_Base {

	use MWSB_Section_Controls;
	use MWSB_Proposal_Style_Controls;

	public function get_name() {
		return 'mwsb-proposal-hero';
	}

	public function get_title() {
		return __( 'MW Proposal — Hero', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-slides';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	public function get_style_depends() {
		return array( 'mwsb-tokens', 'mwsb-proposal' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'content_status', array( 'label' => __( 'Status Pills', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'live_text', array( 'label' => __( 'Live Pill Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'PRIVATE EVENT PROPOSAL // MW SHOWBAND' ) );
		$this->add_control( 'status_text', array( 'label' => __( 'Status Pill Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '[ STATUS: CONFIDENTIAL // EXCLUSIVELY PREPARED ]' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'content_head', array( 'label' => __( 'Headline', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'BESPOKE ENTERTAINMENT CURATION' ) );
		$this->add_control( 'title_tag', array( 'label' => __( 'Title HTML Tag', 'mw-showband' ), 'type' => Controls_Manager::SELECT, 'default' => 'h1', 'options' => array( 'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'div' => 'div' ) ) );
		$this->add_control( 'title_white', array( 'label' => __( 'Title — White Line', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => "LET'S MAKE THIS" ) );
		$this->add_control( 'title_gradient', array( 'label' => __( 'Title — Gradient Line', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'NIGHT UNFORGETTABLE.' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'content_strip', array( 'label' => __( 'Info Strip', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'show_strip', array( 'label' => __( 'Show Info Strip', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$repeater = new Repeater();
		$repeater->add_control( 'icon', array( 'label' => __( 'Icon', 'mw-showband' ), 'type' => Controls_Manager::ICONS, 'default' => array( 'value' => 'fas fa-star', 'library' => 'fa-solid' ) ) );
		$repeater->add_control( 'label', array( 'label' => __( 'Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'PREPARED EXCLUSIVELY FOR' ) );
		$repeater->add_control( 'value', array( 'label' => __( 'Value', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Paul & Victoria' ) );
		$this->add_control(
			'strip_items',
			array(
				'label'       => __( 'Items', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'icon' => array( 'value' => 'fas fa-star', 'library' => 'fa-solid' ), 'label' => 'PREPARED EXCLUSIVELY FOR', 'value' => 'Paul & Victoria' ),
					array( 'icon' => array( 'value' => 'fas fa-champagne-glasses', 'library' => 'fa-solid' ), 'label' => 'OCCASION', 'value' => 'Private Celebration Gala' ),
					array( 'icon' => array( 'value' => 'fas fa-location-dot', 'library' => 'fa-solid' ), 'label' => 'SANCTUARY VENUE', 'value' => 'Aynhoe Park, Oxfordshire' ),
					array( 'icon' => array( 'value' => 'fas fa-calendar-check', 'library' => 'fa-solid' ), 'label' => 'RESERVED TIMELINE', 'value' => 'Saturday, 18th July 2026' ),
				),
				'title_field' => '{{{ label }}}',
				'condition'   => array( 'show_strip' => 'yes' ),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'content_actions', array( 'label' => __( 'Buttons & Scroll Link', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'primary_text', array( 'label' => __( 'Primary Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'VIEW PACKAGES' ) );
		$this->add_control( 'primary_link', array( 'label' => __( 'Primary Button Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#packages' ) ) );
		$this->add_control( 'ghost_text', array( 'label' => __( 'Secondary Button Text (optional)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CONTACT US' ) );
		$this->add_control( 'ghost_link', array( 'label' => __( 'Secondary Button Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '' ), 'condition' => array( 'ghost_text!' => '' ) ) );
		$this->add_control( 'scroll_text', array( 'label' => __( 'Scroll Link Text (optional)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'SCROLL TO DISCOVER THE PROPOSAL' ) );
		$this->add_control( 'scroll_target', array( 'label' => __( 'Scroll Link Target', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '#mwsb-event-timeline', 'condition' => array( 'scroll_text!' => '' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'content_bg', array( 'label' => __( 'Background Image (optional)', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'bg_image', array( 'label' => __( 'Background Image', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => '' ) ) );
		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_proposal_style_controls();
	}

	/** Style tab: layout, then one section per element (see trait-proposal-style-controls.php). */
	private function register_proposal_style_controls() {
		// Layout.
		$this->mwsb_prop_section_start( 'style_layout', __( 'Layout', 'mw-showband' ) );
		$this->mwsb_prop_slider( 'layout_min_height', __( 'Min Height', 'mw-showband' ), '.mwsb-prop-hero', 'min-height', array( 'px' => array( 'min' => 0, 'max' => 1200 ), 'vh' => array( 'min' => 0, 'max' => 100 ) ), array( 'px', 'vh' ) );
		$this->mwsb_prop_slider( 'layout_max_width', __( 'Content Max Width', 'mw-showband' ), '.mwsb-prop .mwsb-container', 'max-width', array( 'px' => array( 'min' => 600, 'max' => 2000 ), '%' => array( 'min' => 40, 'max' => 100 ) ), array( 'px', '%' ) );
		$this->mwsb_prop_slider( 'layout_gap', __( 'Gap Between Blocks', 'mw-showband' ), '.mwsb-prop-hero__inner', 'gap', array( 'px' => array( 'min' => 0, 'max' => 120 ) ) );
		$this->mwsb_prop_slider( 'layout_head_width', __( 'Headline Max Width', 'mw-showband' ), '.mwsb-prop-hero__head', 'max-width', array( 'px' => array( 'min' => 300, 'max' => 1600 ), '%' => array( 'min' => 30, 'max' => 100 ) ), array( 'px', '%' ) );
		$this->add_responsive_control(
			'layout_strip_columns',
			array(
				'label'     => __( 'Info Strip Columns', 'mw-showband' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array( '' => __( 'Default', 'mw-showband' ), '1' => '1', '2' => '2', '3' => '3', '4' => '4' ),
				'selectors' => array( '{{WRAPPER}} .mwsb-prop-strip__grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ),
				'condition' => array( 'show_strip' => 'yes' ),
			)
		);
		$this->mwsb_prop_section_end();

		// Background (image, overlay, glows). Slider defaults mirror proposal.css so the look is unchanged until edited.
		$this->mwsb_prop_section_start( 'style_background', __( 'Background & Effects', 'mw-showband' ) );
		$this->add_control(
			'bg_image_opacity',
			array(
				'label'     => __( 'Image Opacity', 'mw-showband' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 1, 'step' => 0.05 ) ),
				'default'   => array( 'size' => 0.3 ),
				'selectors' => array( '{{WRAPPER}} .mwsb-prop-hero__bg' => 'opacity: {{SIZE}};' ),
				'condition' => array( 'bg_image[url]!' => '' ),
			)
		);
		$this->add_control(
			'bg_image_grayscale',
			array(
				'label'     => __( 'Image Grayscale', 'mw-showband' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 1, 'step' => 0.05 ) ),
				'default'   => array( 'size' => 1 ),
				'selectors' => array( '{{WRAPPER}} .mwsb-prop-hero__bg' => 'filter: grayscale({{SIZE}});' ),
				'condition' => array( 'bg_image[url]!' => '' ),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'bg_overlay',
				'label'    => __( 'Overlay Gradient', 'mw-showband' ),
				'types'    => array( 'gradient' ),
				'selector' => '{{WRAPPER}} .mwsb-prop-hero__scrim',
			)
		);
		$this->mwsb_prop_heading( 'bg_glow_heading', __( 'Glow Orbs', 'mw-showband' ) );
		$this->add_control( 'glow_a_color', array( 'label' => __( 'Glow A Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-hero__glow-a' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'glow_b_color', array( 'label' => __( 'Glow B Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-hero__glow-b' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'glows_hide', array( 'label' => __( 'Hide Glow Orbs', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'none', 'selectors' => array( '{{WRAPPER}} .mwsb-prop-hero__glow-a, {{WRAPPER}} .mwsb-prop-hero__glow-b' => 'display: {{VALUE}};' ) ) );
		$this->mwsb_prop_section_end();

		// Status pills.
		$this->mwsb_prop_section_start( 'style_pills', __( 'Status Pills', 'mw-showband' ) );
		$this->mwsb_prop_heading( 'pill_live_heading', __( 'Live Pill', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'pill_live', '.mwsb-prop-pill--live .mwsb-prop-pill__text' );
		$this->add_control( 'pill_live_dot', array( 'label' => __( 'Dot Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-pill__dot' => 'background-color: {{VALUE}};' ) ) );
		$this->mwsb_prop_box_controls( 'pill_live_box', '.mwsb-prop-pill--live' );
		$this->mwsb_prop_heading( 'pill_muted_heading', __( 'Status Pill', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'pill_muted', '.mwsb-prop-pill--muted .mwsb-prop-pill__text' );
		$this->mwsb_prop_box_controls( 'pill_muted_box', '.mwsb-prop-pill--muted' );
		$this->mwsb_prop_section_end();

		// Eyebrow.
		$this->mwsb_prop_section_start( 'style_eyebrow', __( 'Eyebrow', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'eyebrow', '.mwsb-prop-hero__head .mwsb-prop-eyebrow', array( 'spacing' => true ) );
		$this->mwsb_prop_section_end();

		// Title.
		$this->mwsb_prop_section_start( 'style_title', __( 'Title', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'title', '.mwsb-prop-heading--hero', array( 'spacing' => true ) );
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'title_gradient_bg',
				'label'    => __( 'Gradient Line Colors', 'mw-showband' ),
				'types'    => array( 'gradient' ),
				'selector' => '{{WRAPPER}} .mwsb-prop-heading--hero .mwsb-prop-gradient-text',
			)
		);
		$this->mwsb_prop_section_end();

		// Info strip.
		$this->mwsb_prop_section_start( 'style_strip', __( 'Info Strip', 'mw-showband' ), array( 'show_strip' => 'yes' ) );
		$this->mwsb_prop_box_controls( 'strip_box', '.mwsb-prop-strip' );
		$this->mwsb_prop_slider( 'strip_gap', __( 'Gap Between Items', 'mw-showband' ), '.mwsb-prop-strip__grid', 'gap', array( 'px' => array( 'min' => 0, 'max' => 80 ) ) );
		$this->mwsb_prop_heading( 'strip_label_heading', __( 'Label', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'strip_label', '.mwsb-prop-strip__label' );
		$this->mwsb_prop_heading( 'strip_value_heading', __( 'Value', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'strip_value', '.mwsb-prop-strip__value' );
		$this->mwsb_prop_heading( 'strip_icon_heading', __( 'Icon', 'mw-showband' ) );
		$this->add_control( 'strip_icon_color', array( 'label' => __( 'Icon Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-strip__value .mwsb-prop-icon' => 'color: {{VALUE}};' ) ) );
		$this->mwsb_prop_slider( 'strip_icon_size', __( 'Icon Size', 'mw-showband' ), '.mwsb-prop-strip__value .mwsb-prop-icon', 'font-size', array( 'px' => array( 'min' => 8, 'max' => 64 ) ) );
		$this->mwsb_prop_section_end();

		// Buttons.
		$this->mwsb_prop_section_start( 'style_buttons', __( 'Buttons', 'mw-showband' ) );
		$this->mwsb_prop_slider( 'buttons_gap', __( 'Gap Between Buttons', 'mw-showband' ), '.mwsb-prop-hero__actions', 'gap', array( 'px' => array( 'min' => 0, 'max' => 60 ) ) );
		$this->mwsb_prop_heading( 'btn_primary_heading', __( 'Primary Button', 'mw-showband' ) );
		$this->mwsb_prop_button_controls( 'btn_primary', '.mwsb-prop-btn--primary' );
		$this->mwsb_prop_heading( 'btn_ghost_heading', __( 'Secondary Button', 'mw-showband' ) );
		$this->mwsb_prop_button_controls( 'btn_ghost', '.mwsb-prop-btn--ghost' );
		$this->mwsb_prop_section_end();

		// Scroll link.
		$this->mwsb_prop_section_start( 'style_scroll', __( 'Scroll Link', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'scroll', '.mwsb-prop-hero__scroll-link', array( 'hover' => true ) );
		$this->mwsb_prop_box_controls( 'scroll_box', '.mwsb-prop-hero__scroll-link', array( 'hover_bg' => true ) );
		$this->mwsb_prop_section_end();

		// Elementor-style colour/gradient overlay + image filters/position.
		$this->register_bg_overlay_controls( '.mwsb-prop-hero__bg' );
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$tag   = in_array( $s['title_tag'], array( 'h1', 'h2', 'h3', 'div' ), true ) ? $s['title_tag'] : 'h1';
		$items = ( 'yes' === $s['show_strip'] && ! empty( $s['strip_items'] ) ) ? $s['strip_items'] : array();
		?>
		<section class="mwsb-widget mwsb-section mwsb-prop mwsb-prop-hero">
			<?php if ( ! empty( $s['bg_image']['url'] ) ) : ?>
				<div class="mwsb-prop-hero__bg" style="background-image:url('<?php echo esc_url( $s['bg_image']['url'] ); ?>');"></div>
			<?php endif; ?>
			<div class="mwsb-prop-hero__scrim"></div>
			<div class="mwsb-bg-overlay"></div>
			<div class="mwsb-prop-hero__glow-a"></div>
			<div class="mwsb-prop-hero__glow-b"></div>
			<div class="mwsb-container mwsb-prop-hero__inner">
				<div class="mwsb-prop-hero__status-row">
					<?php if ( ! empty( $s['live_text'] ) ) : ?>
						<span class="mwsb-prop-pill mwsb-prop-pill--live">
							<span class="mwsb-prop-pill__dot"></span>
							<span class="mwsb-prop-pill__text"><?php echo esc_html( $s['live_text'] ); ?></span>
						</span>
					<?php endif; ?>
					<?php if ( ! empty( $s['status_text'] ) ) : ?>
						<span class="mwsb-prop-pill mwsb-prop-pill--muted">
							<span class="mwsb-prop-pill__text"><?php echo esc_html( $s['status_text'] ); ?></span>
						</span>
					<?php endif; ?>
				</div>

				<div class="mwsb-prop-hero__head">
					<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
						<span class="mwsb-prop-eyebrow mwsb-prop-eyebrow--wide"><?php echo esc_html( $s['eyebrow'] ); ?></span>
					<?php endif; ?>
					<<?php echo esc_html( $tag ); ?> class="mwsb-prop-heading mwsb-prop-heading--hero">
						<?php echo esc_html( $s['title_white'] ); ?><br />
						<span class="mwsb-prop-gradient-text"><?php echo esc_html( $s['title_gradient'] ); ?></span>
					</<?php echo esc_html( $tag ); ?>>
				</div>

				<?php if ( $items ) : ?>
					<div class="mwsb-prop-strip">
						<div class="mwsb-prop-strip__grid">
							<?php foreach ( $items as $item ) : ?>
								<div class="mwsb-prop-strip__item">
									<span class="mwsb-prop-strip__label"><?php echo esc_html( $item['label'] ); ?></span>
									<span class="mwsb-prop-strip__value">
										<?php Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true', 'class' => 'mwsb-prop-icon' ) ); ?>
										<?php echo esc_html( $item['value'] ); ?>
									</span>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $s['primary_text'] ) || ! empty( $s['ghost_text'] ) ) : ?>
					<div class="mwsb-prop-hero__actions">
						<?php if ( ! empty( $s['primary_text'] ) ) : ?>
							<a class="mwsb-prop-btn mwsb-prop-btn--primary" href="<?php echo esc_url( $s['primary_link']['url'] ); ?>"><?php echo esc_html( $s['primary_text'] ); ?></a>
						<?php endif; ?>
						<?php if ( ! empty( $s['ghost_text'] ) ) : ?>
							<a class="mwsb-prop-btn mwsb-prop-btn--ghost" href="<?php echo esc_url( $s['ghost_link']['url'] ); ?>"><?php echo esc_html( $s['ghost_text'] ); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $s['scroll_text'] ) ) : ?>
					<div class="mwsb-prop-hero__scroll">
						<a class="mwsb-prop-hero__scroll-link" href="<?php echo esc_attr( $s['scroll_target'] ); ?>">
							<span><?php echo esc_html( $s['scroll_text'] ); ?></span>
							<?php Icons_Manager::render_icon( array( 'value' => 'fas fa-arrow-down', 'library' => 'fa-solid' ), array( 'aria-hidden' => 'true', 'class' => 'mwsb-prop-icon' ) ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
