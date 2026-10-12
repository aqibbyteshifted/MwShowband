<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

/**
 * MW Showband — proposal page: closing concierge CTA with dual buttons and
 * the directors' personal sign-off. One-off bespoke skin for the sales
 * proposal page only — see assets/css/proposal.css.
 * Author: Aqib Dev
 */
class MWSB_Proposal_Cta_Widget extends Widget_Base {

	use MWSB_Section_Controls;
	use MWSB_Proposal_Style_Controls;

	public function get_name() {
		return 'mwsb-proposal-cta';
	}

	public function get_title() {
		return __( 'MW Proposal — Closing CTA', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	public function get_style_depends() {
		return array( 'mwsb-tokens', 'mwsb-proposal' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'content_main', array( 'label' => __( 'Content', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'badge_text', array( 'label' => __( 'Badge Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'PROPOSAL PROTOCOL READY' ) );
		$this->add_control( 'title_tag', array( 'label' => __( 'Title HTML Tag', 'mw-showband' ), 'type' => Controls_Manager::SELECT, 'default' => 'h2', 'options' => array( 'h2' => 'H2', 'h3' => 'H3', 'div' => 'div' ) ) );
		$this->add_control( 'title_white', array( 'label' => __( 'Title — White Line', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'YOUR NIGHT.' ) );
		$this->add_control( 'title_gradient', array( 'label' => __( 'Title — Gradient Line', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'OUR NEXT SHOW.' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => "Everything starts with a conversation. Let's bring your vision to life and build the ultimate musical experience." ) );
		$this->end_controls_section();

		$this->start_controls_section( 'content_actions', array( 'label' => __( 'Buttons', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'primary_text', array( 'label' => __( 'Primary Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'DISCUSS YOUR PROPOSAL' ) );
		$this->add_control( 'primary_link', array( 'label' => __( 'Primary Button Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => 'mailto:info@mw-showband.com' ) ) );
		$this->add_control( 'secondary_icon', array( 'label' => __( 'Secondary Button Icon', 'mw-showband' ), 'type' => Controls_Manager::ICONS, 'default' => array( 'value' => 'fas fa-phone', 'library' => 'fa-solid' ) ) );
		$this->add_control( 'secondary_text', array( 'label' => __( 'Secondary Button Text (optional)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '+44 7707 159384' ) );
		$this->add_control( 'secondary_link', array( 'label' => __( 'Secondary Button Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => 'tel:+447707159384' ), 'condition' => array( 'secondary_text!' => '' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'content_signoff', array( 'label' => __( 'Sign-off (optional)', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'show_signoff', array( 'label' => __( 'Show Personal Sign-off', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'signoff_name', array( 'label' => __( 'Name(s)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Marlon & Wayne', 'condition' => array( 'show_signoff' => 'yes' ) ) );
		$this->add_control( 'signoff_role', array( 'label' => __( 'Role', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Musical & Creative Directors', 'condition' => array( 'show_signoff' => 'yes' ) ) );
		$this->add_control( 'signoff_note', array( 'label' => __( 'Footnote', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MW Showband Ltd. • Confidential Client Copy • Valid for 30 days from date of presentation.', 'condition' => array( 'show_signoff' => 'yes' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'content_bg', array( 'label' => __( 'Background Image (optional)', 'mw-showband' ), 'tab' => Controls_Manager::TAB_CONTENT ) );
		$this->add_control( 'bg_image', array( 'label' => __( 'Background Image', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => '' ) ) );
		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_proposal_style_controls();
	}

	/** Style tab: layout, background, then one section per element of the closing CTA. */
	private function register_proposal_style_controls() {
		// Layout.
		$this->mwsb_prop_section_start( 'style_layout', __( 'Layout', 'mw-showband' ) );
		$this->mwsb_prop_layout_controls( 'layout', '.mwsb-prop-cta' );
		$this->mwsb_prop_slider( 'layout_gap', __( 'Gap Between Blocks', 'mw-showband' ), '.mwsb-prop-cta__inner', 'gap', array( 'px' => array( 'min' => 0, 'max' => 120 ) ) );
		$this->mwsb_prop_slider( 'layout_head_width', __( 'Headline Max Width', 'mw-showband' ), '.mwsb-prop-cta__head', 'max-width', array( 'px' => array( 'min' => 300, 'max' => 1600 ), '%' => array( 'min' => 30, 'max' => 100 ) ), array( 'px', '%' ) );
		$this->mwsb_prop_section_end();

		// Background. Slider defaults mirror proposal.css so the look is unchanged until edited.
		$this->mwsb_prop_section_start( 'style_background', __( 'Background & Effects', 'mw-showband' ) );
		$this->add_control( 'bg_image_opacity', array( 'label' => __( 'Image Opacity', 'mw-showband' ), 'type' => Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 0, 'max' => 1, 'step' => 0.05 ) ), 'default' => array( 'size' => 0.15 ), 'selectors' => array( '{{WRAPPER}} .mwsb-prop-cta__bg' => 'opacity: {{SIZE}};' ), 'condition' => array( 'bg_image[url]!' => '' ) ) );
		$this->add_control( 'bg_image_grayscale', array( 'label' => __( 'Image Grayscale', 'mw-showband' ), 'type' => Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 0, 'max' => 1, 'step' => 0.05 ) ), 'default' => array( 'size' => 1 ), 'selectors' => array( '{{WRAPPER}} .mwsb-prop-cta__bg' => 'filter: grayscale({{SIZE}});' ), 'condition' => array( 'bg_image[url]!' => '' ) ) );
		$this->mwsb_prop_heading( 'bg_glow_h', __( 'Glow Orb', 'mw-showband' ) );
		$this->add_control( 'glow_color', array( 'label' => __( 'Glow Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-cta__glow' => 'background-color: {{VALUE}};' ) ) );
		$this->mwsb_prop_hide_switch( 'glow_hide', __( 'Hide Glow Orb', 'mw-showband' ), '.mwsb-prop-cta__glow' );
		$this->mwsb_prop_section_end();

		// Badge.
		$this->mwsb_prop_section_start( 'style_badge', __( 'Badge', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'badge', '.mwsb-prop-cta__badge span:last-child' );
		$this->mwsb_prop_box_controls( 'badge_box', '.mwsb-prop-cta__badge' );
		$this->mwsb_prop_heading( 'badge_icon_h', __( 'Icon', 'mw-showband' ) );
		$this->add_control( 'badge_icon_color', array( 'label' => __( 'Icon Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-cta__badge .mwsb-prop-icon' => 'color: {{VALUE}};' ) ) );
		$this->mwsb_prop_slider( 'badge_icon_size', __( 'Icon Size', 'mw-showband' ), '.mwsb-prop-cta__badge .mwsb-prop-icon', 'font-size', array( 'px' => array( 'min' => 8, 'max' => 40 ) ) );
		$this->mwsb_prop_hide_switch( 'badge_hide', __( 'Hide Badge', 'mw-showband' ), '.mwsb-prop-cta__badge' );
		$this->mwsb_prop_section_end();

		// Title + description.
		$this->mwsb_prop_section_start( 'style_title', __( 'Title & Description', 'mw-showband' ) );
		$this->mwsb_prop_heading( 'title_h', __( 'Title', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'title', '.mwsb-prop-cta__head .mwsb-prop-heading', array( 'spacing' => true ) );
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'title_gradient_bg',
				'label'    => __( 'Gradient Line Colors', 'mw-showband' ),
				'types'    => array( 'gradient' ),
				'selector' => '{{WRAPPER}} .mwsb-prop-cta__head .mwsb-prop-gradient-text',
			)
		);
		$this->mwsb_prop_heading( 'desc_h', __( 'Description', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'desc', '.mwsb-prop-cta__desc', array( 'spacing' => true ) );
		$this->mwsb_prop_slider( 'desc_max_width', __( 'Description Max Width', 'mw-showband' ), '.mwsb-prop-cta__desc', 'max-width', array( 'px' => array( 'min' => 200, 'max' => 1200 ), '%' => array( 'min' => 20, 'max' => 100 ) ), array( 'px', '%' ) );
		$this->mwsb_prop_hide_switch( 'desc_hide', __( 'Hide Description', 'mw-showband' ), '.mwsb-prop-cta__desc' );
		$this->mwsb_prop_section_end();

		// Buttons.
		$this->mwsb_prop_section_start( 'style_buttons', __( 'Buttons', 'mw-showband' ) );
		$this->mwsb_prop_slider( 'buttons_gap', __( 'Gap Between Buttons', 'mw-showband' ), '.mwsb-prop-cta__actions', 'gap', array( 'px' => array( 'min' => 0, 'max' => 60 ) ) );
		$this->mwsb_prop_heading( 'btn_primary_h', __( 'Primary Button', 'mw-showband' ) );
		$this->mwsb_prop_button_controls( 'btn_primary', '.mwsb-prop-btn--primary' );
		$this->mwsb_prop_heading( 'btn_ghost_h', __( 'Secondary Button', 'mw-showband' ) );
		$this->mwsb_prop_button_controls( 'btn_ghost', '.mwsb-prop-btn--ghost' );
		$this->add_control( 'btn_ghost_icon_color', array( 'label' => __( 'Secondary Icon Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-btn--ghost .mwsb-prop-icon' => 'color: {{VALUE}};' ) ) );
		$this->mwsb_prop_slider( 'btn_ghost_icon_size', __( 'Secondary Icon Size', 'mw-showband' ), '.mwsb-prop-btn--ghost .mwsb-prop-icon', 'font-size', array( 'px' => array( 'min' => 8, 'max' => 40 ) ) );
		$this->mwsb_prop_section_end();

		// Sign-off.
		$this->mwsb_prop_section_start( 'style_signoff', __( 'Sign-off', 'mw-showband' ), array( 'show_signoff' => 'yes' ) );
		$this->mwsb_prop_slider( 'signoff_gap', __( 'Gap Inside Sign-off', 'mw-showband' ), '.mwsb-prop-cta__signoff', 'gap', array( 'px' => array( 'min' => 0, 'max' => 60 ) ) );
		$this->mwsb_prop_heading( 'signoff_name_h', __( 'Name', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'signoff_name', '.mwsb-prop-cta__signoff-name' );
		$this->mwsb_prop_heading( 'signoff_role_h', __( 'Role', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'signoff_role', '.mwsb-prop-cta__signoff-role' );
		$this->mwsb_prop_heading( 'signoff_note_h', __( 'Footnote', 'mw-showband' ) );
		$this->mwsb_prop_text_controls( 'signoff_note', '.mwsb-prop-cta__signoff-note' );
		$this->mwsb_prop_heading( 'signoff_avatar_h', __( 'Avatar', 'mw-showband' ) );
		$this->mwsb_prop_box_controls( 'signoff_avatar', '.mwsb-prop-cta__signoff-avatar', array( 'padding' => false ) );
		$this->add_control( 'signoff_avatar_icon_color', array( 'label' => __( 'Avatar Icon Color', 'mw-showband' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mwsb-prop-cta__signoff-avatar .mwsb-prop-icon' => 'color: {{VALUE}};' ) ) );
		$this->add_responsive_control(
			'signoff_avatar_size',
			array(
				'label'      => __( 'Avatar Size', 'mw-showband' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 24, 'max' => 120 ) ),
				// Width AND height together so the circle stays a circle.
				'selectors'  => array( '{{WRAPPER}} .mwsb-prop-cta__signoff-avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->mwsb_prop_hide_switch( 'signoff_avatar_hide', __( 'Hide Avatar', 'mw-showband' ), '.mwsb-prop-cta__signoff-avatar' );
		$this->mwsb_prop_section_end();

		// Elementor-style colour/gradient overlay + image filters/position.
		$this->register_bg_overlay_controls( '.mwsb-prop-cta__bg' );
	}

	protected function render() {
		$s   = $this->get_settings_for_display();
		$tag = in_array( $s['title_tag'], array( 'h2', 'h3', 'div' ), true ) ? $s['title_tag'] : 'h2';
		?>
		<section class="mwsb-widget mwsb-section mwsb-prop mwsb-prop-cta">
			<?php if ( ! empty( $s['bg_image']['url'] ) ) : ?>
				<div class="mwsb-prop-cta__bg" style="background-image:url('<?php echo esc_url( $s['bg_image']['url'] ); ?>');"></div>
			<?php endif; ?>
			<div class="mwsb-bg-overlay"></div>
			<div class="mwsb-prop-cta__glow"></div>
			<div class="mwsb-container mwsb-prop-cta__inner">
				<?php if ( ! empty( $s['badge_text'] ) ) : ?>
					<span class="mwsb-prop-cta__badge">
						<?php Icons_Manager::render_icon( array( 'value' => 'fas fa-circle-check', 'library' => 'fa-solid' ), array( 'aria-hidden' => 'true', 'class' => 'mwsb-prop-icon' ) ); ?>
						<span><?php echo esc_html( $s['badge_text'] ); ?></span>
					</span>
				<?php endif; ?>

				<div class="mwsb-prop-cta__head">
					<<?php echo esc_html( $tag ); ?> class="mwsb-prop-heading mwsb-prop-heading--hero">
						<?php echo esc_html( $s['title_white'] ); ?><br />
						<span class="mwsb-prop-gradient-text"><?php echo esc_html( $s['title_gradient'] ); ?></span>
					</<?php echo esc_html( $tag ); ?>>
					<?php if ( ! empty( $s['description'] ) ) : ?>
						<p class="mwsb-prop-cta__desc"><?php echo esc_html( $s['description'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $s['primary_text'] ) || ! empty( $s['secondary_text'] ) ) : ?>
					<div class="mwsb-prop-cta__actions">
						<?php if ( ! empty( $s['primary_text'] ) ) : ?>
							<a class="mwsb-prop-btn mwsb-prop-btn--primary" href="<?php echo esc_url( $s['primary_link']['url'] ); ?>"><?php echo esc_html( $s['primary_text'] ); ?></a>
						<?php endif; ?>
						<?php if ( ! empty( $s['secondary_text'] ) ) : ?>
							<a class="mwsb-prop-btn mwsb-prop-btn--ghost" href="<?php echo esc_url( $s['secondary_link']['url'] ); ?>">
								<?php Icons_Manager::render_icon( $s['secondary_icon'], array( 'aria-hidden' => 'true', 'class' => 'mwsb-prop-icon' ) ); ?>
								<span><?php echo esc_html( $s['secondary_text'] ); ?></span>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( 'yes' === $s['show_signoff'] ) : ?>
					<div class="mwsb-prop-cta__signoff">
						<div class="mwsb-prop-cta__signoff-row">
							<div class="mwsb-prop-cta__signoff-text">
								<span class="mwsb-prop-cta__signoff-name"><?php echo esc_html( $s['signoff_name'] ); ?></span>
								<span class="mwsb-prop-cta__signoff-role"><?php echo esc_html( $s['signoff_role'] ); ?></span>
							</div>
							<div class="mwsb-prop-cta__signoff-avatar">
								<?php Icons_Manager::render_icon( array( 'value' => 'fas fa-music', 'library' => 'fa-solid' ), array( 'aria-hidden' => 'true', 'class' => 'mwsb-prop-icon' ) ); ?>
							</div>
						</div>
						<?php if ( ! empty( $s['signoff_note'] ) ) : ?>
							<p class="mwsb-prop-cta__signoff-note"><?php echo esc_html( $s['signoff_note'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
