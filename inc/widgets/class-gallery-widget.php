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
 * MW Showband — "Moments That Hit Different" editorial masonry gallery.
 * Author: Aqib Dev
 */
class MWSB_Gallery_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-gallery';
	}

	public function get_title() {
		return __( 'MW Gallery', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
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

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WORLD ARCHIVE' ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MOMENTS THAT HIT DIFFERENT' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => "Candid stadium energy captured live in the world's most breathtaking settings." ) );

		$this->end_controls_section();

		$this->start_controls_section(
			'items_section',
			array(
				'label' => __( 'Gallery Items', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'image', array( 'label' => __( 'Image', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => Utils::get_placeholder_image_src() ) ) );
		$repeater->add_control( 'tag', array( 'label' => __( 'Tag', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'DESTINATION WEDDING' ) );
		$repeater->add_control( 'caption', array( 'label' => __( 'Caption', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'LONDON • ROYAL PALACE WEDDING' ) );
		$repeater->add_control( 'badge', array( 'label' => __( 'Badge (optional)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '' ) );
		$repeater->add_control(
			'column_span',
			array(
				'label'   => __( 'Column Span (of 12)', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '8',
				'options' => array( '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '12' => '12 (full)' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Items', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'tag' => 'DESTINATION WEDDING', 'caption' => 'LONDON • ROYAL PALACE WEDDING', 'badge' => '650 GUESTS', 'column_span' => '8' ),
					array( 'tag' => 'OCEANFRONT GALA', 'caption' => 'MARBELLA • PRIVATE ESTATE', 'column_span' => '4' ),
					array( 'tag' => 'ARENA CELEBRATION', 'caption' => 'TEL AVIV • PRIVATE ARENA', 'column_span' => '5' ),
					array( 'tag' => 'SUPERYACHT SOIRÉE', 'caption' => 'MONACO • HARBOUR GRAND PRIX GALA', 'badge' => 'VIP EXCLUSIVE', 'column_span' => '7' ),
				),
				'title_field' => '{{{ caption }}}',
			)
		);

		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'head' => '.mwsb-gallery__head', 'desc' => '.mwsb-gallery__desc', 'content_gap' => '.mwsb-gallery__grid' ) );

		$this->start_controls_section(
			'mwsb_gallery_item_style',
			array(
				'label' => __( 'Item Overlay Text', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'gallery_tag_color',
			array(
				'label'     => __( 'Tag Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-tertiary)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-gallery__tag' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'       => 'gallery_caption_typography',
				'label'      => __( 'Caption Typography', 'mw-showband' ),
				'separator'  => 'before',
				'selector'   => '{{WRAPPER}} .mwsb-gallery__caption',
			)
		);

		$this->add_responsive_control(
			'gallery_item_radius',
			array(
				'label'      => __( 'Item Border Radius', 'mw-showband' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array( 'min' => 0, 'max' => 60 ),
				),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .mwsb-gallery__item' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="mwsb-widget mwsb-section mwsb-gallery">
			<div class="mwsb-container">
				<div class="mwsb-gallery__head">
					<span class="mwsb-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
					<h2 class="mwsb-heading-display mwsb-heading-display--xl"><?php echo esc_html( $s['title'] ); ?></h2>
					<p class="mwsb-gallery__desc"><?php echo esc_html( $s['description'] ); ?></p>
				</div>

				<div class="mwsb-gallery__grid">
					<?php foreach ( $s['items'] as $item ) : ?>
						<div class="mwsb-gallery__item mwsb-gallery__item--span-<?php echo esc_attr( $item['column_span'] ); ?>">
							<?php if ( ! empty( $item['image']['url'] ) ) : ?>
								<img src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $item['caption'] ); ?>" />
							<?php endif; ?>
							<div class="mwsb-gallery__scrim"></div>
							<div class="mwsb-gallery__overlay">
								<div>
									<span class="mwsb-gallery__tag"><?php echo esc_html( $item['tag'] ); ?></span>
									<h3 class="mwsb-gallery__caption"><?php echo esc_html( $item['caption'] ); ?></h3>
								</div>
								<?php if ( ! empty( $item['badge'] ) ) : ?>
									<span class="mwsb-gallery__badge"><?php echo esc_html( $item['badge'] ); ?></span>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
