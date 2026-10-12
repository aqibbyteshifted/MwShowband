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
 * MW Showband — Founder heritage / about block.
 * Author: Aqib Dev
 */
class MWSB_Founders_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-founders';
	}

	public function get_title() {
		return __( 'MW Founders', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'image', array( 'label' => __( 'Image', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => Utils::get_placeholder_image_src() ) ) );
		$this->add_control( 'image_name', array( 'label' => __( 'Image Caption Name', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MARLON & WAYNE' ) );
		$this->add_control( 'image_role', array( 'label' => __( 'Image Caption Role', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Founders • Showband Directors' ) );

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'FOUNDER HERITAGE' ) );
		$this->add_control( 'title_line1', array( 'label' => __( 'Title Line 1', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '20+ YEARS OF' ) );
		$this->add_control( 'title_line2', array( 'label' => __( 'Title Line 2', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'SHOW BUSINESS.' ) );
		$this->add_control( 'paragraph_1', array( 'label' => __( 'Paragraph 1', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Marlon and Wayne founded, directed, and fronted major international showbands for two decades before creating MW Showband.' ) );
		$this->add_control( 'paragraph_2', array( 'label' => __( 'Paragraph 2', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Having performed across 40+ countries for royalty, world leaders, and top-tier luxury festivals, they curate every single setlist directly with clients.' ) );

		$repeater = new Repeater();
		$repeater->add_control( 'number', array( 'label' => __( 'Number', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '1,200+' ) );
		$repeater->add_control( 'label', array( 'label' => __( 'Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'EVENTS DIRECTED' ) );

		$this->add_control(
			'stats',
			array(
				'label'       => __( 'Mini Stats', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'number' => '1,200+', 'label' => 'EVENTS DIRECTED' ),
					array( 'number' => '40+', 'label' => 'COUNTRIES TOURED' ),
				),
				'title_field' => '{{{ label }}}',
			)
		);

		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'desc' => '.mwsb-founders__text', 'content_gap' => '.mwsb-founders__row' ) );

		$this->start_controls_section(
			'mwsb_founders_text_style',
			array(
				'label' => __( 'Text & Stats', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'founders_paragraph_typography',
				'label'    => __( 'Paragraph Typography', 'mw-showband' ),
				'selector' => '{{WRAPPER}} .mwsb-founders__text',
			)
		);

		$this->add_control(
			'founders_stat_number_color',
			array(
				'label'     => __( 'Stat Number Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-tertiary)',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-founders__stat-number' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'founders_stat_number_typography',
				'label'    => __( 'Stat Number Typography', 'mw-showband' ),
				'selector' => '{{WRAPPER}} .mwsb-founders__stat-number',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="mwsb-widget mwsb-section mwsb-founders" id="the-band">
			<div class="mwsb-container mwsb-founders__row">
				<div class="mwsb-founders__media">
					<?php if ( ! empty( $s['image']['url'] ) ) : ?>
						<img src="<?php echo esc_url( $s['image']['url'] ); ?>" alt="<?php echo esc_attr( $s['image_name'] ); ?>" />
					<?php endif; ?>
					<div class="mwsb-founders__media-caption">
						<div class="mwsb-founders__media-name"><?php echo esc_html( $s['image_name'] ); ?></div>
						<div class="mwsb-founders__media-role"><?php echo esc_html( $s['image_role'] ); ?></div>
					</div>
				</div>
				<div class="mwsb-founders__col">
					<span class="mwsb-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
					<h2 class="mwsb-heading-display mwsb-heading-display--xl">
						<?php echo esc_html( $s['title_line1'] ); ?><br />
						<span class="mwsb-gradient-text"><?php echo esc_html( $s['title_line2'] ); ?></span>
					</h2>
					<p class="mwsb-founders__text"><?php echo esc_html( $s['paragraph_1'] ); ?></p>
					<p class="mwsb-founders__text mwsb-founders__text--muted"><?php echo esc_html( $s['paragraph_2'] ); ?></p>

					<div class="mwsb-founders__stats">
						<?php foreach ( $s['stats'] as $stat ) : ?>
							<div class="mwsb-founders__stat">
								<span class="mwsb-founders__stat-number"><?php echo esc_html( $stat['number'] ); ?></span>
								<span class="mwsb-founders__stat-label"><?php echo esc_html( $stat['label'] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
