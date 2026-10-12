<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

/**
 * MW Showband — "What Are You Planning?" archetype selector + matchmaker CTA.
 * Author: Aqib Dev
 */
class MWSB_Event_Planner_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-event-planner';
	}

	public function get_title() {
		return __( 'MW Event Planner', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-select';
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

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CUSTOM ARCHITECTURE' ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WHAT ARE YOU PLANNING?' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Select your event archetype to configure your custom entertainment tier.' ) );

		$this->end_controls_section();

		$this->start_controls_section(
			'archetypes_section',
			array(
				'label' => __( 'Archetypes', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'icon_char', array( 'label' => __( 'Icon (emoji/char)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '&#10084;' ) );
		$repeater->add_control( 'archetype_title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WEDDING' ) );
		$repeater->add_control( 'archetype_text', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Ceremony strings, cocktail quartet, 12-14pc Showband & midnight afterparty.' ) );
		$repeater->add_control(
			'accent',
			array(
				'label'   => __( 'Accent', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'purple',
				'options' => array( 'purple' => 'Purple', 'cyan' => 'Cyan', 'magenta' => 'Magenta', 'gold' => 'Gold' ),
			)
		);

		$this->add_control(
			'archetypes',
			array(
				'label'       => __( 'Archetype Cards', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'icon_char' => '&#10084;', 'archetype_title' => 'WEDDING', 'archetype_text' => 'Ceremony strings, cocktail quartet, 12-14pc Showband & midnight afterparty.', 'accent' => 'purple' ),
					array( 'icon_char' => '&#127970;', 'archetype_title' => 'CORPORATE', 'archetype_text' => 'Awards banquets, custom walk-up stings, gala dinner set & headliner show.', 'accent' => 'cyan' ),
					array( 'icon_char' => '&#127881;', 'archetype_title' => 'PRIVATE PARTY', 'archetype_text' => 'Milestone birthdays, VIP villas, Ibiza theme hybrid brass sets.', 'accent' => 'magenta' ),
					array( 'icon_char' => '&#9992;', 'archetype_title' => 'DESTINATION', 'archetype_text' => 'Multi-day luxury celebrations, welcome yacht cocktail, main gala show.', 'accent' => 'gold' ),
				),
				'title_field' => '{{{ archetype_title }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cta_section',
			array(
				'label' => __( 'Matchmaker CTA', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'cta_title', array( 'label' => __( 'CTA Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'READY TO INITIALIZE YOUR CURATION?' ) );
		$this->add_control( 'cta_desc', array( 'label' => __( 'CTA Description', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Instant technical specs, date checks, and bespoke quote generation.' ) );
		$this->add_control( 'cta_button_text', array( 'label' => __( 'Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => "LET'S BUILD YOUR SHOW →" ) );
		$this->add_control( 'cta_button_link', array( 'label' => __( 'Button Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#contact-crescendo' ) ) );

		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'head' => '.mwsb-planner__head', 'desc' => '.mwsb-planner__desc', 'content_gap' => '.mwsb-planner__grid' ) );

		$this->start_controls_section(
			'mwsb_planner_style',
			array(
				'label' => __( 'Archetype Cards & CTA', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'archetype_title_typography',
				'label'    => __( 'Archetype Title Typography', 'mw-showband' ),
				'selector' => '{{WRAPPER}} .mwsb-archetype-card h3',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'planner_cta_title_typography',
				'label'     => __( 'CTA Title Typography', 'mw-showband' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .mwsb-planner__cta-title',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="mwsb-widget mwsb-section mwsb-planner" id="event-builder">
			<div class="mwsb-container">
				<div class="mwsb-planner__head">
					<span class="mwsb-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
					<h2 class="mwsb-heading-display mwsb-heading-display--xl"><?php echo esc_html( $s['title'] ); ?></h2>
					<p class="mwsb-planner__desc"><?php echo esc_html( $s['description'] ); ?></p>
				</div>

				<div class="mwsb-planner__grid" data-mwsb-archetype-group>
					<?php foreach ( $s['archetypes'] as $i => $a ) : ?>
						<div class="mwsb-archetype-card mwsb-archetype-card--<?php echo esc_attr( $a['accent'] ); ?> <?php echo 0 === $i ? 'is-active' : ''; ?>" data-mwsb-archetype>
							<div>
								<div class="mwsb-archetype-card__icon"><?php echo wp_kses_post( $a['icon_char'] ); ?></div>
								<h3><?php echo esc_html( $a['archetype_title'] ); ?></h3>
								<p><?php echo esc_html( $a['archetype_text'] ); ?></p>
							</div>
							<span class="mwsb-archetype-card__link">Select Archetype &#8594;</span>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="mwsb-planner__cta">
					<div>
						<div class="mwsb-planner__cta-title"><?php echo esc_html( $s['cta_title'] ); ?></div>
						<p class="mwsb-planner__cta-desc"><?php echo esc_html( $s['cta_desc'] ); ?></p>
					</div>
					<a href="<?php echo esc_url( $s['cta_button_link']['url'] ); ?>" class="mwsb-btn mwsb-btn--primary"><?php echo esc_html( $s['cta_button_text'] ); ?></a>
				</div>
			</div>
		</section>
		<?php
	}
}
