<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

/**
 * MW Showband — "Thank You" page: animated confirmation mark, headline with a gradient accent
 * line, short message, a three-step "what happens next" row, call-to-action buttons and quick
 * contact links. Built to be the destination of the contact form (see the form's "After Submit"
 * setting) and to be dropped onto an Elementor Canvas page — see class-thankyou-page-builder.php.
 * Uses the site's shared tokens, buttons and typography so it matches every other page.
 * Author: Aqib Dev
 */
class MWSB_Thank_You_Widget extends Widget_Base {

	use MWSB_Section_Controls;
	use MWSB_Proposal_Style_Controls;

	public function get_name() {
		return 'mwsb-thank-you';
	}

	public function get_title() {
		return __( 'MW Thank You', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-check-circle';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	public function get_style_depends() {
		return array( 'mwsb-tokens', 'mwsb-thankyou' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'content_main', array( 'label' => __( 'Message', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MESSAGE RECEIVED' ) );
		$this->add_control( 'title_tag', array( 'label' => __( 'Title HTML Tag', 'mw-showband' ), 'type' => Controls_Manager::SELECT, 'default' => 'h1', 'options' => array( 'h1' => 'H1', 'h2' => 'H2', 'div' => 'div' ) ) );
		$this->add_control( 'title_white', array( 'label' => __( 'Title — White Line', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'THANK YOU.' ) );
		$this->add_control( 'title_gradient', array( 'label' => __( 'Title — Gradient Line', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => "WE'LL BE IN TOUCH SOON." ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Your enquiry has landed with the MW Showband team. One of us reads every message personally and will get back to you within 2 hours during UK business hours.' ) );
		$this->add_control( 'note_text', array( 'label' => __( 'Footnote', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MW Showband Ltd. • Your details are never shared.' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'content_steps', array( 'label' => __( 'What Happens Next', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'show_steps', array( 'label' => __( 'Show Steps', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'steps_heading', array( 'label' => __( 'Steps Heading', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WHAT HAPPENS NEXT', 'condition' => array( 'show_steps' => 'yes' ) ) );
		$repeater = new Repeater();
		$repeater->add_control( 'icon', array( 'label' => __( 'Icon', 'mw-showband' ), 'type' => Controls_Manager::ICONS, 'default' => array( 'value' => 'fas fa-envelope-open-text', 'library' => 'fa-solid' ) ) );
		$repeater->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WE REVIEW' ) );
		$repeater->add_control( 'text', array( 'label' => __( 'Text', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => "Marlon or Wayne reads your enquiry personally: your date, venue and the kind of night you're planning." ) );
		$this->add_control(
			'steps',
			array(
				'label'       => __( 'Steps', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'icon' => array( 'value' => 'fas fa-envelope-open-text', 'library' => 'fa-solid' ), 'title' => 'WE REVIEW', 'text' => "Marlon or Wayne reads your enquiry personally: your date, venue and the kind of night you're planning." ),
					array( 'icon' => array( 'value' => 'fas fa-phone', 'library' => 'fa-solid' ), 'title' => 'WE REACH OUT', 'text' => 'Expect a call or email within 2 hours to talk through your vision, line-up and logistics.' ),
					array( 'icon' => array( 'value' => 'fas fa-file-signature', 'library' => 'fa-solid' ), 'title' => 'YOUR BESPOKE PROPOSAL', 'text' => 'A tailored line-up, timeline and pricing proposal for your event, with no obligation.' ),
				),
				'title_field' => '{{{ title }}}',
				'condition'   => array( 'show_steps' => 'yes' ),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'content_actions', array( 'label' => __( 'Buttons & Contact', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'primary_text', array( 'label' => __( 'Primary Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'BACK TO HOME' ) );
		$this->add_control( 'primary_link', array( 'label' => __( 'Primary Button Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => home_url( '/' ) ) ) );
		$this->add_control( 'ghost_text', array( 'label' => __( 'Secondary Button Text (optional)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WATCH THE SHOW' ) );
		$this->add_control( 'ghost_link', array( 'label' => __( 'Secondary Button Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => home_url( '/#media-vault' ) ), 'condition' => array( 'ghost_text!' => '' ) ) );
		$this->add_control( 'contact_phone', array( 'label' => __( 'Phone (optional)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '+44 7707 159384', 'separator' => 'before' ) );
		$this->add_control( 'contact_phone_link', array( 'label' => __( 'Phone Link', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'default' => 'tel:+447707159384', 'condition' => array( 'contact_phone!' => '' ) ) );
		$this->add_control( 'contact_email', array( 'label' => __( 'Email (optional)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'info@mw-showband.com' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'content_bg', array( 'label' => __( 'Background Image (optional)', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'bg_image', array( 'label' => __( 'Background Image', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => '' ) ) );
		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_thankyou_style_controls();
	}

	/** Style tab: layout, one section per element, background overlay. */
	private function register_thankyou_style_controls() {
		// Layout.
		$this->mwsb_prop_section_start( 'style_layout', __( 'Layout', 'mw-showband' ) );
		$this->mwsb_prop_slider( 'layout_min_height', __( 'Min Height', 'mw-showband' ), '.mwsb-ty', 'min-height', array( 'px' => array( 'min' => 300, 'max' => 1400 ), 'vh' => array( 'min' => 30, 'max' => 100 ) ), array( 'px', 'vh' ) );
		$this->mwsb_prop_slider( 'layout_width', __( 'Content Max Width', 'mw-showband' ), '.mwsb-ty__inner', 'max-width', array( 'px' => array( 'min' => 400, 'max' => 1400 ), '%' => array( 'min' => 40, 'max' => 100 ) ), array( 'px', '%' ) );
		$this->mwsb_prop_slider( 'layout_gap', __( 'Gap Between Blocks', 'mw-showband' ), '.mwsb-ty__inner', 'gap', array( 'px' => array( 'min' => 0, 'max' => 100 ) ) );
		$this->mwsb_prop_section_end();

		// Check mark.
		$this->mwsb_prop_section_start( 'style_check', __( 'Confirmation Mark', 'mw-showband' ) );
		$this->add_control( 'check_color', array( 'label' => __( 'Check Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-ty__check' => 'color: {{VALUE}};' ) ) );
		$this->mwsb_prop_box_controls( 'check_box', '.mwsb-ty__check', array( 'padding' => false ) );
		$this->add_responsive_control(
			'check_size',
			array(
				'label'      => __( 'Size', 'mw-showband' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 48, 'max' => 200 ) ),
				// Width AND height together so the circle stays a circle.
				'selectors'  => array( '{{WRAPPER}} .mwsb-ty__check' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->mwsb_prop_hide_switch( 'check_hide', __( 'Hide Confirmation Mark', 'mw-showband' ), '.mwsb-ty__check' );
		$this->mwsb_prop_section_end();

		// Text blocks.
		$this->mwsb_prop_section_start( 'style_text', __( 'Heading & Text', 'mw-showband' ) );
		$this->mwsb_prop_heading( 'eyebrow_h', __( 'Eyebrow', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'eyebrow', '.mwsb-ty__eyebrow', array( 'spacing' => true ) );
		$this->mwsb_prop_heading( 'title_h', __( 'Title', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'title', '.mwsb-ty__title', array( 'spacing' => true ) );
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'title_gradient_bg',
				'label'    => __( 'Gradient Line Colors', 'mw-showband' ),
				'types'    => array( 'gradient' ),
				'selector' => '{{WRAPPER}} .mwsb-ty__title .mwsb-gradient-text',
			)
		);
		$this->mwsb_prop_heading( 'desc_h', __( 'Description', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'desc', '.mwsb-ty__desc', array( 'spacing' => true ) );
		$this->mwsb_prop_slider( 'desc_width', __( 'Description Max Width', 'mw-showband' ), '.mwsb-ty__desc', 'max-width', array( 'px' => array( 'min' => 200, 'max' => 1200 ), '%' => array( 'min' => 20, 'max' => 100 ) ), array( 'px', '%' ) );
		$this->mwsb_prop_heading( 'note_h', __( 'Footnote', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'note', '.mwsb-ty__note' );
		$this->mwsb_prop_section_end();

		// Steps.
		$this->mwsb_prop_section_start( 'style_steps', __( 'Next Steps', 'mw-showband' ), array( 'show_steps' => 'yes' ) );
		$this->add_responsive_control(
			'steps_columns',
			array(
				'label'     => __( 'Columns', 'mw-showband' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array( '' => __( 'Default', 'mw-showband' ), '1' => '1', '2' => '2', '3' => '3', '4' => '4' ),
				'selectors' => array( '{{WRAPPER}} .mwsb-ty__steps' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ),
			)
		);
		$this->mwsb_prop_slider( 'steps_gap', __( 'Gap Between Cards', 'mw-showband' ), '.mwsb-ty__steps', 'gap', array( 'px' => array( 'min' => 0, 'max' => 80 ) ) );
		$this->mwsb_prop_heading( 'steps_heading_h', __( 'Section Label', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'steps_label', '.mwsb-ty__steps-label' );
		$this->mwsb_prop_heading( 'step_card_h', __( 'Card', 'mw-showband' ) );
		$this->mwsb_prop_box_controls( 'step_box', '.mwsb-ty-step', array( 'hover_bg' => true ) );
		$this->mwsb_prop_transition_control( 'step_transition', '.mwsb-ty-step' );
		$this->mwsb_prop_heading( 'step_num_h', __( 'Number', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'step_num', '.mwsb-ty-step__num' );
		$this->mwsb_prop_heading( 'step_icon_h', __( 'Icon', 'mw-showband' ) );
		$this->add_control( 'step_icon_color', array( 'label' => __( 'Icon Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-ty-step__icon' => 'color: {{VALUE}};' ) ) );
		$this->mwsb_prop_slider( 'step_icon_size', __( 'Icon Size', 'mw-showband' ), '.mwsb-ty-step__icon', 'font-size', array( 'px' => array( 'min' => 12, 'max' => 64 ) ) );
		$this->mwsb_prop_heading( 'step_title_h', __( 'Title', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'step_title', '.mwsb-ty-step__title' );
		$this->mwsb_prop_heading( 'step_text_h', __( 'Text', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'step_text', '.mwsb-ty-step__text' );
		$this->mwsb_prop_section_end();

		// Buttons + contact links.
		$this->mwsb_prop_section_start( 'style_buttons', __( 'Buttons & Contact', 'mw-showband' ) );
		$this->mwsb_prop_slider( 'buttons_gap', __( 'Gap Between Buttons', 'mw-showband' ), '.mwsb-ty__actions', 'gap', array( 'px' => array( 'min' => 0, 'max' => 60 ) ) );
		$this->mwsb_prop_heading( 'btn_primary_h', __( 'Primary Button', 'mw-showband' ) );
		$this->mwsb_prop_button_controls( 'btn_primary', '.mwsb-ty__actions .mwsb-btn--primary' );
		$this->mwsb_prop_heading( 'btn_ghost_h', __( 'Secondary Button', 'mw-showband' ) );
		$this->mwsb_prop_button_controls( 'btn_ghost', '.mwsb-ty__actions .mwsb-btn--ghost' );
		$this->mwsb_prop_heading( 'contact_h', __( 'Phone & Email Links', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'contact', '.mwsb-ty__contact a', array( 'hover' => true ) );
		$this->add_control( 'contact_icon_color', array( 'label' => __( 'Contact Icon Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-ty__contact .mwsb-ty-icon' => 'color: {{VALUE}};' ) ) );
		$this->mwsb_prop_section_end();

		// Glow orbs.
		$this->mwsb_prop_section_start( 'style_glow', __( 'Glow Effects', 'mw-showband' ) );
		$this->add_control( 'glow_a_color', array( 'label' => __( 'Glow A Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-ty__glow--a' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'glow_b_color', array( 'label' => __( 'Glow B Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-ty__glow--b' => 'background-color: {{VALUE}};' ) ) );
		$this->mwsb_prop_hide_switch( 'glows_hide', __( 'Hide Glow Effects', 'mw-showband' ), '.mwsb-ty__glow' );
		$this->mwsb_prop_section_end();

		// Elementor-style background overlay + image filters.
		$this->register_bg_overlay_controls( '.mwsb-ty__bg' );
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$tag   = in_array( $s['title_tag'], array( 'h1', 'h2', 'div' ), true ) ? $s['title_tag'] : 'h1';
		$steps = ( 'yes' === $s['show_steps'] && ! empty( $s['steps'] ) ) ? $s['steps'] : array();
		?>
		<section class="mwsb-widget mwsb-section mwsb-ty">
			<?php if ( ! empty( $s['bg_image']['url'] ) ) : ?>
				<div class="mwsb-ty__bg" style="background-image:url('<?php echo esc_url( $s['bg_image']['url'] ); ?>');"></div>
			<?php endif; ?>
			<div class="mwsb-bg-overlay"></div>
			<div class="mwsb-ty__glow mwsb-ty__glow--a"></div>
			<div class="mwsb-ty__glow mwsb-ty__glow--b"></div>

			<div class="mwsb-container mwsb-ty__inner">
				<div class="mwsb-ty__check" role="img" aria-label="<?php esc_attr_e( 'Message sent', 'mw-showband' ); ?>">
					<svg viewBox="0 0 52 52" aria-hidden="true" focusable="false">
						<circle class="mwsb-ty__check-ring" cx="26" cy="26" r="24" fill="none" stroke="currentColor" stroke-width="2" />
						<path class="mwsb-ty__check-tick" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" d="M15 27l8 8 14-16" />
					</svg>
				</div>

				<?php if ( ! empty( $s['eyebrow'] ) ) : ?>
					<span class="mwsb-eyebrow mwsb-ty__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
				<?php endif; ?>

				<<?php echo esc_html( $tag ); ?> class="mwsb-heading-display mwsb-heading-display--hero mwsb-ty__title">
					<?php echo esc_html( $s['title_white'] ); ?><br />
					<span class="mwsb-gradient-text"><?php echo esc_html( $s['title_gradient'] ); ?></span>
				</<?php echo esc_html( $tag ); ?>>

				<?php if ( ! empty( $s['description'] ) ) : ?>
					<p class="mwsb-ty__desc"><?php echo esc_html( $s['description'] ); ?></p>
				<?php endif; ?>

				<?php if ( $steps ) : ?>
					<?php if ( ! empty( $s['steps_heading'] ) ) : ?>
						<span class="mwsb-ty__steps-label"><?php echo esc_html( $s['steps_heading'] ); ?></span>
					<?php endif; ?>
					<div class="mwsb-ty__steps">
						<?php foreach ( $steps as $i => $step ) : ?>
							<div class="mwsb-ty-step">
								<div class="mwsb-ty-step__top">
									<span class="mwsb-ty-step__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
									<span class="mwsb-ty-step__icon">
										<?php Icons_Manager::render_icon( $step['icon'], array( 'aria-hidden' => 'true', 'class' => 'mwsb-ty-icon' ) ); ?>
									</span>
								</div>
								<h3 class="mwsb-ty-step__title"><?php echo esc_html( $step['title'] ); ?></h3>
								<p class="mwsb-ty-step__text"><?php echo esc_html( $step['text'] ); ?></p>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $s['primary_text'] ) || ! empty( $s['ghost_text'] ) ) : ?>
					<div class="mwsb-ty__actions">
						<?php if ( ! empty( $s['primary_text'] ) ) : ?>
							<a class="mwsb-btn mwsb-btn--primary" href="<?php echo esc_url( $s['primary_link']['url'] ); ?>"><?php echo esc_html( $s['primary_text'] ); ?></a>
						<?php endif; ?>
						<?php if ( ! empty( $s['ghost_text'] ) ) : ?>
							<a class="mwsb-btn mwsb-btn--ghost" href="<?php echo esc_url( $s['ghost_link']['url'] ); ?>"><?php echo esc_html( $s['ghost_text'] ); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $s['contact_phone'] ) || ! empty( $s['contact_email'] ) ) : ?>
					<div class="mwsb-ty__contact">
						<?php if ( ! empty( $s['contact_phone'] ) ) : ?>
							<a href="<?php echo esc_attr( $s['contact_phone_link'] ); ?>">
								<?php Icons_Manager::render_icon( array( 'value' => 'fas fa-phone', 'library' => 'fa-solid' ), array( 'aria-hidden' => 'true', 'class' => 'mwsb-ty-icon' ) ); ?>
								<span><?php echo esc_html( $s['contact_phone'] ); ?></span>
							</a>
						<?php endif; ?>
						<?php if ( ! empty( $s['contact_email'] ) ) : ?>
							<a href="mailto:<?php echo esc_attr( $s['contact_email'] ); ?>">
								<?php Icons_Manager::render_icon( array( 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ), array( 'aria-hidden' => 'true', 'class' => 'mwsb-ty-icon' ) ); ?>
								<span><?php echo esc_html( $s['contact_email'] ); ?></span>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $s['note_text'] ) ) : ?>
					<p class="mwsb-ty__note"><?php echo esc_html( $s['note_text'] ); ?></p>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
