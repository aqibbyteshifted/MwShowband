<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;

/**
 * MW Showband — "About" page founders section: side-by-side founder cards
 * (image, bio, tag pills).
 * Author: Aqib Dev
 */
class MWSB_Dual_Founders_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-dual-founders';
	}

	public function get_title() {
		return __( 'MW Dual Founders', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-person';
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

		$this->add_control(
			'eyebrow_icon',
			array(
				'label'   => __( 'Eyebrow Icon', 'mw-showband' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array( 'value' => 'fas fa-star', 'library' => 'fa-solid' ),
			)
		);
		$this->add_control( 'eyebrow_text', array( 'label' => __( 'Eyebrow Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '02 / LEADERSHIP // THE ARCHITECTS' ) );
		$this->add_control( 'heading', array( 'label' => __( 'Heading', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MARLON & WAYNE' ) );
		$this->add_control( 'subheading', array( 'label' => __( 'Subheading', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'SHOWBAND DIRECTORS & FOUNDERS' ) );
		$this->add_control( 'intro_text', array( 'label' => __( 'Intro Text', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Unifying technical orchestral masterclass with magnetic arena showmanship. The driving force behind elite bespoke private entertainment.' ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'founders_section',
			array(
				'label' => __( 'Founders', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'image', array( 'label' => __( 'Image', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => Utils::get_placeholder_image_src() ) ) );
		$repeater->add_control( 'corner_tag', array( 'label' => __( 'Corner Tag', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CO-FOUNDER // 01' ) );
		$repeater->add_control(
			'accent',
			array(
				'label'   => __( 'Accent Color', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'purple',
				'options' => array( 'purple' => 'Purple', 'cyan' => 'Cyan', 'magenta' => 'Magenta', 'gold' => 'Gold' ),
			)
		);
		$repeater->add_control( 'name', array( 'label' => __( 'Name', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MARLON' ) );
		$repeater->add_control( 'role_tag', array( 'label' => __( 'Role Tag', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MUSICAL DIRECTOR' ) );
		$repeater->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Musical Director & Master Conductor' ) );
		$repeater->add_control( 'bio', array( 'label' => __( 'Bio', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => '20+ Years Stadium & Gala Production. Marlon crafts the bespoke musical arrangements, orchestrating the 14-piece ensemble with mathematical precision, dynamic pacing, and symphonic soul.' ) );
		$repeater->add_control(
			'tags',
			array(
				'label'       => __( 'Tag Pills', 'mw-showband' ),
				'type'        => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ),
				'description' => __( 'One tag per line.', 'mw-showband' ),
				'default'     => "Royal Philharmonic Collaborator\nWest End Brass Arranger\nTimecoded Audio Director",
			)
		);

		$this->add_control(
			'founders',
			array(
				'label'       => __( 'Founder Cards', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'corner_tag' => 'CO-FOUNDER // 01',
						'accent'     => 'purple',
						'name'       => 'MARLON',
						'role_tag'   => 'MUSICAL DIRECTOR',
						'title'      => 'Musical Director & Master Conductor',
						'bio'        => '20+ Years Stadium & Gala Production. Marlon crafts the bespoke musical arrangements, orchestrating the 14-piece ensemble with mathematical precision, dynamic pacing, and symphonic soul.',
						'tags'       => "Royal Philharmonic Collaborator\nWest End Brass Arranger\nTimecoded Audio Director",
					),
					array(
						'corner_tag' => 'CO-FOUNDER // 02',
						'accent'     => 'magenta',
						'name'       => 'WAYNE',
						'role_tag'   => 'CREATIVE DIRECTOR',
						'title'      => 'Creative Director & Lead Frontman',
						'bio'        => 'Fronted International Arena Tours. Wayne dictates the energetic arc of the room, captivating crowds with electric vocal power, unmatched crowd charisma, and visceral performance momentum.',
						'tags'       => "Global Arena Frontman\nChoreography & Stage Design\nVocal Collective Lead",
					),
				),
				'title_field' => '{{{ name }}}',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'eyebrow' => '.mwsb-dual-founders__eyebrow', 'heading' => '.mwsb-dual-founders__heading', 'desc' => '.mwsb-dual-founders__intro', 'head' => '.mwsb-dual-founders__header', 'content_gap' => '.mwsb-dual-founders__grid' ) );

		$this->start_controls_section(
			'mwsb_df_heading_style',
			array(
				'label' => __( 'Heading', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .mwsb-dual-founders__heading',
				'label'    => __( 'Heading Typography', 'mw-showband' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_df_card_style',
			array(
				'label' => __( 'Founder Cards', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'     => __( 'Image Height', 'mw-showband' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 200, 'max' => 800 ) ),
				'default'   => array( 'unit' => 'px', 'size' => 520 ),
				'selectors' => array(
					'{{WRAPPER}} .mwsb-founder-card__media' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_surface',
			array(
				'label'   => __( 'Card Surface', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'elevated',
				'options' => array(
					'elevated'  => __( 'Elevated Card', 'mw-showband' ),
					'container' => __( 'Container Low', 'mw-showband' ),
					'lowest'    => __( 'Container Lowest', 'mw-showband' ),
					'highlight' => __( 'Highlight', 'mw-showband' ),
				),
				'selectors_dictionary' => array(
					'elevated'  => 'var(--mwsb-surface-elevated)',
					'container' => 'var(--mwsb-surface-container-low)',
					'lowest'    => 'var(--mwsb-surface-container-lowest)',
					'highlight' => 'var(--mwsb-surface-highlight)',
				),
				'selectors' => array(
					'{{WRAPPER}} .mwsb-founder-card' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'founder_name_typography',
				'label'     => __( 'Name Typography', 'mw-showband' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .mwsb-founder-card__name',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'founder_bio_typography',
				'label'    => __( 'Bio Typography', 'mw-showband' ),
				'selector' => '{{WRAPPER}} .mwsb-founder-card__bio',
			)
		);

		$this->end_controls_section();
	}

	private function get_accent_var( $accent ) {
		$map = array(
			'purple'  => 'var(--mwsb-accent-purple)',
			'cyan'    => 'var(--mwsb-tertiary)',
			'magenta' => 'var(--mwsb-secondary)',
			'gold'    => 'var(--mwsb-stage-gold)',
			'white'   => 'var(--mwsb-text-white)',
		);
		return isset( $map[ $accent ] ) ? $map[ $accent ] : $map['purple'];
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="mwsb-widget mwsb-section mwsb-dual-founders">
			<div class="mwsb-container mwsb-dual-founders__inner">

				<div class="mwsb-dual-founders__header">
					<div>
						<div class="mwsb-dual-founders__eyebrow">
							<?php Icons_Manager::render_icon( $s['eyebrow_icon'], array( 'aria-hidden' => 'true' ) ); ?>
							<span><?php echo esc_html( $s['eyebrow_text'] ); ?></span>
						</div>
						<h2 class="mwsb-heading-display mwsb-heading-display--xl mwsb-dual-founders__heading"><?php echo esc_html( $s['heading'] ); ?></h2>
						<?php if ( ! empty( $s['subheading'] ) ) : ?>
							<p class="mwsb-dual-founders__subheading"><?php echo esc_html( $s['subheading'] ); ?></p>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $s['intro_text'] ) ) : ?>
						<p class="mwsb-dual-founders__intro"><?php echo esc_html( $s['intro_text'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $s['founders'] ) ) : ?>
					<div class="mwsb-dual-founders__grid">
						<?php foreach ( $s['founders'] as $founder ) :
							$accent_var = $this->get_accent_var( $founder['accent'] );
							$tags       = array_filter( array_map( 'trim', explode( "\n", (string) $founder['tags'] ) ) );
							?>
							<div class="mwsb-founder-card">
								<div class="mwsb-founder-card__media">
									<?php if ( ! empty( $founder['image']['url'] ) ) : ?>
										<img src="<?php echo esc_url( $founder['image']['url'] ); ?>" alt="<?php echo esc_attr( $founder['name'] ); ?>" />
									<?php endif; ?>
									<?php if ( ! empty( $founder['corner_tag'] ) ) : ?>
										<span class="mwsb-founder-card__corner" style="color:<?php echo esc_attr( $accent_var ); ?>;"><?php echo esc_html( $founder['corner_tag'] ); ?></span>
									<?php endif; ?>
								</div>
								<div class="mwsb-founder-card__body">
									<div class="mwsb-founder-card__row">
										<h3 class="mwsb-founder-card__name"><?php echo esc_html( $founder['name'] ); ?></h3>
										<span class="mwsb-founder-card__role-tag" style="color:<?php echo esc_attr( $accent_var ); ?>;"><?php echo esc_html( $founder['role_tag'] ); ?></span>
									</div>
									<p class="mwsb-founder-card__title" style="color:<?php echo esc_attr( $accent_var ); ?>;"><?php echo esc_html( $founder['title'] ); ?></p>
									<p class="mwsb-founder-card__bio"><?php echo esc_html( $founder['bio'] ); ?></p>
									<?php if ( ! empty( $tags ) ) : ?>
										<div class="mwsb-founder-card__tags">
											<?php foreach ( $tags as $tag ) : ?>
												<span class="mwsb-founder-card__tag"><?php echo esc_html( $tag ); ?></span>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
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
