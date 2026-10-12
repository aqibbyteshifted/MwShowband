<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

/**
 * MW Showband — Dramatic gradient stat counters.
 * Author: Aqib Dev
 */
class MWSB_Stats_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-stats';
	}

	public function get_title() {
		return __( 'MW Stats', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-counter';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Stats', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'number', array( 'label' => __( 'Number', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '14' ) );
		$repeater->add_control( 'label', array( 'label' => __( 'Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MUSICIANS' ) );
		$repeater->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Virtuoso multi-instrumentalists with blistering 4-piece horn core.' ) );
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

		$this->add_control(
			'stats',
			array(
				'label'       => __( 'Stat Items', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'number' => '14', 'label' => 'MUSICIANS', 'description' => 'Virtuoso multi-instrumentalists with blistering 4-piece horn core.', 'accent' => 'purple' ),
					array( 'number' => '4', 'label' => 'VOCALISTS', 'description' => 'Powerhouse international frontlines with four-octave versatility.', 'accent' => 'magenta' ),
					array( 'number' => '800+', 'label' => 'SONGS', 'description' => "Seven decades of dance, funk, rock, motown & world club tracks.", 'accent' => 'cyan' ),
					array( 'number' => 'GLOBAL', 'label' => 'PERFORMANCES', 'description' => 'London, Marbella, Tel Aviv, Monaco, Dubai, and New York.', 'accent' => 'gold' ),
				),
				'title_field' => '{{{ label }}}',
			)
		);

		$this->end_controls_section();

		$this->register_section_style_controls();

		$this->start_controls_section(
			'mwsb_stats_style',
			array(
				'label' => __( 'Stat Text', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'stat_number_heading',
			array(
				'label'       => __( 'Number', 'mw-showband' ),
				'type'        => Controls_Manager::HEADING,
				'description' => __( 'The number uses a gradient fill per accent color, so only size/weight apply here (not color).', 'mw-showband' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stat_number_typography',
				'selector' => '{{WRAPPER}} .mwsb-stat__number',
			)
		);

		$this->add_control(
			'stat_label_heading',
			array(
				'label'     => __( 'Label', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stat_label_typography',
				'selector' => '{{WRAPPER}} .mwsb-stat__label',
			)
		);

		$this->add_control(
			'stat_label_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-white)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-stat__label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'stat_desc_heading',
			array(
				'label'     => __( 'Description', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stat_desc_typography',
				'selector' => '{{WRAPPER}} .mwsb-stat__desc',
			)
		);

		$this->add_control(
			'stat_desc_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-muted)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-stat__desc' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="mwsb-widget mwsb-section mwsb-stats">
			<div class="mwsb-container mwsb-stats__grid">
				<?php foreach ( $s['stats'] as $stat ) : ?>
					<div class="mwsb-stat">
						<span class="mwsb-stat__number mwsb-stat__number--<?php echo esc_attr( $stat['accent'] ); ?>"><?php echo esc_html( $stat['number'] ); ?></span>
						<span class="mwsb-stat__label"><?php echo esc_html( $stat['label'] ); ?></span>
						<p class="mwsb-stat__desc"><?php echo esc_html( $stat['description'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
		<?php
	}
}
