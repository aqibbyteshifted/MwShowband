<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

/**
 * Reusable Elementor "Style" tab builders for the proposal-page widgets, so every
 * proposal widget exposes the same, familiar controls (typography, colours, border,
 * radius, shadow, spacing) per element instead of hardcoding them in proposal.css.
 *
 * Design contract — controls never change the default look:
 *   - No control sets a default that differs from proposal.css. A control only emits
 *     CSS once the editor actually changes it, so an untouched widget renders exactly
 *     as the CSS file defines it.
 *   - Every selector is prefixed with {{WRAPPER}}, which out-ranks the single-class
 *     rules in proposal.css, so an edited value always wins without !important.
 *
 * Usage inside register_controls():
 *   $this->mwsb_prop_section_start( 'style_title', __( 'Title', 'mw-showband' ) );
 *   $this->mwsb_prop_text_controls( 'title', '.mwsb-prop-heading', array( 'hover' => false ) );
 *   $this->mwsb_prop_section_end();
 *
 * Author: Aqib Dev
 */
trait MWSB_Proposal_Style_Controls {

	protected function mwsb_prop_section_start( $key, $label, array $condition = array() ) {
		$args = array(
			'label' => $label,
			'tab'   => Controls_Manager::TAB_STYLE,
		);
		if ( $condition ) {
			$args['condition'] = $condition;
		}
		$this->start_controls_section( $key, $args );
	}

	protected function mwsb_prop_section_end() {
		$this->end_controls_section();
	}

