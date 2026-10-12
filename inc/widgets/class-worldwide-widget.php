<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

/**
 * MW Showband — "Wherever The Party Is" global city grid.
 * Author: Aqib Dev
 */
class MWSB_Worldwide_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-worldwide';
	}

	public function get_title() {
		return __( 'MW Worldwide', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
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

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'GLOBAL DEPLOYMENT' ) );
		$this->add_control( 'title_line1', array( 'label' => __( 'Title Line 1', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WHEREVER THE PARTY IS.' ) );
		$this->add_control( 'title_line2', array( 'label' => __( 'Title Line 2 (gradient)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => "WE'LL BE THERE." ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Dedicated touring production, flight-ready crew, and seamless global backline coordination.' ) );
		$this->add_control( 'banner_text', array( 'label' => __( 'Logistics Banner Text', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Zero logistical burden: We supply touring sound engineers, backline tech, and audio-visual liaison.' ) );
		$this->add_control( 'banner_badge', array( 'label' => __( 'Logistics Banner Badge', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CARNET & VISA CERTIFIED' ) );

		$this->end_controls_section();

		$this->start_controls_section(
			'cities_section',
			array(
				'label' => __( 'Cities', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'city', array( 'label' => __( 'City', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'LONDON' ) );
		$repeater->add_control( 'tag', array( 'label' => __( 'Tag', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'HEADQUARTERS' ) );

		$this->add_control(
			'cities',
			array(
				'label'       => __( 'City Items', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'city' => 'LONDON', 'tag' => 'HEADQUARTERS' ),
					array( 'city' => 'MARBELLA', 'tag' => 'MEDITERRANEAN HUB' ),
					array( 'city' => 'TEL AVIV', 'tag' => 'MIDDLE EAST HQ' ),
					array( 'city' => 'MONACO', 'tag' => 'RIVIERA RESIDENCY' ),
					array( 'city' => 'DUBAI', 'tag' => 'GULF CIRCUIT' ),
					array( 'city' => 'NEW YORK', 'tag' => 'US TOUR ENSEMBLE' ),
				),
				'title_field' => '{{{ city }}}',
			)
		);

		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'head' => '.mwsb-worldwide__head', 'desc' => '.mwsb-worldwide__desc', 'content_gap' => '.mwsb-worldwide__grid' ) );

		$this->start_controls_section(
			'mwsb_worldwide_style',
			array(
				'label' => __( 'Header & Cities', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'worldwide_eyebrow_color',
			array(
				'label'     => __( 'Eyebrow Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-tertiary)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-worldwide__eyebrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'worldwide_city_typography',
				'label'     => __( 'City Name Typography', 'mw-showband' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .mwsb-worldwide__city h4',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="mwsb-widget mwsb-section mwsb-worldwide">
			<div class="mwsb-container">
				<div class="mwsb-worldwide__head">
					<span class="mwsb-eyebrow mwsb-worldwide__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
					<h2 class="mwsb-heading-display mwsb-heading-display--xl">
						<?php echo esc_html( $s['title_line1'] ); ?><br />
						<span class="mwsb-gradient-text"><?php echo esc_html( $s['title_line2'] ); ?></span>
					</h2>
					<p class="mwsb-worldwide__desc"><?php echo esc_html( $s['description'] ); ?></p>
				</div>

				<div class="mwsb-worldwide__panel">
					<div class="mwsb-worldwide__grid">
						<?php foreach ( $s['cities'] as $c ) : ?>
							<div class="mwsb-worldwide__city">
								<span class="mwsb-worldwide__icon">&#9992;</span>
								<h4><?php echo esc_html( $c['city'] ); ?></h4>
								<span><?php echo esc_html( $c['tag'] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="mwsb-worldwide__banner">
						<div class="mwsb-worldwide__banner-text">
							<span class="mwsb-worldwide__banner-icon">&#127760;</span>
							<span><?php echo esc_html( $s['banner_text'] ); ?></span>
						</div>
						<span class="mwsb-pill"><?php echo esc_html( $s['banner_badge'] ); ?></span>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
