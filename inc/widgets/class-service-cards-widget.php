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
 * MW Showband — "Services" page grid: an asymmetric 12-column editorial
 * layout of service cards (image, badges, title, description, footer bar).
 * Author: Aqib Dev
 */
class MWSB_Service_Cards_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-service-cards';
	}

	public function get_title() {
		return __( 'MW Service Cards', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
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

		$this->add_control( 'eyebrow_left', array( 'label' => __( 'Eyebrow Left', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '// 02 / CORE OFFERING' ) );
		$this->add_control( 'eyebrow_right', array( 'label' => __( 'Eyebrow Right', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WORLDWIDE DEPLOYMENT' ) );
		$this->add_control( 'heading_line1', array( 'label' => __( 'Heading Line 1', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'ONE SHOW.' ) );
		$this->add_control( 'heading_line2', array( 'label' => __( 'Heading Line 2 (gradient)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'INFINITE POSSIBILITIES.' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Six bespoke performance formats engineered for international galas, private estates, and luxury celebrations. Every arrangement customized to architectural dynamics.' ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'cards_section',
			array(
				'label' => __( 'Service Cards', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'image', array( 'label' => __( 'Image', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => Utils::get_placeholder_image_src() ) ) );
		$repeater->add_control(
			'width',
			array(
				'label'   => __( 'Card Width', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'wide',
				'options' => array( 'wide' => __( 'Wide', 'mw-showband' ), 'narrow' => __( 'Narrow', 'mw-showband' ) ),
			)
		);
		$repeater->add_control(
			'accent',
			array(
				'label'   => __( 'Accent Color', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'purple',
				'options' => array( 'purple' => 'Purple', 'cyan' => 'Cyan', 'magenta' => 'Magenta', 'gold' => 'Gold' ),
			)
		);
		$repeater->add_control( 'number', array( 'label' => __( 'Number', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '01' ) );
		$repeater->add_control( 'badge1', array( 'label' => __( 'Badge 1', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CORE RIG' ) );
		$repeater->add_control( 'badge2', array( 'label' => __( 'Badge 2 (optional)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '' ) );
		$repeater->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'FLAGSHIP FORMAT' ) );
		$repeater->add_control( 'meta', array( 'label' => __( 'Meta (right-aligned)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WORLD STAGES · ARENAS · SUPERYACHTS' ) );
		$repeater->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'LIVE SHOWBAND' ) );
		$repeater->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Full-scale live performance with world-class musicians, singers and stage energy.' ) );
		$repeater->add_control( 'footer_note', array( 'label' => __( 'Footer Note', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CUSTOM SETLIST ARCHITECTURE' ) );
		$repeater->add_control( 'link', array( 'label' => __( 'Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#' ) ) );
		$repeater->add_control( 'link_text', array( 'label' => __( 'Link Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'EXPLORE' ) );

		$this->add_control(
			'cards',
			array(
				'label'       => __( 'Cards', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'width' => 'wide', 'accent' => 'purple', 'number' => '01', 'badge1' => 'CORE RIG', 'badge2' => '12–16 PIECE', 'eyebrow' => 'FLAGSHIP FORMAT', 'meta' => 'WORLD STAGES · ARENAS · SUPERYACHTS', 'title' => 'LIVE SHOWBAND', 'description' => 'Full-scale live performance with world-class musicians, singers and stage energy. From customized horn arrangements to arena-tier vocal power that commands thousands.', 'footer_note' => 'CUSTOM SETLIST ARCHITECTURE', 'link_text' => 'EXPLORE' ),
					array( 'width' => 'narrow', 'accent' => 'magenta', 'number' => '02', 'badge1' => 'BESPOKE CELEBRATION', 'badge2' => '', 'eyebrow' => 'CURATED COUTURE', 'meta' => 'PRIVATE ESTATES', 'title' => 'WEDDINGS', 'description' => 'Entertainment designed around the couple, the guests and the energy of the celebration. Seamless transitions from acoustic ceremonies to explosive dawn dance floors.', 'footer_note' => 'DYNAMIC 3-PHASE CURATION', 'link_text' => 'EXPLORE' ),
					array( 'width' => 'narrow', 'accent' => 'cyan', 'number' => '03', 'badge1' => 'BRAND STAGES', 'badge2' => '', 'eyebrow' => 'PRECISION TIMING', 'meta' => 'SUMMITS & AWARDS', 'title' => 'CORPORATE EVENTS', 'description' => 'High-impact live entertainment for launches, galas, awards and corporate experiences. Timecode-synced walk-up stingers, custom award fanfares, and headliner sets.', 'footer_note' => 'SHOWCALLER SYNCHRONIZATION', 'link_text' => 'EXPLORE' ),
					array( 'width' => 'wide', 'accent' => 'purple', 'number' => '04', 'badge1' => 'INTIMATE LUXURY', 'badge2' => 'EXCLUSIVE GUESTLISTS', 'eyebrow' => 'IMMERSIVE INTERACTION', 'meta' => 'PENTHOUSES & CHATEAUX', 'title' => 'PRIVATE PARTIES', 'description' => 'Bespoke entertainment for private celebrations, milestone events and exclusive occasions. Direct dancefloor roaming, acoustic pop-ups, and spontaneous VIP guest duets.', 'footer_note' => 'NO BARRIERS · 100% VISCERAL', 'link_text' => 'EXPLORE' ),
					array( 'width' => 'wide', 'accent' => 'cyan', 'number' => '05', 'badge1' => 'CROSS-BORDER OPS', 'badge2' => 'SELF-CONTAINED PRODUCTION', 'eyebrow' => 'INTERNATIONAL LOGISTICS', 'meta' => 'MONACO · MARBELLA · DUBAI', 'title' => 'DESTINATION EVENTS', 'description' => 'International entertainment for luxury weddings and events around the world. Fully bonded ATA Carnets, global backline coordination, and zero-compromise touring execution.', 'footer_note' => 'AIR-TRANSIT PACKAGED PRODUCTION', 'link_text' => 'EXPLORE' ),
					array( 'width' => 'narrow', 'accent' => 'gold', 'number' => '06', 'badge1' => 'AVANT-GARDE', 'badge2' => '', 'eyebrow' => 'HAUTE PRODUCTION', 'meta' => 'CUSTOM THEMATIC RIGS', 'title' => 'BESPOKE SHOWS', 'description' => 'Custom entertainment concepts built around the venue, audience, theme and event vision. Specialized 24-piece orchestras, interactive dancers, and holographic LED synchronization.', 'footer_note' => 'CREATIVE DIRECTION LAB', 'link_text' => 'EXPLORE' ),
				),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'eyebrow' => '.mwsb-service-cards__eyebrow', 'heading' => '.mwsb-service-cards__heading', 'desc' => '.mwsb-service-cards__desc', 'flow' => '.mwsb-service-cards__header', 'content_gap' => '.mwsb-service-cards__inner' ) );

		$this->start_controls_section(
			'mwsb_sc_heading_style',
			array(
				'label' => __( 'Heading', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .mwsb-service-cards__heading',
				'label'    => __( 'Heading Typography', 'mw-showband' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_sc_card_style',
			array(
				'label' => __( 'Cards', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .mwsb-service-card' => '--mwsb-sc-surface: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_border_radius',
			array(
				'label'      => __( 'Card Border Radius', 'mw-showband' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 16 ),
				'selectors'  => array(
					'{{WRAPPER}} .mwsb-service-card' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'     => __( 'Image Height', 'mw-showband' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 160, 'max' => 600 ) ),
				'default'   => array( 'unit' => 'px', 'size' => 320 ),
				'selectors' => array(
					'{{WRAPPER}} .mwsb-service-card__media' => 'height: {{SIZE}}{{UNIT}};',
				),
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
		);
		return isset( $map[ $accent ] ) ? $map[ $accent ] : $map['purple'];
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="mwsb-widget mwsb-section mwsb-service-cards">
			<div class="mwsb-container mwsb-service-cards__inner">

				<div class="mwsb-service-cards__header">
					<div class="mwsb-service-cards__eyebrow">
						<span><?php echo esc_html( $s['eyebrow_left'] ); ?></span>
						<span class="mwsb-service-cards__eyebrow-sep">&middot;</span>
						<span class="mwsb-service-cards__eyebrow-muted"><?php echo esc_html( $s['eyebrow_right'] ); ?></span>
					</div>
					<h2 class="mwsb-heading-display mwsb-heading-display--xl mwsb-service-cards__heading">
						<?php echo esc_html( $s['heading_line1'] ); ?> <span class="mwsb-gradient-text"><?php echo esc_html( $s['heading_line2'] ); ?></span>
					</h2>
					<?php if ( ! empty( $s['description'] ) ) : ?>
						<p class="mwsb-service-cards__desc"><?php echo esc_html( $s['description'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $s['cards'] ) ) : ?>
					<div class="mwsb-service-cards__grid">
						<?php foreach ( $s['cards'] as $card ) :
							$accent_var  = $this->get_accent_var( $card['accent'] );
							$width_class = 'narrow' === $card['width'] ? 'mwsb-service-card--narrow' : 'mwsb-service-card--wide';
							?>
							<article class="mwsb-service-card <?php echo esc_attr( $width_class ); ?>" style="--mwsb-sc-accent: <?php echo esc_attr( $accent_var ); ?>;">
								<div class="mwsb-service-card__media">
									<?php if ( ! empty( $card['image']['url'] ) ) : ?>
										<img src="<?php echo esc_url( $card['image']['url'] ); ?>" alt="<?php echo esc_attr( $card['title'] ); ?>" />
									<?php endif; ?>
									<div class="mwsb-service-card__badges">
										<?php if ( ! empty( $card['badge1'] ) ) : ?><span class="mwsb-service-card__badge mwsb-service-card__badge--accent"><?php echo esc_html( $card['badge1'] ); ?></span><?php endif; ?>
										<?php if ( ! empty( $card['badge2'] ) ) : ?><span class="mwsb-service-card__badge"><?php echo esc_html( $card['badge2'] ); ?></span><?php endif; ?>
									</div>
									<?php if ( ! empty( $card['number'] ) ) : ?>
										<span class="mwsb-service-card__number"><?php echo esc_html( $card['number'] ); ?></span>
									<?php endif; ?>
								</div>
								<div class="mwsb-service-card__body">
									<div class="mwsb-service-card__top-row">
										<?php if ( ! empty( $card['eyebrow'] ) ) : ?><span class="mwsb-service-card__eyebrow"><?php echo esc_html( $card['eyebrow'] ); ?></span><?php endif; ?>
										<?php if ( ! empty( $card['meta'] ) ) : ?><span class="mwsb-service-card__meta"><?php echo esc_html( $card['meta'] ); ?></span><?php endif; ?>
									</div>
									<h3 class="mwsb-service-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
									<?php if ( ! empty( $card['description'] ) ) : ?>
										<p class="mwsb-service-card__desc"><?php echo esc_html( $card['description'] ); ?></p>
									<?php endif; ?>
								</div>
								<div class="mwsb-service-card__footer">
									<?php if ( ! empty( $card['footer_note'] ) ) : ?>
										<span class="mwsb-service-card__footer-note">
											<span class="mwsb-service-card__footer-dot"></span>
											<?php echo esc_html( $card['footer_note'] ); ?>
										</span>
									<?php endif; ?>
									<a href="<?php echo esc_url( $card['link']['url'] ); ?>" class="mwsb-service-card__link">
										<?php echo esc_html( $card['link_text'] ); ?>
										<span class="mwsb-service-card__link-arrow">&#8594;</span>
									</a>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

			</div>
		</section>
		<?php
	}
}
