<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;

/**
 * MW Showband — "Contact" page hero: status pill row, headline (with a
 * gradient accent line), description, and a bottom metadata chip bar.
 * Author: Aqib Dev
 */
class MWSB_Contact_Hero_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-contact-hero';
	}

	public function get_title() {
		return __( 'MW Contact Hero', 'mw-showband' );
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
			'pills_section',
			array(
				'label' => __( 'Status Pills', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$pills_repeater = new Repeater();
		$pills_repeater->add_control( 'text', array( 'label' => __( 'Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CONTACT // MW SHOWBAND' ) );
		$pills_repeater->add_control( 'show_dot', array( 'label' => __( 'Show Pulse Dot', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => '' ) );

		$this->add_control(
			'pills',
			array(
				'label'       => __( 'Pills', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $pills_repeater->get_controls(),
				'default'     => array(
					array( 'text' => 'CONTACT // MW SHOWBAND', 'show_dot' => 'yes' ),
					array( 'text' => 'STATUS: 2025/2026 INTAKE OPEN', 'show_dot' => '' ),
					array( 'text' => 'RESPONSE LATENCY: < 2 HOURS', 'show_dot' => '' ),
				),
				'title_field' => '{{{ text }}}',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'headline_section',
			array(
				'label' => __( 'Headline', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'heading_line1', array( 'label' => __( 'Heading Line 1', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => "LET'S BUILD" ) );
		$this->add_control( 'heading_line2', array( 'label' => __( 'Heading Line 2 (gradient)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'YOUR NIGHT.' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => "Tell us what you're planning. We'll take it from there with unmatched acoustic mastery and stadium-scale precision." ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'meta_section',
			array(
				'label' => __( 'Metadata Bar', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$meta_repeater = new Repeater();
		$meta_repeater->add_control( 'text', array( 'label' => __( 'Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'EVENTS' ) );
		$meta_repeater->add_control( 'highlight', array( 'label' => __( 'Highlight (accent color)', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => '' ) );

		$this->add_control(
			'meta_items',
			array(
				'label'       => __( 'Metadata Chips', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $meta_repeater->get_controls(),
				'default'     => array(
					array( 'text' => 'EVENTS', 'highlight' => '' ),
					array( 'text' => 'WEDDINGS', 'highlight' => '' ),
					array( 'text' => 'PRIVATE ESTATES', 'highlight' => '' ),
					array( 'text' => 'CORPORATE', 'highlight' => '' ),
					array( 'text' => 'WORLDWIDE DEPLOYMENT', 'highlight' => 'yes' ),
				),
				'title_field' => '{{{ text }}}',
			)
		);

		$this->add_control( 'scroll_text', array( 'label' => __( 'Scroll Cue Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'SCROLL TO ENQUIRY' ) );
		$this->add_control( 'scroll_link', array( 'label' => __( 'Scroll Cue Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#enquiry-suite' ) ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->register_section_style_controls();
		$this->register_hero_layout_controls( '.mwsb-contact-hero' );
		$this->register_bg_overlay_controls( '.mwsb-contact-hero__bg' );
		$this->register_text_style_controls( array( 'eyebrow' => '', 'heading' => '.mwsb-contact-hero__heading', 'desc' => '.mwsb-contact-hero__desc', 'flow' => '.mwsb-contact-hero__headline-block', 'content_gap' => '.mwsb-contact-hero__content' ) );

		$this->start_controls_section(
			'mwsb_ch_overlay_style',
			array(
				'label' => __( 'Default Gradient Overlay', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_opacity',
			array(
				'label'     => __( 'Darkness', 'mw-showband' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
				'default'   => array( 'unit' => 'px', 'size' => 80 ),
				'selectors' => array(
					'{{WRAPPER}} .mwsb-contact-hero__scrim' => 'opacity: calc( {{SIZE}} / 100 );',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_ch_heading_style',
			array(
				'label' => __( 'Heading', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .mwsb-contact-hero__heading',
				'label'    => __( 'Heading Typography', 'mw-showband' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'selector' => '{{WRAPPER}} .mwsb-contact-hero__desc',
				'label'    => __( 'Description Typography', 'mw-showband' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s  = $this->get_settings_for_display();
		$bg = ! empty( $s['background_image']['url'] ) ? $s['background_image']['url'] : '';
		?>
		<section class="mwsb-widget mwsb-section mwsb-contact-hero">
			<div class="mwsb-contact-hero__bg" style="<?php echo $bg ? 'background-image:url(' . esc_url( $bg ) . ');' : ''; ?>"></div>
			<div class="mwsb-contact-hero__scrim"></div>
			<div class="mwsb-bg-overlay"></div>
			<div class="mwsb-contact-hero__bloom mwsb-contact-hero__bloom--a"></div>
			<div class="mwsb-contact-hero__bloom mwsb-contact-hero__bloom--b"></div>

			<div class="mwsb-container mwsb-contact-hero__content">

				<?php if ( ! empty( $s['pills'] ) ) : ?>
					<div class="mwsb-contact-hero__pills">
						<?php foreach ( $s['pills'] as $pill ) : ?>
							<span class="mwsb-contact-hero__pill">
								<?php if ( 'yes' === $pill['show_dot'] ) : ?><span class="mwsb-pulse-dot"></span><?php endif; ?>
								<?php echo esc_html( $pill['text'] ); ?>
							</span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="mwsb-contact-hero__headline-block">
					<h1 class="mwsb-heading-display mwsb-contact-hero__heading">
						<?php echo esc_html( $s['heading_line1'] ); ?> <span class="mwsb-gradient-text"><?php echo esc_html( $s['heading_line2'] ); ?></span>
					</h1>
					<?php if ( ! empty( $s['description'] ) ) : ?>
						<p class="mwsb-contact-hero__desc"><?php echo esc_html( $s['description'] ); ?></p>
					<?php endif; ?>
				</div>

				<div class="mwsb-contact-hero__meta-row">
					<?php if ( ! empty( $s['meta_items'] ) ) : ?>
						<div class="mwsb-contact-hero__meta">
							<?php foreach ( $s['meta_items'] as $i => $item ) : ?>
								<?php if ( $i > 0 ) : ?><span class="mwsb-contact-hero__meta-sep">&middot;</span><?php endif; ?>
								<span class="mwsb-contact-hero__meta-item <?php echo 'yes' === $item['highlight'] ? 'is-highlight' : ''; ?>"><?php echo esc_html( $item['text'] ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $s['scroll_text'] ) ) : ?>
						<a href="<?php echo esc_url( $s['scroll_link']['url'] ); ?>" class="mwsb-contact-hero__scroll">
							<span><?php echo esc_html( $s['scroll_text'] ); ?></span>
							<span class="mwsb-contact-hero__scroll-arrow">&#8595;</span>
						</a>
					<?php endif; ?>
				</div>

			</div>
		</section>
		<?php
	}
}
