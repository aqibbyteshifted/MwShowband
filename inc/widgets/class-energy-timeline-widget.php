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
 * MW Showband — "Choose Your Energy" interactive timeline.
 * Author: Aqib Dev
 */
class MWSB_Energy_Timeline_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-energy-timeline';
	}

	public function get_title() {
		return __( 'MW Energy Timeline', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-tabs';
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

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'PROGRAM CURATION' ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CHOOSE YOUR ENERGY' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'A night engineered in 5 dynamic movements. Each block calibrated to build peak dance floor momentum.' ) );
		$this->add_control(
			'layout_style',
			array(
				'label'   => __( 'Layout', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'tabs',
				'options' => array(
					'tabs'  => __( 'Interactive Tabs (click to switch)', 'mw-showband' ),
					'cards' => __( 'Card Grid (all stages shown at once)', 'mw-showband' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'stages_section',
			array(
				'label' => __( 'Stages', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'stage_number', array( 'label' => __( 'Stage No.', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '01. WELCOME' ) );
		$repeater->add_control( 'stage_time', array( 'label' => __( 'Time Slot (card layout only)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '' ) );
		$repeater->add_control( 'tab_title', array( 'label' => __( 'Tab Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Acoustic Lounge' ) );
		$repeater->add_control( 'tab_subtitle', array( 'label' => __( 'Tab Subtitle', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Chilled neo-soul vibes' ) );
		$repeater->add_control( 'detail_title', array( 'label' => __( 'Detail Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'ACOUSTIC LOUNGE & CHAMPAGNE JAZZ' ) );
		$repeater->add_control( 'detail_desc', array( 'label' => __( 'Detail Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Warm, understated sophistication during early guest arrival and high-end cocktail hours.' ) );
		$repeater->add_control( 'ensemble', array( 'label' => __( 'Ensemble', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '3-4 PIECE' ) );
		$repeater->add_control( 'bpm', array( 'label' => __( 'Tempo', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '85-105 BPM' ) );
		$repeater->add_control( 'energy_level', array( 'label' => __( 'Energy', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WARM & INTIMATE' ) );
		$repeater->add_control( 'image', array( 'label' => __( 'Image', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => Utils::get_placeholder_image_src() ) ) );

		$this->add_control(
			'stages',
			array(
				'label'       => __( 'Timeline Stages', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'stage_number' => '01. WELCOME', 'tab_title' => 'Acoustic Lounge', 'tab_subtitle' => 'Chilled neo-soul vibes', 'detail_title' => 'ACOUSTIC LOUNGE & CHAMPAGNE JAZZ', 'ensemble' => '3-4 PIECE', 'bpm' => '85-105 BPM', 'energy_level' => 'WARM & INTIMATE' ),
					array( 'stage_number' => '02. DINNER', 'tab_title' => 'Roaming Brass', 'tab_subtitle' => 'Duos & table solos', 'detail_title' => 'CONTEMPORARY ROAMING BRASS & DINNER', 'ensemble' => 'DUO / ROAMING', 'bpm' => '95-115 BPM', 'energy_level' => 'CHIC & INTERACTIVE' ),
					array( 'stage_number' => '03. FIRST DANCE', 'tab_title' => 'Symphonic Build', 'tab_subtitle' => 'Cinematic live score', 'detail_title' => 'CUSTOM SYMPHONIC FIRST DANCE', 'ensemble' => 'FULL ENSEMBLE', 'bpm' => '75-120 BPM', 'energy_level' => 'CINEMATIC & EPIC' ),
					array( 'stage_number' => '04. MAIN PARTY', 'tab_title' => '14pc Juggernaut', 'tab_subtitle' => 'Explosive dance takeover', 'detail_title' => '14-PIECE EXPLOSIVE PARTY JUGGERNAUT', 'ensemble' => '14-PIECE FLAGSHIP', 'bpm' => '124-135 BPM', 'energy_level' => 'MAXIMUM PEAK' ),
					array( 'stage_number' => '05. AFTER PARTY', 'tab_title' => 'Live DJ x Horns', 'tab_subtitle' => 'Ibiza club voltage', 'detail_title' => 'HIGH-VOLTAGE LIVE DJ X BRASS AFTERPARTY', 'ensemble' => 'HYBRID 4-PIECE', 'bpm' => '128-140 BPM', 'energy_level' => 'ELECTRIC IBIZA' ),
				),
				'title_field' => '{{{ tab_title }}}',
			)
		);

		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'head' => '.mwsb-timeline__head', 'desc' => '.mwsb-timeline__desc' ) );

		$this->start_controls_section(
			'mwsb_timeline_header_style',
			array(
				'label' => __( 'Header', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'timeline_eyebrow_color',
			array(
				'label'     => __( 'Eyebrow Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-secondary)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-timeline__eyebrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'timeline_title_typography',
				'label'    => __( 'Title Typography', 'mw-showband' ),
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .mwsb-timeline__head h2',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_timeline_content_style',
			array(
				'label' => __( 'Tabs / Cards Text', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'timeline_detail_title_heading',
			array(
				'label' => __( 'Detail Title', 'mw-showband' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'timeline_detail_title_typography',
				'selector' => '{{WRAPPER}} .mwsb-timeline__title, {{WRAPPER}} .mwsb-timeline-card__title',
			)
		);

		$this->add_control(
			'timeline_energy_color',
			array(
				'label'     => __( 'Energy Value Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-tertiary)',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-timeline__energy-value' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/** Card-grid layout: every stage shown at once (image + time badge + title + description + curated setup), no JS required. */
	private function render_cards( $s ) {
		?>
		<section class="mwsb-widget mwsb-section mwsb-timeline mwsb-timeline--cards">
			<div class="mwsb-container">
				<div class="mwsb-timeline__head">
					<span class="mwsb-eyebrow mwsb-timeline__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
					<h2 class="mwsb-heading-display mwsb-heading-display--xl"><?php echo esc_html( $s['title'] ); ?></h2>
					<p class="mwsb-timeline__desc"><?php echo esc_html( $s['description'] ); ?></p>
				</div>

				<div class="mwsb-timeline__grid">
					<?php foreach ( $s['stages'] as $stage ) : ?>
						<div class="mwsb-timeline-card">
							<div class="mwsb-timeline-card__media">
								<?php if ( ! empty( $stage['image']['url'] ) ) : ?>
									<img src="<?php echo esc_url( $stage['image']['url'] ); ?>" alt="<?php echo esc_attr( $stage['tab_title'] ); ?>" />
								<?php endif; ?>
								<span class="mwsb-timeline-card__stage"><?php echo esc_html( $stage['stage_number'] ); ?></span>
								<?php if ( ! empty( $stage['stage_time'] ) ) : ?>
									<span class="mwsb-timeline-card__time"><?php echo esc_html( $stage['stage_time'] ); ?></span>
								<?php endif; ?>
							</div>
							<div class="mwsb-timeline-card__body">
								<h3 class="mwsb-timeline-card__title"><?php echo esc_html( $stage['tab_title'] ); ?></h3>
								<p class="mwsb-timeline-card__desc"><?php echo esc_html( $stage['detail_desc'] ); ?></p>
								<?php if ( ! empty( $stage['ensemble'] ) ) : ?>
									<div class="mwsb-timeline-card__setup">
										<span class="mwsb-timeline-card__setup-label">CURATED SETUP</span>
										<span class="mwsb-timeline-card__setup-value"><?php echo esc_html( $stage['ensemble'] ); ?></span>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( 'cards' === $s['layout_style'] ) {
			$this->render_cards( $s );
			return;
		}
		?>
		<section class="mwsb-widget mwsb-section mwsb-timeline" id="interactive-timeline">
			<div class="mwsb-container">
				<div class="mwsb-timeline__head">
					<span class="mwsb-eyebrow mwsb-timeline__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
					<h2 class="mwsb-heading-display mwsb-heading-display--xl"><?php echo esc_html( $s['title'] ); ?></h2>
					<p class="mwsb-timeline__desc"><?php echo esc_html( $s['description'] ); ?></p>
				</div>

				<div class="mwsb-timeline__tabs" data-mwsb-timeline-tabs>
					<?php foreach ( $s['stages'] as $i => $stage ) : ?>
						<button
							type="button"
							class="mwsb-timeline__tab <?php echo 0 === $i ? 'is-active' : ''; ?>"
							data-title="<?php echo esc_attr( $stage['detail_title'] ); ?>"
							data-desc="<?php echo esc_attr( $stage['detail_desc'] ); ?>"
							data-ensemble="<?php echo esc_attr( $stage['ensemble'] ); ?>"
							data-bpm="<?php echo esc_attr( $stage['bpm'] ); ?>"
							data-energy="<?php echo esc_attr( $stage['energy_level'] ); ?>"
							data-image="<?php echo ! empty( $stage['image']['url'] ) ? esc_url( $stage['image']['url'] ) : ''; ?>"
							data-intensity="<?php echo esc_attr( sprintf( '%02d', $i + 1 ) ); ?>"
						>
							<span class="mwsb-timeline__tab-num"><?php echo esc_html( $stage['stage_number'] ); ?></span>
							<span class="mwsb-timeline__tab-title"><?php echo esc_html( $stage['tab_title'] ); ?></span>
							<span class="mwsb-timeline__tab-sub"><?php echo esc_html( $stage['tab_subtitle'] ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>

				<?php $first = $s['stages'][0]; ?>
				<div class="mwsb-timeline__card" data-mwsb-timeline-card>
					<div class="mwsb-timeline__info">
						<div class="mwsb-timeline__intensity">STAGE INTENSITY: LEVEL <span data-field="intensity">01</span></div>
						<h3 class="mwsb-timeline__title" data-field="title"><?php echo esc_html( $first['detail_title'] ); ?></h3>
						<p class="mwsb-timeline__text" data-field="desc"><?php echo esc_html( $first['detail_desc'] ); ?></p>
						<div class="mwsb-timeline__specs">
							<div><span>ENSEMBLE</span><strong data-field="ensemble"><?php echo esc_html( $first['ensemble'] ); ?></strong></div>
							<div><span>TEMPO</span><strong data-field="bpm"><?php echo esc_html( $first['bpm'] ); ?></strong></div>
							<div><span>ENERGY</span><strong data-field="energy" class="mwsb-timeline__energy-value"><?php echo esc_html( $first['energy_level'] ); ?></strong></div>
						</div>
					</div>
					<div class="mwsb-timeline__media">
						<?php if ( ! empty( $first['image']['url'] ) ) : ?>
							<img data-field="image" src="<?php echo esc_url( $first['image']['url'] ); ?>" alt="" />
						<?php else : ?>
							<img data-field="image" src="" alt="" style="display:none;" />
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
