<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;

/**
 * Shared "Section Settings" controls (background surface + vertical spacing)
 * reused across every MW Showband widget for a consistent editing experience.
 *
 * Author: Aqib Dev
 */
trait MWSB_Section_Controls {

	protected function register_section_style_controls() {
		$this->start_controls_section(
			'mwsb_section_style',
			array(
				'label' => __( 'Section Settings', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'mwsb_bg_surface',
			array(
				'label'   => __( 'Background Surface', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'base',
				'options' => array(
					'base'      => __( 'Base (Void)', 'mw-showband' ),
					'elevated'  => __( 'Elevated Card', 'mw-showband' ),
					'container' => __( 'Container Low', 'mw-showband' ),
					'lowest'    => __( 'Container Lowest', 'mw-showband' ),
					'none'      => __( 'Transparent', 'mw-showband' ),
				),
				'selectors_dictionary' => array(
					'base'      => 'var(--mwsb-surface-base)',
					'elevated'  => 'var(--mwsb-surface-elevated)',
					'container' => 'var(--mwsb-surface-container-low)',
					'lowest'    => 'var(--mwsb-surface-container-lowest)',
					'none'      => 'transparent',
				),
				'selectors' => array(
					'{{WRAPPER}} .mwsb-section' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'mwsb_section_padding_y',
			array(
				'label'         => __( 'Vertical Padding (Top/Bottom)', 'mw-showband' ),
				'type'          => Controls_Manager::SLIDER,
				'range'         => array(
					'px' => array( 'min' => 0, 'max' => 200 ),
				),
				'default'       => array( 'unit' => 'px', 'size' => 50 ),
				'tablet_default' => array( 'unit' => 'px', 'size' => 50 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 30 ),
				'selectors'     => array(
					// Applied to the inner content container (which already
					// carries the left/right gutter), not the outer
					// .mwsb-section — so the background box always bleeds
					// edge-to-edge, and X + Y padding live on one element.
					'{{WRAPPER}} .mwsb-container' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'mwsb_section_padding_x',
			array(
				'label'          => __( 'Side Padding (Left/Right)', 'mw-showband' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => array(
					'px' => array( 'min' => 0, 'max' => 160 ),
				),
				'default'        => array( 'unit' => 'px', 'size' => 50 ),
				'tablet_default' => array( 'unit' => 'px', 'size' => 32 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 20 ),
				'description'    => __( 'Matches the header\'s own side gutter by default (both use the same 50px standard), but can be overridden per section if a specific one ever needs to differ.', 'mw-showband' ),
				'selectors'      => array(
					'{{WRAPPER}} .mwsb-container' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Shared "Heading & Text" + "Spacing" Style controls for a widget's title block
	 * (eyebrow, heading, description) so every widget offers the same colour, typography
	 * and gap controls — including per-device gaps, to keep heading→content spacing under
	 * control on tablet/mobile. Nothing here sets a default, so a widget renders exactly as
	 * its stylesheet defines until the editor changes a value.
	 *
	 * @param array $args {
	 *   @type string $eyebrow      Selector (relative to the widget) of the eyebrow; '' to skip.
	 *   @type string $heading      Selector of the heading; '' to skip.
	 *   @type string $desc         Selector of the description paragraph; '' to skip.
	 *   @type string $head         Wrapper of the whole title block — adds a "Header → Content" gap.
	 *   @type string $flow         A flex-column wrapper holding eyebrow/heading/desc — adds ONE gap
	 *                              control (`gap`) instead of the three margin controls below.
	 *   @type string $content_gap  A flex/grid wrapper whose `gap` is the space between big blocks.
	 *   @type bool   $typography   Add typography controls (false when the widget already has them).
	 *   @type bool   $eyebrow_color Add an eyebrow colour control (false if the widget has one).
	 * }
	 */
	protected function register_text_style_controls( array $args = array() ) {
		$a = wp_parse_args(
			$args,
			array(
				'eyebrow'       => '.mwsb-eyebrow',
				'heading'       => '.mwsb-heading-display',
				'desc'          => '',
				'head'          => '',
				'flow'          => '',
				'content_gap'   => '',
				'typography'    => true,
				'eyebrow_color' => true,
			)
		);

		$this->start_controls_section(
			'mwsbx_text_style',
			array(
				'label' => __( 'Heading & Text', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$blocks = array(
			'eyebrow' => __( 'Eyebrow', 'mw-showband' ),
			'heading' => __( 'Heading', 'mw-showband' ),
			'desc'    => __( 'Description', 'mw-showband' ),
		);
		$first = true;
		foreach ( $blocks as $key => $label ) {
			if ( empty( $a[ $key ] ) ) {
				continue;
			}
			$selector = '{{WRAPPER}} ' . $a[ $key ];

			$this->add_control(
				'mwsbx_' . $key . '_heading',
				array(
					'label'     => $label,
					'type'      => Controls_Manager::HEADING,
					'separator' => $first ? 'none' : 'before',
				)
			);
			$first = false;

			if ( 'eyebrow' !== $key || $a['eyebrow_color'] ) {
				$this->add_control(
					'mwsbx_' . $key . '_color',
					array(
						'label'     => __( 'Color', 'mw-showband' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array( $selector => 'color: {{VALUE}};' ),
					)
				);
			}

			if ( $a['typography'] ) {
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'     => 'mwsbx_' . $key . '_typography',
						'selector' => $selector,
					)
				);
			}
		}

		$this->end_controls_section();

		// Spacing: each control is responsive (desktop / tablet / mobile).
		$has_gap = $a['flow'] || $a['head'] || $a['content_gap'] || $a['desc'] || $a['eyebrow'];
		if ( ! $has_gap ) {
			return;
		}

		$this->start_controls_section(
			'mwsbx_spacing_style',
			array(
				'label' => __( 'Spacing', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$slider = function ( $id, $label, $selector, $property, $max = 160 ) {
			$this->add_responsive_control(
				$id,
				array(
					'label'      => $label,
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', 'em' ),
					'range'      => array(
						'px' => array( 'min' => 0, 'max' => $max ),
						'em' => array( 'min' => 0, 'max' => 10, 'step' => 0.1 ),
					),
					'selectors'  => array( '{{WRAPPER}} ' . $selector => $property . ': {{SIZE}}{{UNIT}};' ),
				)
			);
		};

		if ( $a['flow'] ) {
			$slider( 'mwsbx_gap_flow', __( 'Gap Between Eyebrow, Heading & Description', 'mw-showband' ), $a['flow'], 'gap', 80 );
		} else {
			if ( $a['eyebrow'] ) {
				$slider( 'mwsbx_gap_eyebrow', __( 'Eyebrow → Heading Gap', 'mw-showband' ), $a['eyebrow'], 'margin-bottom', 80 );
			}
			if ( $a['desc'] ) {
				$slider( 'mwsbx_gap_desc', __( 'Heading → Description Gap', 'mw-showband' ), $a['desc'], 'margin-top', 80 );
			}
		}
		if ( $a['head'] ) {
			$slider( 'mwsbx_gap_head', __( 'Header → Content Gap', 'mw-showband' ), $a['head'], 'margin-bottom', 200 );
		}
		if ( $a['content_gap'] ) {
			$slider( 'mwsbx_gap_content', __( 'Gap Between Content Blocks', 'mw-showband' ), $a['content_gap'], 'gap', 160 );
		}

		if ( $a['head'] ) {
			$this->add_responsive_control(
				'mwsbx_head_align',
				array(
					'label'     => __( 'Header Alignment', 'mw-showband' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array( 'title' => __( 'Left', 'mw-showband' ), 'icon' => 'eicon-text-align-left' ),
						'center' => array( 'title' => __( 'Center', 'mw-showband' ), 'icon' => 'eicon-text-align-center' ),
						'right'  => array( 'title' => __( 'Right', 'mw-showband' ), 'icon' => 'eicon-text-align-right' ),
					),
					'selectors' => array( '{{WRAPPER}} ' . $a['head'] => 'text-align: {{VALUE}};' ),
				)
			);
		}

		$this->end_controls_section();
	}

	/**
	 * Elementor-style "Background Overlay" + image filters/position for any widget that paints a
	 * background image. Pair it with ONE extra element in render(), placed right after the image
	 * (and any default scrim) and below the content:
	 *
	 *     <div class="mwsb-bg-overlay"></div>
	 *
	 * That layer has no background of its own, so a widget looks exactly as before until an overlay
	 * is chosen. Because it is a separate layer (not the widget's old gradient scrim) a plain colour
	 * or a gradient always works — nothing underneath fights it, which is why the old "Darkness"
	 * sliders felt broken.
	 *
	 * @param string $bg Selector of the image element (relative to the widget wrapper).
	 */
	protected function register_bg_overlay_controls( $bg ) {
		$this->start_controls_section(
			'mwsbx_bg_overlay_section',
			array(
				'label' => __( 'Background Overlay', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'mwsbx_overlay',
				'label'    => __( 'Overlay', 'mw-showband' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mwsb-bg-overlay',
			)
		);

		// Default .5 is harmless until a colour is picked (the layer has no background of its own),
		// and gives a sensible half-strength overlay the moment one is.
		$this->add_control(
			'mwsbx_overlay_opacity',
			array(
				'label'     => __( 'Overlay Opacity', 'mw-showband' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 1, 'step' => 0.05 ) ),
				'default'   => array( 'size' => 0.5 ),
				'selectors' => array( '{{WRAPPER}} .mwsb-bg-overlay' => 'opacity: {{SIZE}};' ),
			)
		);

		$this->add_control(
			'mwsbx_overlay_blend',
			array(
				'label'     => __( 'Blend Mode', 'mw-showband' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''            => __( 'Normal', 'mw-showband' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'color-burn'  => 'Color Burn',
					'hard-light'  => 'Hard Light',
					'soft-light'  => 'Soft Light',
					'difference'  => 'Difference',
					'exclusion'   => 'Exclusion',
					'hue'         => 'Hue',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'luminosity'  => 'Luminosity',
				),
				'selectors' => array( '{{WRAPPER}} .mwsb-bg-overlay' => 'mix-blend-mode: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'mwsbx_bg_filters_heading',
			array(
				'label'     => __( 'Background Image', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Replaces the stylesheet's built-in brightness/grayscale filter once changed.
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'mwsbx_bg_filters',
				'selector' => '{{WRAPPER}} ' . $bg,
			)
		);

		$this->add_responsive_control(
			'mwsbx_bg_position',
			array(
				'label'     => __( 'Image Position', 'mw-showband' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''              => __( 'Default', 'mw-showband' ),
					'center center' => __( 'Center', 'mw-showband' ),
					'top center'    => __( 'Top', 'mw-showband' ),
					'bottom center' => __( 'Bottom', 'mw-showband' ),
					'center left'   => __( 'Left', 'mw-showband' ),
					'center right'  => __( 'Right', 'mw-showband' ),
				),
				'selectors' => array( '{{WRAPPER}} ' . $bg => 'background-position: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'mwsbx_bg_size',
			array(
				'label'     => __( 'Image Size', 'mw-showband' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''        => __( 'Default', 'mw-showband' ),
					'cover'   => __( 'Cover', 'mw-showband' ),
					'contain' => __( 'Contain', 'mw-showband' ),
					'auto'    => __( 'Auto', 'mw-showband' ),
				),
				'selectors' => array( '{{WRAPPER}} ' . $bg => 'background-size: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Output of a language-switcher shortcode (GTranslate, TranslatePress, WPML, Weglot...), or ''.
	 * Runs the shortcode only when its plugin has actually registered it: do_shortcode() leaves an
	 * unknown tag in place, so with the plugin deactivated the page would otherwise print the raw
	 * text "[gtranslate]".
	 */
	protected function render_language_switcher( $shortcode ) {
		$shortcode = trim( (string) $shortcode );
		if ( '' === $shortcode || ! preg_match( '/^\[\s*([^\s\]\/]+)/', $shortcode, $m ) || ! shortcode_exists( $m[1] ) ) {
			return '';
		}
		return do_shortcode( $shortcode );
	}

	/**
	 * Hero banner layout: min height + where the content block sits vertically (top / centre /
	 * bottom), per device. The hero element is a flex row, so `align-items` is the vertical position.
	 * No defaults: each hero's stylesheet already centres its content.
	 *
	 * @param string $hero Selector of the hero <section> (relative to the widget wrapper).
	 */
	protected function register_hero_layout_controls( $hero ) {
		$this->start_controls_section(
			'mwsbx_hero_layout',
			array(
				'label' => __( 'Hero Layout', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'mwsbx_hero_min_height',
			array(
				'label'      => __( 'Min Height', 'mw-showband' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array( 'min' => 200, 'max' => 1200 ),
					'vh' => array( 'min' => 20, 'max' => 100 ),
				),
				'selectors'  => array( '{{WRAPPER}} ' . $hero => 'min-height: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'mwsbx_hero_vertical',
			array(
				'label'     => __( 'Content Vertical Position', 'mw-showband' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array( 'title' => __( 'Top', 'mw-showband' ), 'icon' => 'eicon-v-align-top' ),
					'center'     => array( 'title' => __( 'Middle', 'mw-showband' ), 'icon' => 'eicon-v-align-middle' ),
					'flex-end'   => array( 'title' => __( 'Bottom', 'mw-showband' ), 'icon' => 'eicon-v-align-bottom' ),
				),
				'selectors' => array( '{{WRAPPER}} ' . $hero => 'align-items: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}
}
