<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

/**
 * MW Showband — Sticky Header widget.
 * Author: Aqib Dev
 */
class MWSB_Header_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-header';
	}

	public function get_title() {
		return __( 'MW Header', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-header';
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

		$this->add_control(
			'logo',
			array(
				'label'   => __( 'Logo', 'mw-showband' ),
				'type'    => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ),
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);

		$this->add_control(
			'logo_text',
			array(
				'label'   => __( 'Logo Text', 'mw-showband' ),
				'type'    => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'default' => 'MW SHOWBAND',
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'mw-showband' ),
				'type'    => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'default' => 'MENU ITEM',
			)
		);
		$repeater->add_control(
			'link',
			array(
				'label'   => __( 'Link', 'mw-showband' ),
				'type'    => Controls_Manager::URL, 'dynamic' => array( 'active' => true ),
				'default' => array( 'url' => '#' ),
			)
		);

		$this->add_control(
			'nav_items',
			array(
				'label'       => __( 'Navigation Items', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'label' => 'SHOWS', 'link' => array( 'url' => '#interactive-timeline' ) ),
					array( 'label' => 'EVENTS', 'link' => array( 'url' => '#event-builder' ) ),
					array( 'label' => 'EXPERIENCE', 'link' => array( 'url' => '#experience' ) ),
					array( 'label' => 'THE BAND', 'link' => array( 'url' => '#the-band' ) ),
					array( 'label' => 'MEDIA', 'link' => array( 'url' => '#media-vault' ) ),
					array( 'label' => 'CONTACT', 'link' => array( 'url' => '#contact-crescendo' ) ),
				),
				'title_field' => '{{{ label }}}',
			)
		);

		$this->add_control(
			'ghost_button_text',
			array(
				'label'   => __( 'Secondary Button Text', 'mw-showband' ),
				'type'    => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'default' => 'WATCH THE SHOW',
			)
		);
		$this->add_control(
			'ghost_button_link',
			array(
				'label'   => __( 'Secondary Button Link', 'mw-showband' ),
				'type'    => Controls_Manager::URL, 'dynamic' => array( 'active' => true ),
				'default' => array( 'url' => '#media-vault' ),
			)
		);

		$this->add_control(
			'primary_button_text',
			array(
				'label'   => __( 'Primary Button Text', 'mw-showband' ),
				'type'    => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'default' => 'CHECK AVAILABILITY',
			)
		);
		$this->add_control(
			'primary_button_link',
			array(
				'label'   => __( 'Primary Button Link', 'mw-showband' ),
				'type'    => Controls_Manager::URL, 'dynamic' => array( 'active' => true ),
				'default' => array( 'url' => '#event-builder' ),
			)
		);

		$this->add_control(
			'lang_shortcode',
			array(
				'label'       => __( 'Language Switcher Shortcode (optional)', 'mw-showband' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '[language-switcher]',
				'description' => __( 'Paste your multilingual plugin\'s switcher shortcode to show it in the header (next to the buttons / menu icon). TranslatePress: [language-switcher] · GTranslate: [gtranslate] · WPML: [wpml_language_selector_widget] · Weglot: [weglot_switcher]. Leave empty to show nothing.', 'mw-showband' ),
				'separator'   => 'before',
			)
		);

		$this->end_controls_section();

		$this->register_section_style_controls();

		$this->start_controls_section(
			'mwsb_logo_style',
			array(
				'label' => __( 'Logo', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'logo_width',
			array(
				'label'      => __( 'Width', 'mw-showband' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 10, 'max' => 400 ),
					'%'  => array( 'min' => 1, 'max' => 100 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mwsb-header__logo-img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'logo_height',
			array(
				'label'          => __( 'Height', 'mw-showband' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array( 'min' => 10, 'max' => 200 ),
				),
				'default'        => array( 'unit' => 'px', 'size' => 32 ),
				'tablet_default' => array( 'unit' => 'px', 'size' => 32 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 28 ),
				'selectors'      => array(
					'{{WRAPPER}} .mwsb-header__logo-img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'logo_text_heading',
			array(
				'label'     => __( 'Logo Text', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'logo_text_typography',
				'selector' => '{{WRAPPER}} .mwsb-header__logo-text',
			)
		);

		$this->add_control(
			'logo_text_color',
			array(
				'label'     => __( 'Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-white)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-header__logo-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_nav_style',
			array(
				'label' => __( 'Navigation', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'nav_link_typography',
				'selector' => '{{WRAPPER}} .mwsb-header__link, {{WRAPPER}} .mwsb-header__mobile-link',
			)
		);

		$this->add_control(
			'nav_link_color',
			array(
				'label'     => __( 'Link Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-muted)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-header__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'nav_link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-white)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-header__link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'nav_item_spacing',
			array(
				'label'      => __( 'Item Spacing', 'mw-showband' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array( 'min' => 0, 'max' => 80 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 24 ),
				'selectors'  => array(
					'{{WRAPPER}} .mwsb-header__nav' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_header_buttons_style',
			array(
				'label' => __( 'Buttons', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'ghost_btn_heading',
			array(
				'label' => __( 'Secondary (Ghost) Button', 'mw-showband' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ghost_btn_typography',
				'selector' => '{{WRAPPER}} .mwsb-header__ghost',
			)
		);

		$this->add_control(
			'ghost_btn_text_color',
			array(
				'label'     => __( 'Text Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mwsb-header__ghost' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'ghost_btn_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mwsb-header__ghost',
			)
		);

		$this->add_control(
			'primary_btn_heading',
			array(
				'label'     => __( 'Primary Button', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'primary_btn_typography',
				'selector' => '{{WRAPPER}} .mwsb-header__cta',
			)
		);

		$this->add_control(
			'primary_btn_text_color',
			array(
				'label'     => __( 'Text Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mwsb-header__cta' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'primary_btn_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mwsb-header__cta',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<header class="mwsb-widget mwsb-section mwsb-header">
			<div class="mwsb-container mwsb-header__inner">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mwsb-header__brand">
					<?php if ( ! empty( $s['logo']['url'] ) ) : ?>
						<img src="<?php echo esc_url( $s['logo']['url'] ); ?>" alt="<?php echo esc_attr( $s['logo_text'] ); ?>" class="mwsb-header__logo-img" />
					<?php endif; ?>
					<span class="mwsb-header__logo-text"><?php echo esc_html( $s['logo_text'] ); ?></span>
				</a>

				<nav class="mwsb-header__nav">
					<?php foreach ( $s['nav_items'] as $item ) : ?>
						<a href="<?php echo esc_url( $item['link']['url'] ); ?>" class="mwsb-header__link"><?php echo esc_html( $item['label'] ); ?></a>
					<?php endforeach; ?>
				</nav>

				<div class="mwsb-header__actions">
					<?php $lang_html = $this->render_language_switcher( $s['lang_shortcode'] ); ?>
					<?php if ( '' !== $lang_html ) : ?>
						<div class="mwsb-header__lang notranslate" data-no-translation><?php echo $lang_html; // phpcs:ignore WordPress.Security.EscapeOutput -- shortcode output, set by an admin. ?></div>
					<?php endif; ?>
					<a href="<?php echo esc_url( $s['ghost_button_link']['url'] ); ?>" class="mwsb-btn mwsb-btn--ghost mwsb-header__ghost"><?php echo esc_html( $s['ghost_button_text'] ); ?></a>
					<a href="<?php echo esc_url( $s['primary_button_link']['url'] ); ?>" class="mwsb-btn mwsb-btn--primary mwsb-header__cta"><?php echo esc_html( $s['primary_button_text'] ); ?></a>
					<button type="button" class="mwsb-header__burger" data-mwsb-header-toggle aria-label="<?php esc_attr_e( 'Toggle menu', 'mw-showband' ); ?>" aria-expanded="false">
						<span class="mwsb-header__burger-line"></span>
						<span class="mwsb-header__burger-line"></span>
						<span class="mwsb-header__burger-line"></span>
					</button>
				</div>
			</div>

			<div class="mwsb-header__mobile-panel" data-mwsb-header-panel>
				<nav class="mwsb-header__mobile-nav">
					<?php foreach ( $s['nav_items'] as $item ) : ?>
						<a href="<?php echo esc_url( $item['link']['url'] ); ?>" class="mwsb-header__mobile-link"><?php echo esc_html( $item['label'] ); ?></a>
					<?php endforeach; ?>
				</nav>
				<div class="mwsb-header__mobile-actions">
					<a href="<?php echo esc_url( $s['ghost_button_link']['url'] ); ?>" class="mwsb-btn mwsb-btn--ghost"><?php echo esc_html( $s['ghost_button_text'] ); ?></a>
					<a href="<?php echo esc_url( $s['primary_button_link']['url'] ); ?>" class="mwsb-btn mwsb-btn--primary"><?php echo esc_html( $s['primary_button_text'] ); ?></a>
				</div>
			</div>
		</header>
		<?php
	}
}
