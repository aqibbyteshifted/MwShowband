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
 * MW Showband — Split feature block with pill tags + live visualizer + image card.
 * Author: Aqib Dev
 */
class MWSB_Feature_Split_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-feature-split';
	}

	public function get_title() {
		return __( 'MW Feature Split', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-column';
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

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'UNCOMPROMISING STADIUM RIG' ) );
		$this->add_control( 'title_prefix', array( 'label' => __( 'Title Prefix (optional, e.g. "We are")', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '' ) );
		$this->add_control( 'title_line1', array( 'label' => __( 'Title Line 1', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'NOT BACKGROUND MUSIC.' ) );
		$this->add_control( 'title_line2', array( 'label' => __( 'Title Line 2', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'A LIVE EXPERIENCE.' ) );
		$this->add_control(
			'title_inline',
			array(
				'label'   => __( 'Title On One Line', 'mw-showband' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);
		$this->add_control(
			'title_line2_style',
			array(
				'label'   => __( 'Title Line 2 Style', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'gradient',
				'options' => array(
					'gradient'      => __( 'Gradient Fill', 'mw-showband' ),
					'italic_accent' => __( 'Italic + Accent Color', 'mw-showband' ),
				),
			)
		);
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Every arrangement is written, choreographed, and light-synced in our private production labs. We bring turnkey stadium sound and concert illumination into your venue.' ) );

		$bullet_repeater = new Repeater();
		$bullet_repeater->add_control( 'text', array( 'label' => __( 'Point', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => '' ) );
		$this->add_control(
			'bullet_points',
			array(
				'label'       => __( 'Bullet Points (optional — shown as a list below the Description)', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $bullet_repeater->get_controls(),
				'default'     => array(),
				'title_field' => '{{{ text }}}',
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'tag', array( 'label' => __( 'Tag', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'LIVE VOCALS' ) );
		$this->add_control(
			'tags',
			array(
				'label'   => __( 'Feature Tags', 'mw-showband' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array( 'tag' => 'LIVE VOCALS' ),
					array( 'tag' => 'LIVE MUSICIANS' ),
					array( 'tag' => 'INTERACTIVE PERFORMANCE' ),
					array( 'tag' => 'CUSTOM SETLIST' ),
					array( 'tag' => 'FULL-SCALE SHOW' ),
				),
				'title_field' => '{{{ tag }}}',
			)
		);

		$this->add_control( 'feed_label', array( 'label' => __( 'Feed Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'DMX & TIMECODE LIVE FEED' ) );
		$this->add_control( 'feed_status', array( 'label' => __( 'Feed Status', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CH 01-32 ACTIVE' ) );

		$this->add_control( 'image', array( 'label' => __( 'Feature Image', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => Utils::get_placeholder_image_src() ) ) );
		$this->add_control( 'image_caption_title', array( 'label' => __( 'Image Caption Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MONUMENTAL SOUND ARCHITECTURE' ) );
		$this->add_control( 'image_caption_text', array( 'label' => __( 'Image Caption Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Bespoke 64-channel wireless IEM and touring audio array.' ) );

		$this->add_control(
			'reverse_layout',
			array(
				'label'        => __( 'Reverse Layout', 'mw-showband' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
			)
		);

		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'desc' => '.mwsb-feature-split__desc', 'content_gap' => '.mwsb-feature-split__panel' ) );

		$this->start_controls_section(
			'mwsb_fs_text_style',
			array(
				'label' => __( 'Eyebrow, Title & Text', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'fs_eyebrow_heading',
			array(
				'label' => __( 'Eyebrow', 'mw-showband' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fs_eyebrow_typography',
				'selector' => '{{WRAPPER}} .mwsb-feature-split__eyebrow',
			)
		);

		$this->add_control(
			'fs_eyebrow_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-tertiary)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-feature-split__eyebrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'fs_prefix_heading',
			array(
				'label'     => __( 'Title Prefix', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fs_prefix_typography',
				'selector' => '{{WRAPPER}} .mwsb-feature-split__prefix',
			)
		);

		$this->add_control(
			'fs_prefix_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-muted)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-feature-split__prefix' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'fs_title_heading',
			array(
				'label'     => __( 'Title', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fs_title_typography',
				'selector' => '{{WRAPPER}} .mwsb-feature-split__panel h2',
			)
		);

		$this->add_control(
			'fs_title_color',
			array(
				'label'     => __( 'Line 1 Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-white)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-feature-split__panel h2' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'fs_title_accent_color',
			array(
				'label'     => __( 'Line 2 Accent Color (Italic + Accent Color style only)', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-accent-purple)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-feature-split__accent-italic' => 'color: {{VALUE}};',
				),
				'condition' => array( 'title_line2_style' => 'italic_accent' ),
			)
		);

		$this->add_control(
			'fs_desc_heading',
			array(
				'label'     => __( 'Description', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fs_desc_typography',
				'selector' => '{{WRAPPER}} .mwsb-feature-split__desc, {{WRAPPER}} .mwsb-feature-split__bullets li',
			)
		);

		$this->add_control(
			'fs_desc_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-muted)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-feature-split__desc' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_fs_media_style',
			array(
				'label' => __( 'Tags, Visualizer & Image Caption', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'fs_tag_heading',
			array(
				'label' => __( 'Feature Tags', 'mw-showband' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fs_tag_typography',
				'selector' => '{{WRAPPER}} .mwsb-pill',
			)
		);

		$this->add_control(
			'fs_feed_label_heading',
			array(
				'label'     => __( 'Visualizer Feed Label', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'fs_feed_label_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-tertiary)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-visualizer__label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'fs_caption_heading',
			array(
				'label'     => __( 'Image Caption', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fs_caption_title_typography',
				'label'    => __( 'Title Typography', 'mw-showband' ),
				'selector' => '{{WRAPPER}} .mwsb-feature-split__caption-title',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fs_caption_text_typography',
				'label'    => __( 'Text Typography', 'mw-showband' ),
				'selector' => '{{WRAPPER}} .mwsb-feature-split__caption-text',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_fs_panel_style',
			array(
				'label' => __( 'Panel Card & Bullets', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'fs_panel_bg',
			array(
				'label'     => __( 'Panel Background', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(47, 34, 87, 0.7)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-feature-split__panel' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'fs_panel_radius',
			array(
				'label'      => __( 'Panel Border Radius', 'mw-showband' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 80 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .mwsb-feature-split__panel' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'fs_panel_padding',
			array(
				'label'      => __( 'Panel Padding', 'mw-showband' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mwsb-feature-split__panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'fs_bullet_icon_heading',
			array(
				'label'     => __( 'Bullet Icon', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'fs_bullet_icon_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-tertiary)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-feature-split__bullets li::before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$reverse = 'yes' === $s['reverse_layout'] ? ' mwsb-feature-split--reverse' : '';
		?>
		<section class="mwsb-widget mwsb-section mwsb-feature-split<?php echo esc_attr( $reverse ); ?>">
			<div class="mwsb-container">
				<div class="mwsb-feature-split__panel">
					<div class="mwsb-feature-split__col">
						<span class="mwsb-eyebrow mwsb-feature-split__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
						<?php if ( ! empty( $s['title_prefix'] ) ) : ?>
							<span class="mwsb-feature-split__prefix"><?php echo esc_html( $s['title_prefix'] ); ?></span>
						<?php endif; ?>
						<h2 class="mwsb-heading-display mwsb-heading-display--xl">
							<?php echo esc_html( $s['title_line1'] ); ?><?php echo 'yes' === $s['title_inline'] ? ' ' : '<br />'; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span class="<?php echo 'italic_accent' === $s['title_line2_style'] ? 'mwsb-feature-split__accent-italic' : 'mwsb-gradient-text'; ?>"><?php echo esc_html( $s['title_line2'] ); ?></span>
						</h2>
						<p class="mwsb-feature-split__desc"><?php echo esc_html( $s['description'] ); ?></p>

						<?php if ( ! empty( $s['bullet_points'] ) ) : ?>
							<ul class="mwsb-feature-split__bullets">
								<?php foreach ( $s['bullet_points'] as $bp ) : ?>
									<?php if ( empty( $bp['text'] ) ) { continue; } ?>
									<li><?php echo esc_html( $bp['text'] ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<div class="mwsb-pill-row">
							<?php foreach ( $s['tags'] as $t ) : ?>
								<span class="mwsb-pill"><?php echo esc_html( $t['tag'] ); ?></span>
							<?php endforeach; ?>
						</div>

						<div class="mwsb-visualizer">
							<div class="mwsb-visualizer__head">
								<span class="mwsb-visualizer__label"><?php echo esc_html( $s['feed_label'] ); ?></span>
								<span><?php echo esc_html( $s['feed_status'] ); ?></span>
							</div>
							<div class="mwsb-visualizer__bars">
								<?php
								$heights = array( 85, 65, 95, 70, 90, 60, 80, 100 );
								foreach ( $heights as $i => $h ) :
									?>
									<span style="height: <?php echo (int) $h; ?>%;" class="<?php echo 100 === $h ? 'mwsb-visualizer__bar--pulse' : ''; ?>"></span>
								<?php endforeach; ?>
							</div>
						</div>
					</div>

					<div class="mwsb-feature-split__col mwsb-feature-split__media">
						<?php if ( ! empty( $s['image']['url'] ) ) : ?>
							<img src="<?php echo esc_url( $s['image']['url'] ); ?>" alt="<?php echo esc_attr( $s['image_caption_title'] ); ?>" />
						<?php endif; ?>
						<div class="mwsb-feature-split__caption">
							<div>
								<div class="mwsb-feature-split__caption-title"><?php echo esc_html( $s['image_caption_title'] ); ?></div>
								<div class="mwsb-feature-split__caption-text"><?php echo esc_html( $s['image_caption_text'] ); ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