	/** A small divider heading to group controls inside one section. */
	protected function mwsb_prop_heading( $key, $label ) {
		$this->add_control(
			$key,
			array(
				'label'     => $label,
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
	}

	/** Responsive padding / margin / radius style box. */
	protected function mwsb_prop_dimensions( $key, $label, $selector, $property = 'padding' ) {
		$this->add_responsive_control(
			$key,
			array(
				'label'      => $label,
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => $property . ': {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	/** Responsive single-value slider (gap, size, width, height, opacity...). */
	protected function mwsb_prop_slider( $key, $label, $selector, $property, array $range = array(), array $units = array( 'px' ) ) {
		if ( ! $range ) {
			$range = array( 'px' => array( 'min' => 0, 'max' => 200 ) );
		}
		$this->add_responsive_control(
			$key,
			array(
				'label'      => $label,
				'type'       => Controls_Manager::SLIDER,
				'size_units' => $units,
				'range'      => $range,
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => $property . ': {{SIZE}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Typography + colour (+ optional hover colour and margin) for one text element.
	 *
	 * @param array $args color (bool), hover (bool), spacing (bool).
	 */
	protected function mwsb_prop_text_controls( $key, $selector, array $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'color'   => true,
				'hover'   => false,
				'spacing' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => $key . '_typography',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);

		if ( $args['color'] ) {
			$this->add_control(
				$key . '_color',
				array(
					'label'     => __( 'Text Color', 'mw-showband' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $selector => 'color: {{VALUE}};',
					),
				)
			);
		}

		if ( $args['hover'] ) {
			$this->add_control(
				$key . '_color_hover',
				array(
					'label'     => __( 'Text Hover Color', 'mw-showband' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $selector . ':hover' => 'color: {{VALUE}};',
					),
				)
			);
		}

		if ( $args['spacing'] ) {
			$this->mwsb_prop_dimensions( $key . '_margin', __( 'Margin', 'mw-showband' ), $selector, 'margin' );
		}
	}

	/**
	 * Background, border, radius, shadow and padding for one box (pill, card, strip...).
	 *
	 * @param array $args background, hover_bg, border, radius, shadow, padding (all bool).
	 */
	protected function mwsb_prop_box_controls( $key, $selector, array $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'background' => true,
				'hover_bg'   => false,
				'border'     => true,
				'radius'     => true,
				'shadow'     => true,
				'padding'    => true,
			)
		);

		if ( $args['background'] ) {
			$this->add_control(
				$key . '_bg',
				array(
					'label'     => __( 'Background Color', 'mw-showband' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $selector => 'background-color: {{VALUE}};',
					),
				)
			);
		}

		if ( $args['hover_bg'] ) {
			$this->add_control(
				$key . '_bg_hover',
				array(
					'label'     => __( 'Hover Background Color', 'mw-showband' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $selector . ':hover' => 'background-color: {{VALUE}};',
					),
				)
			);
		}

		if ( $args['border'] ) {
			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => $key . '_border',
					'selector' => '{{WRAPPER}} ' . $selector,
				)
			);
		}

		if ( $args['radius'] ) {
			$this->mwsb_prop_dimensions( $key . '_radius', __( 'Border Radius', 'mw-showband' ), $selector, 'border-radius' );
		}

		if ( $args['shadow'] ) {
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => $key . '_shadow',
					'selector' => '{{WRAPPER}} ' . $selector,
				)
			);
		}

		if ( $args['padding'] ) {
			$this->mwsb_prop_dimensions( $key . '_padding', __( 'Padding', 'mw-showband' ), $selector, 'padding' );
		}
	}

	/**
	 * "Hide this element" switch, per device (desktop / tablet / mobile). Emits display:none only
	 * while on, so a hidden element simply isn't rendered visually — the markup stays intact.
	 */
	protected function mwsb_prop_hide_switch( $key, $label, $selector ) {
		$this->add_responsive_control(
			$key,
			array(
				'label'        => $label,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Hide', 'mw-showband' ),
				'label_off'    => __( 'Show', 'mw-showband' ),
				'return_value' => 'none',
				'selectors'    => array(
					'{{WRAPPER}} ' . $selector => 'display: {{VALUE}};',
				),
			)
		);
	}

	/** Hover/transition speed for one element, in seconds. */
	protected function mwsb_prop_transition_control( $key, $selector, $label = '' ) {
		$this->add_control(
			$key,
			array(
				'label'      => $label ? $label : __( 'Transition Speed (s)', 'mw-showband' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 's' ),
				'range'      => array( 's' => array( 'min' => 0, 'max' => 2, 'step' => 0.05 ) ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => 'transition-duration: {{SIZE}}s;',
				),
			)
		);
	}

	/**
	 * Image skin: frame height, fit, radius and a switch to turn the hover zoom off.
	 *
	 * @param string $frame        Selector of the box that crops the image (gets height + radius).
	 * @param string $img          Selector of the <img>.
	 * @param string $hover_parent Selector of the element whose :hover zooms the image ('' to skip the switch).
	 */
	protected function mwsb_prop_image_controls( $key, $frame, $img, $hover_parent = '' ) {
		$this->mwsb_prop_slider( $key . '_height', __( 'Height', 'mw-showband' ), $frame, 'height', array( 'px' => array( 'min' => 60, 'max' => 800 ), 'vh' => array( 'min' => 10, 'max' => 100 ) ), array( 'px', 'vh' ) );

		$this->add_control(
			$key . '_fit',
			array(
				'label'     => __( 'Image Fit', 'mw-showband' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''        => __( 'Default', 'mw-showband' ),
					'cover'   => __( 'Cover (fill, crop)', 'mw-showband' ),
					'contain' => __( 'Contain (show all)', 'mw-showband' ),
					'fill'    => __( 'Stretch', 'mw-showband' ),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $img => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->mwsb_prop_dimensions( $key . '_radius', __( 'Border Radius', 'mw-showband' ), $frame, 'border-radius' );

		if ( $hover_parent ) {
			$this->add_control(
				$key . '_no_zoom',
				array(
					'label'        => __( 'Disable Hover Zoom', 'mw-showband' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'none',
					'selectors'    => array(
						'{{WRAPPER}} ' . $hover_parent . ':hover ' . $img => 'transform: {{VALUE}};',
					),
				)
			);
		}
	}

	/**
	 * Controls for the standard centred/left section heading block (.mwsb-prop-head):
	 * eyebrow, title, description, the gaps between them, header→content gap, width, alignment.
	 */
	protected function mwsb_prop_section_head_controls( $key = 'head' ) {
		$head = '.mwsb-prop-head';

		$this->mwsb_prop_section_start( 'style_' . $key, __( 'Section Heading', 'mw-showband' ) );

		$this->mwsb_prop_heading( $key . '_eyebrow_h', __( 'Eyebrow', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( $key . '_eyebrow', $head . ' .mwsb-prop-eyebrow' );
		$this->mwsb_prop_heading( $key . '_title_h', __( 'Title', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( $key . '_title', $head . ' .mwsb-prop-heading' );
		$this->mwsb_prop_heading( $key . '_desc_h', __( 'Description', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( $key . '_desc', $head . ' .mwsb-prop-desc' );

		$this->mwsb_prop_heading( $key . '_space_h', __( 'Spacing & Layout', 'mw-showband' ) );
		$this->mwsb_prop_slider( $key . '_gap', __( 'Gap Between Eyebrow, Title & Description', 'mw-showband' ), $head, 'gap', array( 'px' => array( 'min' => 0, 'max' => 80 ) ) );
		$this->mwsb_prop_slider( $key . '_margin_bottom', __( 'Header → Content Gap', 'mw-showband' ), $head, 'margin-bottom', array( 'px' => array( 'min' => 0, 'max' => 200 ) ) );
		$this->mwsb_prop_slider( $key . '_max_width', __( 'Header Max Width', 'mw-showband' ), $head, 'max-width', array( 'px' => array( 'min' => 200, 'max' => 1600 ), '%' => array( 'min' => 20, 'max' => 100 ) ), array( 'px', '%' ) );
		$this->add_responsive_control(
			$key . '_align',
			array(
				'label'     => __( 'Alignment', 'mw-showband' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array( 'title' => __( 'Left', 'mw-showband' ), 'icon' => 'eicon-text-align-left' ),
					'center' => array( 'title' => __( 'Center', 'mw-showband' ), 'icon' => 'eicon-text-align-center' ),
					'end'    => array( 'title' => __( 'Right', 'mw-showband' ), 'icon' => 'eicon-text-align-right' ),
				),
				// `start|center|end` is valid for both properties: text lines align, and the block itself
				// (desc has a max-width) aligns inside the flex-column header.
				'selectors' => array(
					'{{WRAPPER}} ' . $head => 'text-align: {{VALUE}}; align-items: {{VALUE}};',
				),
			)
		);

		$this->mwsb_prop_heading( $key . '_hide_h', __( 'Visibility', 'mw-showband' ) );
		$this->mwsb_prop_hide_switch( $key . '_hide_eyebrow', __( 'Hide Eyebrow', 'mw-showband' ), $head . ' .mwsb-prop-eyebrow' );
		$this->mwsb_prop_hide_switch( $key . '_hide_desc', __( 'Hide Description', 'mw-showband' ), $head . ' .mwsb-prop-desc' );

		$this->mwsb_prop_section_end();
	}

	/**
	 * Section-level layout: min height + content max width (the 3-class selector out-ranks the
	 * `.mwsb-prop .mwsb-container { max-width: none }` default in proposal.css).
	 */
	protected function mwsb_prop_layout_controls( $key, $section_selector ) {
		$this->mwsb_prop_slider( $key . '_min_height', __( 'Min Height', 'mw-showband' ), $section_selector, 'min-height', array( 'px' => array( 'min' => 0, 'max' => 1200 ), 'vh' => array( 'min' => 0, 'max' => 100 ) ), array( 'px', 'vh' ) );
		$this->mwsb_prop_slider( $key . '_max_width', __( 'Content Max Width', 'mw-showband' ), '.mwsb-prop .mwsb-container', 'max-width', array( 'px' => array( 'min' => 600, 'max' => 2000 ), '%' => array( 'min' => 40, 'max' => 100 ) ), array( 'px', '%' ) );
	}

	/** Normal / Hover button skin: typography, colours, border, shadow, size. */
	protected function mwsb_prop_button_controls( $key, $selector ) {
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => $key . '_typography',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);

		$this->start_controls_tabs( $key . '_tabs' );

		$this->start_controls_tab( $key . '_tab_normal', array( 'label' => __( 'Normal', 'mw-showband' ) ) );
		$this->add_control(
			$key . '_color',
			array(
				'label'     => __( 'Text Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} ' . $selector => 'color: {{VALUE}};' ),
			)
		);
		$this->add_control(
			$key . '_bg',
			array(
				'label'     => __( 'Background Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				// `background` shorthand (not background-color): the site's buttons are painted with a
				// gradient (background-image), which a plain background-color would sit invisibly under.
				'selectors' => array( '{{WRAPPER}} ' . $selector => 'background: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => $key . '_border',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => $key . '_shadow',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab( $key . '_tab_hover', array( 'label' => __( 'Hover', 'mw-showband' ) ) );
		$this->add_control(
			$key . '_color_hover',
			array(
				'label'     => __( 'Text Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} ' . $selector . ':hover' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_control(
			$key . '_bg_hover',
			array(
				'label'     => __( 'Background Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} ' . $selector . ':hover' => 'background: {{VALUE}};' ),
			)
		);
		$this->add_control(
			$key . '_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} ' . $selector . ':hover' => 'border-color: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => $key . '_shadow_hover',
				'selector' => '{{WRAPPER}} ' . $selector . ':hover',
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->mwsb_prop_dimensions( $key . '_radius', __( 'Border Radius', 'mw-showband' ), $selector, 'border-radius' );
		$this->mwsb_prop_dimensions( $key . '_padding', __( 'Padding', 'mw-showband' ), $selector, 'padding' );
		$this->mwsb_prop_slider( $key . '_height', __( 'Height', 'mw-showband' ), $selector, 'height', array( 'px' => array( 'min' => 24, 'max' => 120 ) ) );
	}
}
