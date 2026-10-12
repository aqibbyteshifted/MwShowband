<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * MW Showband — "Contact" page location section: header + a real embedded
 * Google Maps iframe with a floating glassmorphic office details panel.
 * Author: Aqib Dev
 */
class MWSB_Location_Map_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-location-map';
	}

	public function get_title() {
		return __( 'MW Location Map', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	protected function register_controls() {

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'header_section',
			array(
				'label' => __( 'Header', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'eyebrow_text', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '// 03 / MW SHOWBAND // GLOBAL HEADQUARTERS' ) );
		$this->add_control( 'heading', array( 'label' => __( 'Heading', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'FIND US.' ) );
		$this->add_control( 'coordinates_text', array( 'label' => __( 'Coordinates', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'COORDINATES: 51.5074° N, 0.1278° W' ) );
		$this->add_control( 'location_text', array( 'label' => __( 'Location Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MAYFAIR, LONDON' ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'map_section',
			array(
				'label' => __( 'Map', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'map_embed_url',
			array(
				'label'       => __( 'Google Maps Embed URL', 'mw-showband' ),
				'type'        => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'default'     => 'https://www.google.com/maps?q=17+Berkeley+Street,+Mayfair,+London&output=embed',
				'description' => __( 'From Google Maps: Share → Embed a map → copy the src="..." URL.', 'mw-showband' ),
			)
		);

		$this->add_responsive_control(
			'map_height',
			array(
				'label'     => __( 'Map Height', 'mw-showband' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 240, 'max' => 900 ) ),
				'default'   => array( 'unit' => 'px', 'size' => 460 ),
				'selectors' => array(
					'{{WRAPPER}} .mwsb-location-map__frame-wrap' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'panel_section',
			array(
				'label' => __( 'Floating Panel', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'show_panel', array( 'label' => __( 'Show Panel', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'panel_label', array( 'label' => __( 'Status Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '[ LONDON OFFICE // OPERATIONAL ]', 'condition' => array( 'show_panel' => 'yes' ) ) );
		$this->add_control( 'panel_title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MW SHOWBAND GLOBAL HQ', 'condition' => array( 'show_panel' => 'yes' ) ) );
		$this->add_control( 'panel_address', array( 'label' => __( 'Address', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => "17 Berkeley Street, Mayfair\nLondon W1J 8EA, United Kingdom", 'condition' => array( 'show_panel' => 'yes' ) ) );
		$this->add_control( 'panel_hubs', array( 'label' => __( 'Hubs', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'London · Marbella · Monaco · Tel Aviv', 'condition' => array( 'show_panel' => 'yes' ) ) );
		$this->add_control( 'panel_link', array( 'label' => __( 'Directions Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => 'https://maps.google.com/?q=17+Berkeley+Street+Mayfair+London', 'is_external' => 'true' ), 'condition' => array( 'show_panel' => 'yes' ) ) );
		$this->add_control( 'panel_link_text', array( 'label' => __( 'Directions Link Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'GET DIRECTIONS', 'condition' => array( 'show_panel' => 'yes' ) ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'eyebrow' => '.mwsb-location-map__eyebrow', 'heading' => '.mwsb-location-map__heading', 'head' => '.mwsb-location-map__header' ) );
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$lines = array_filter( array_map( 'trim', explode( "\n", (string) $s['panel_address'] ) ) );
		?>
		<section class="mwsb-widget mwsb-section mwsb-location-map">
			<div class="mwsb-container mwsb-location-map__header">
				<div>
					<?php if ( ! empty( $s['eyebrow_text'] ) ) : ?><span class="mwsb-location-map__eyebrow"><?php echo esc_html( $s['eyebrow_text'] ); ?></span><?php endif; ?>
					<h2 class="mwsb-heading-display mwsb-heading-display--xl mwsb-location-map__heading"><?php echo esc_html( $s['heading'] ); ?></h2>
				</div>
				<div class="mwsb-location-map__coords">
					<?php if ( ! empty( $s['coordinates_text'] ) ) : ?>
						<span class="mwsb-location-map__coords-item">
							<span class="mwsb-location-map__coords-dot"></span>
							<?php echo esc_html( $s['coordinates_text'] ); ?>
						</span>
					<?php endif; ?>
					<?php if ( ! empty( $s['location_text'] ) ) : ?>
						<span class="mwsb-location-map__coords-sep">&middot;</span>
						<span><?php echo esc_html( $s['location_text'] ); ?></span>
					<?php endif; ?>
				</div>
			</div>

			<div class="mwsb-container">
				<div class="mwsb-location-map__frame-wrap">
					<?php if ( ! empty( $s['map_embed_url'] ) ) : ?>
						<iframe
							class="mwsb-location-map__frame"
							src="<?php echo esc_url( $s['map_embed_url'] ); ?>"
							loading="lazy"
							referrerpolicy="no-referrer-when-downgrade"
							allowfullscreen
						></iframe>
					<?php endif; ?>

					<?php if ( 'yes' === $s['show_panel'] ) : ?>
						<div class="mwsb-location-map__panel">
							<div class="mwsb-location-map__panel-row">
								<?php if ( ! empty( $s['panel_label'] ) ) : ?><span class="mwsb-location-map__panel-label"><?php echo esc_html( $s['panel_label'] ); ?></span><?php endif; ?>
								<span class="mwsb-location-map__panel-dot"></span>
							</div>
							<?php if ( ! empty( $s['panel_title'] ) ) : ?><h4 class="mwsb-location-map__panel-title"><?php echo esc_html( $s['panel_title'] ); ?></h4><?php endif; ?>
							<?php if ( ! empty( $lines ) ) : ?>
								<p class="mwsb-location-map__panel-address">
									<?php echo esc_html( implode( ', ', $lines ) ); ?>
								</p>
							<?php endif; ?>
							<div class="mwsb-location-map__panel-footer">
								<?php if ( ! empty( $s['panel_hubs'] ) ) : ?>
									<span class="mwsb-location-map__panel-hubs"><strong>HUBS:</strong> <?php echo esc_html( $s['panel_hubs'] ); ?></span>
								<?php endif; ?>
								<?php if ( ! empty( $s['panel_link_text'] ) ) : ?>
									<a href="<?php echo esc_url( $s['panel_link']['url'] ); ?>" class="mwsb-location-map__panel-link" <?php echo ! empty( $s['panel_link']['is_external'] ) ? 'target="_blank" rel="noopener"' : ''; ?>>
										<?php echo esc_html( $s['panel_link_text'] ); ?> &#8599;
									</a>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
