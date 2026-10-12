<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

/**
 * MW Showband — Two-column contact section: info/details on the left,
 * a fully field-customizable enquiry form (à la Elementor Form) on the right.
 * Author: Aqib Dev
 */
class MWSB_Contact_Form_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-contact-form';
	}

	public function get_title() {
		return __( 'MW Contact Form', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	protected function register_controls() {

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'info_section',
			array(
				'label' => __( 'Info Column', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'CONTACT' ) );
		$this->add_control( 'title_line1', array( 'label' => __( 'Title Line 1', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => "LET'S BUILD YOUR" ) );
		$this->add_control( 'title_line2', array( 'label' => __( 'Title Line 2 (muted)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'NEXT SHOW.' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => "Tell us about your event. Our booking team replies directly — no call centre, no generic quote." ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'contact_items_section',
			array(
				'label' => __( 'Contact Details', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'info_label', array( 'label' => __( 'Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'BOOKINGS' ) );
		$repeater->add_control( 'info_value', array( 'label' => __( 'Value', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'info@mw-showband.com' ) );
		$repeater->add_control( 'info_link', array( 'label' => __( 'Link (optional)', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'placeholder' => 'mailto: or tel:' ) );

		$this->add_control(
			'contact_items',
			array(
				'label'       => __( 'Rows', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'info_label' => 'BOOKINGS', 'info_value' => 'info@mw-showband.com', 'info_link' => array( 'url' => 'mailto:info@mw-showband.com' ) ),
					array( 'info_label' => 'TOUR DESK · 24/7', 'info_value' => '+44 7707 159384', 'info_link' => array( 'url' => 'tel:+447707159384' ) ),
					array( 'info_label' => 'PRESS', 'info_value' => 'info@mw-showband.com', 'info_link' => array( 'url' => 'mailto:info@mw-showband.com' ) ),
					array( 'info_label' => 'OFFICES', 'info_value' => 'Los Angeles · London', 'info_link' => array( 'url' => '' ) ),
				),
				'title_field' => '{{{ info_label }}}',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'form_header_section',
			array(
				'label' => __( 'Form Header', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'form_title', array( 'label' => __( 'Form Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Send us a note.' ) );
		$this->add_control( 'careers_text', array( 'label' => __( 'Secondary Link Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Looking for talent?' ) );
		$this->add_control( 'careers_link', array( 'label' => __( 'Secondary Link URL', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#careers' ) ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'inquiry_section',
			array(
				'label' => __( 'Inquiry Type Tabs', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'inquiry_label', array( 'label' => __( 'Field Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'INQUIRY TYPE' ) );

		$tabs_repeater = new Repeater();
		$tabs_repeater->add_control( 'tab_label', array( 'label' => __( 'Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'New Booking' ) );
		$tabs_repeater->add_control( 'tab_value', array( 'label' => __( 'Value (optional)', 'mw-showband' ), 'type' => Controls_Manager::TEXT ) );

		$this->add_control(
			'inquiry_types',
			array(
				'label'       => __( 'Tabs', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $tabs_repeater->get_controls(),
				'default'     => array(
					array( 'tab_label' => 'Wedding' ),
					array( 'tab_label' => 'Corporate Event' ),
					array( 'tab_label' => 'Private Party' ),
					array( 'tab_label' => 'General Enquiry' ),
				),
				'title_field' => '{{{ tab_label }}}',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'fields_section',
			array(
				'label' => __( 'Form Fields', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$fields_repeater = new Repeater();
		$fields_repeater->add_control(
			'group_heading',
			array(
				'label'       => __( 'Group Heading (optional)', 'mw-showband' ),
				'type'        => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'description' => __( 'Shown above this field to start a new group, e.g. "ABOUT YOU".', 'mw-showband' ),
			)
		);
		$fields_repeater->add_control( 'field_label', array( 'label' => __( 'Label / Placeholder', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Field label', 'condition' => array( 'field_type!' => 'html' ) ) );
		$fields_repeater->add_control(
			'field_type',
			array(
				'label'   => __( 'Type', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'text',
				'options' => array(
					'text'       => __( 'Text', 'mw-showband' ),
					'email'      => __( 'Email', 'mw-showband' ),
					'textarea'   => __( 'Textarea', 'mw-showband' ),
					'url'        => __( 'URL', 'mw-showband' ),
					'tel'        => __( 'Tel', 'mw-showband' ),
					'radio'      => __( 'Radio', 'mw-showband' ),
					'select'     => __( 'Select', 'mw-showband' ),
					'checkbox'   => __( 'Checkbox', 'mw-showband' ),
					'acceptance' => __( 'Acceptance', 'mw-showband' ),
					'number'     => __( 'Number', 'mw-showband' ),
					'date'       => __( 'Date', 'mw-showband' ),
					'time'       => __( 'Time', 'mw-showband' ),
					'password'   => __( 'Password', 'mw-showband' ),
					'html'       => __( 'HTML', 'mw-showband' ),
					'hidden'     => __( 'Hidden', 'mw-showband' ),
				),
			)
		);
		$fields_repeater->add_control(
			'field_options',
			array(
				'label'       => __( 'Options', 'mw-showband' ),
				'type'        => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ),
				'description' => __( 'One option per line. For Select, the first line is the default-selected one.', 'mw-showband' ),
				'condition'   => array( 'field_type' => array( 'select', 'radio', 'checkbox' ) ),
			)
		);
		$fields_repeater->add_control(
			'acceptance_text',
			array(
				'label'       => __( 'Acceptance Text (HTML allowed, e.g. a link to your Privacy Policy)', 'mw-showband' ),
				'type'        => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ),
				'default'     => 'I agree to the <a href="#" target="_blank" rel="noopener">Privacy Policy</a>.',
				'condition'   => array( 'field_type' => 'acceptance' ),
			)
		);
		$fields_repeater->add_control(
			'html_content',
			array(
				'label'     => __( 'HTML Content', 'mw-showband' ),
				'type'      => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ),
				'default'   => '',
				'condition' => array( 'field_type' => 'html' ),
			)
		);
		$fields_repeater->add_control(
			'hidden_value',
			array(
				'label'     => __( 'Hidden Field Value', 'mw-showband' ),
				'type'      => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'default'   => '',
				'condition' => array( 'field_type' => 'hidden' ),
			)
		);
		$fields_repeater->add_control(
			'placeholder_text',
			array(
				'label'     => __( 'Placeholder (overrides Label as the shown placeholder, optional)', 'mw-showband' ),
				'type'      => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'default'   => '',
				'condition' => array( 'field_type' => array( 'text', 'email', 'textarea', 'url', 'tel', 'number', 'password' ) ),
			)
		);
		$fields_repeater->add_control(
			'field_width',
			array(
				'label'     => __( 'Width', 'mw-showband' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'full',
				'options'   => array(
					'full' => __( 'Full Width', 'mw-showband' ),
					'half' => __( 'Half Width', 'mw-showband' ),
				),
				'condition' => array( 'field_type!' => 'hidden' ),
			)
		);
		$fields_repeater->add_control(
			'required',
			array(
				'label'        => __( 'Required', 'mw-showband' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array( 'field_type!' => array( 'hidden', 'html' ) ),
			)
		);

		$this->add_control(
			'fields',
			array(
				'label'       => __( 'Fields', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $fields_repeater->get_controls(),
				'default'     => array(
					array( 'group_heading' => '', 'field_label' => 'Name', 'field_type' => 'text', 'field_width' => 'half', 'required' => 'yes' ),
					array( 'group_heading' => '', 'field_label' => 'Last name', 'field_type' => 'text', 'field_width' => 'half', 'required' => 'yes' ),
					array( 'group_heading' => '', 'field_label' => 'Email', 'field_type' => 'email', 'field_width' => 'half', 'required' => 'yes' ),
					array( 'group_heading' => '', 'field_label' => 'Phone', 'field_type' => 'tel', 'field_width' => 'half', 'required' => 'yes' ),
					array( 'group_heading' => '', 'field_label' => 'Date of event', 'field_type' => 'text', 'field_width' => 'half', 'required' => 'yes' ),
					array(
						'group_heading' => '',
						'field_label'   => 'Event type',
						'field_type'    => 'select',
						'field_options' => "Traditional wedding\nJewish wedding\nOther Religious Wedding\n(birthday) party\nCorporate event\nBar/bat mitzwah",
						'field_width'   => 'half',
						'required'      => 'yes',
					),
					array( 'group_heading' => '', 'field_label' => 'Number of guests', 'field_type' => 'text', 'field_width' => 'half', 'required' => 'yes' ),
					array( 'group_heading' => '', 'field_label' => 'Venue', 'field_type' => 'text', 'field_width' => 'half', 'required' => 'yes' ),
					array(
						'group_heading' => '',
						'field_label'   => 'How did you hear about us?',
						'field_type'    => 'select',
						'field_options' => "Instagram\nFacebook/Instagram Ad\nTikTok\nRecommendation from a friend\nAt an event / saw live performance\nWedding planner / event organizer\nMagazine / printed media\nGoogle Search\nFacebook",
						'field_width'   => 'full',
						'required'      => '',
					),
					array(
						'group_heading' => '',
						'field_label'   => 'Budget',
						'field_type'    => 'select',
						'field_options' => "£8,000 - £15,000\n£15,000 - £20,000\n£20,000 - £30,000\n£30,000+",
						'field_width'   => 'full',
						'required'      => '',
					),
					array( 'group_heading' => '', 'field_label' => 'Anything else we need to know', 'field_type' => 'textarea', 'field_width' => 'full', 'required' => '' ),
				),
				'title_field' => '{{{ field_label }}}',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'form_settings_section',
			array(
				'label' => __( 'Form Settings', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'recipient_email',
			array(
				'label'       => __( 'Send Submissions To', 'mw-showband' ),
				'type'        => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'placeholder' => get_option( 'admin_email' ),
				'description' => __( 'Leave blank to use the site admin email.', 'mw-showband' ),
			)
		);
		$this->add_control( 'footer_note', array( 'label' => __( 'Footer Note', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'ONE OF OUR TEAM READS EVERY MESSAGE' ) );
		$this->add_control( 'submit_text', array( 'label' => __( 'Submit Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'SEND ENQUIRY' ) );
		$this->add_control( 'sending_text', array( 'label' => __( 'Sending Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'SENDING…' ) );
		$this->add_control( 'success_text', array( 'label' => __( 'Success Message', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => "Thanks — your message is in. We'll be in touch shortly." ) );
		$this->add_control( 'error_text', array( 'label' => __( 'Error Message', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Something went wrong sending your message. Please try again.' ) );
		$this->add_control(
			'after_submit',
			array(
				'label'       => __( 'After Submit', 'mw-showband' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'thankyou',
				'options'     => array(
					'thankyou' => __( 'Go to the Thank You page', 'mw-showband' ),
					'custom'   => __( 'Go to a custom URL', 'mw-showband' ),
					'message'  => __( 'Stay here and show the message', 'mw-showband' ),
				),
				'description' => __( '"Thank You page" uses the page made under Appearance → Thank You Page. Until that page exists, the form just shows the success message.', 'mw-showband' ),
				'separator'   => 'before',
			)
		);
		$this->add_control( 'redirect_url', array( 'label' => __( 'Redirect URL', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'placeholder' => 'https://', 'condition' => array( 'after_submit' => 'custom' ) ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->register_section_style_controls();

		$this->start_controls_section(
			'mwsb_cf_style',
			array(
				'label' => __( 'Form Style', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'accent_color',
			array(
				'label'     => __( 'Accent Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'placeholder' => 'var(--mwsb-primary)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-contact-form' => '--mwsb-cf-accent: {{VALUE}};',
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
					'{{WRAPPER}} .mwsb-contact-form' => '--mwsb-cf-card-bg: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_cf_text_style',
			array(
				'label' => __( 'Info Column Text', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cf_heading_typography',
				'label'    => __( 'Heading Typography', 'mw-showband' ),
				'selector' => '{{WRAPPER}} .mwsb-contact-form__heading',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'cf_desc_typography',
				'label'     => __( 'Description Typography', 'mw-showband' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .mwsb-contact-form__desc',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'cf_info_value_typography',
				'label'     => __( 'Info Value Typography', 'mw-showband' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .mwsb-contact-form__info-value',
			)
		);

		$this->end_controls_section();
	}

	/** Where to send the visitor after a successful submit ('' = stay on the page and show the message). */
	private function get_redirect_url( $s ) {
		$mode = isset( $s['after_submit'] ) ? $s['after_submit'] : 'thankyou';
		if ( 'custom' === $mode ) {
			return ! empty( $s['redirect_url']['url'] ) ? $s['redirect_url']['url'] : '';
		}
		if ( 'thankyou' === $mode && class_exists( 'MWSB_Thankyou_Page_Builder' ) ) {
			return MWSB_Thankyou_Page_Builder::get_url();
		}
		return '';
	}

	protected function render() {
		$s        = $this->get_settings_for_display();
		$redirect = $this->get_redirect_url( $s );
		?>
		<section class="mwsb-widget mwsb-section mwsb-contact-form" id="contact">
			<div class="mwsb-container mwsb-contact-form__grid">

				<div class="mwsb-contact-form__info">
					<span class="mwsb-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
					<h2 class="mwsb-heading-display mwsb-contact-form__heading">
						<?php echo esc_html( $s['title_line1'] ); ?><br />
						<span class="mwsb-gradient-text"><?php echo esc_html( $s['title_line2'] ); ?></span>
					</h2>
					<?php if ( ! empty( $s['description'] ) ) : ?>
						<p class="mwsb-contact-form__desc"><?php echo esc_html( $s['description'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $s['contact_items'] ) ) : ?>
						<div class="mwsb-contact-form__info-list">
							<?php foreach ( $s['contact_items'] as $item ) : ?>
								<div class="mwsb-contact-form__info-row">
									<span class="mwsb-contact-form__info-label"><?php echo esc_html( $item['info_label'] ); ?></span>
									<?php if ( ! empty( $item['info_link']['url'] ) ) : ?>
										<a href="<?php echo esc_url( $item['info_link']['url'] ); ?>" class="mwsb-contact-form__info-value"><?php echo esc_html( $item['info_value'] ); ?></a>
									<?php else : ?>
										<span class="mwsb-contact-form__info-value"><?php echo esc_html( $item['info_value'] ); ?></span>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="mwsb-contact-form__card">
					<div class="mwsb-contact-form__card-head">
						<h3 class="mwsb-contact-form__card-title">
							<span class="mwsb-contact-form__marker"></span>
							<?php echo esc_html( $s['form_title'] ); ?>
						</h3>
						<?php if ( ! empty( $s['careers_text'] ) ) : ?>
							<a href="<?php echo esc_url( $s['careers_link']['url'] ); ?>" class="mwsb-contact-form__careers"><?php echo esc_html( $s['careers_text'] ); ?> &#8594;</a>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $s['inquiry_types'] ) ) : ?>
						<?php if ( ! empty( $s['inquiry_label'] ) ) : ?>
							<div class="mwsb-contact-form__section-label"><?php echo esc_html( $s['inquiry_label'] ); ?></div>
						<?php endif; ?>
						<div class="mwsb-contact-form__tabs" data-mwsb-contact-tabs>
							<?php foreach ( $s['inquiry_types'] as $i => $tab ) :
								$value = ! empty( $tab['tab_value'] ) ? $tab['tab_value'] : sanitize_title( $tab['tab_label'] );
								?>
								<label class="mwsb-contact-form__tab <?php echo 0 === $i ? 'is-active' : ''; ?>">
									<input
										type="radio"
										name="mwsb_field_inquiry_type"
										value="<?php echo esc_attr( $value ); ?>"
										class="mwsb-contact-form__tab-input"
										<?php checked( 0 === $i ); ?>
									/>
									<span><?php echo esc_html( $tab['tab_label'] ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<form
						class="mwsb-contact-form__form"
						data-mwsb-contact-form
						data-submit-text="<?php echo esc_attr( $s['submit_text'] ); ?>"
						data-sending-text="<?php echo esc_attr( $s['sending_text'] ); ?>"
						<?php if ( $redirect ) : ?>data-redirect="<?php echo esc_url( $redirect ); ?>"<?php endif; ?>
						novalidate
					>
						<input type="hidden" name="action" value="mwsb_contact_form_submit" />
						<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'mwsb_contact_form' ) ); ?>" />
						<?php if ( ! empty( $s['recipient_email'] ) ) : ?>
							<input type="hidden" name="mwsb_recipient" value="<?php echo esc_attr( $s['recipient_email'] ); ?>" />
						<?php endif; ?>
						<input type="text" name="mwsb_hp" class="mwsb-contact-form__hp" tabindex="-1" autocomplete="off" aria-hidden="true" />

						<div class="mwsb-contact-form__fields">
							<?php foreach ( $s['fields'] as $field ) :
								$type = $field['field_type'];

								// HTML blocks and Hidden fields have no label/name slug and skip the
								// regular field wrapper markup entirely.
								if ( 'html' === $type ) {
									if ( ! empty( $field['html_content'] ) ) {
										echo '<div class="mwsb-contact-form__field mwsb-contact-form__field--full mwsb-contact-form__html">' . wp_kses_post( $field['html_content'] ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
									}
									continue;
								}

								$slug = sanitize_title( $field['field_label'] );
								$name = 'mwsb_field_' . $slug;

								if ( 'hidden' === $type ) {
									echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $field['hidden_value'] ) . '" />'; // phpcs:ignore WordPress.Security.EscapeOutput
									continue;
								}

								$required    = 'yes' === $field['required'];
								$width       = 'half' === $field['field_width'] ? 'mwsb-contact-form__field--half' : 'mwsb-contact-form__field--full';
								$placeholder = ! empty( $field['placeholder_text'] ) ? $field['placeholder_text'] : $field['field_label'];
								$options     = in_array( $type, array( 'select', 'radio', 'checkbox' ), true )
									? array_filter( array_map( 'trim', explode( "\n", (string) $field['field_options'] ) ) )
									: array();
								?>
								<?php if ( ! empty( $field['group_heading'] ) ) : ?>
									<div class="mwsb-contact-form__group-label"><?php echo esc_html( $field['group_heading'] ); ?></div>
								<?php endif; ?>
								<div class="mwsb-contact-form__field <?php echo esc_attr( $width ); ?><?php echo in_array( $type, array( 'radio', 'checkbox', 'acceptance' ), true ) ? ' mwsb-contact-form__field--choice' : ''; ?>">
									<?php if ( 'textarea' === $type ) : ?>
										<label class="screen-reader-text" for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $field['field_label'] ); ?></label>
										<textarea
											id="<?php echo esc_attr( $name ); ?>"
											name="<?php echo esc_attr( $name ); ?>"
											class="mwsb-contact-form__control mwsb-contact-form__control--textarea"
											placeholder="<?php echo esc_attr( $placeholder ); ?>"
											rows="5"
											<?php echo $required ? 'required' : ''; ?>
										></textarea>
									<?php elseif ( 'select' === $type ) : ?>
										<label class="screen-reader-text" for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $field['field_label'] ); ?></label>
										<select
											id="<?php echo esc_attr( $name ); ?>"
											name="<?php echo esc_attr( $name ); ?>"
											class="mwsb-contact-form__control mwsb-contact-form__control--select"
											<?php echo $required ? 'required' : ''; ?>
										>
											<?php foreach ( $options as $opt ) : ?>
												<option value="<?php echo esc_attr( $opt ); ?>"><?php echo esc_html( $opt ); ?></option>
											<?php endforeach; ?>
										</select>
									<?php elseif ( 'radio' === $type ) : ?>
										<span class="mwsb-contact-form__choice-legend"><?php echo esc_html( $field['field_label'] ); ?></span>
										<div class="mwsb-contact-form__choice-group">
											<?php foreach ( $options as $i => $opt ) : ?>
												<label class="mwsb-contact-form__choice">
													<input type="radio" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $opt ); ?>" <?php echo ( 0 === $i && $required ) ? 'required' : ''; ?> />
													<span><?php echo esc_html( $opt ); ?></span>
												</label>
											<?php endforeach; ?>
										</div>
									<?php elseif ( 'checkbox' === $type ) : ?>
										<span class="mwsb-contact-form__choice-legend"><?php echo esc_html( $field['field_label'] ); ?></span>
										<div class="mwsb-contact-form__choice-group">
											<?php foreach ( $options as $opt ) : ?>
												<label class="mwsb-contact-form__choice">
													<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[]" value="<?php echo esc_attr( $opt ); ?>" />
													<span><?php echo esc_html( $opt ); ?></span>
												</label>
											<?php endforeach; ?>
										</div>
									<?php elseif ( 'acceptance' === $type ) : ?>
										<label class="mwsb-contact-form__choice">
											<input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="yes" <?php echo $required ? 'required' : ''; ?> />
											<span><?php echo wp_kses_post( $field['acceptance_text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
										</label>
									<?php else : ?>
										<label class="screen-reader-text" for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $field['field_label'] ); ?></label>
										<input
											type="<?php echo esc_attr( $type ); ?>"
											id="<?php echo esc_attr( $name ); ?>"
											name="<?php echo esc_attr( $name ); ?>"
											class="mwsb-contact-form__control"
											placeholder="<?php echo esc_attr( $placeholder ); ?>"
											<?php echo $required ? 'required' : ''; ?>
										/>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>

						<div class="mwsb-contact-form__footer">
							<?php if ( ! empty( $s['footer_note'] ) ) : ?>
								<span class="mwsb-contact-form__note"><?php echo esc_html( $s['footer_note'] ); ?></span>
							<?php endif; ?>
							<button type="submit" class="mwsb-btn mwsb-btn--primary mwsb-contact-form__submit">
								<?php echo esc_html( $s['submit_text'] ); ?>
								<span class="mwsb-contact-form__submit-icon">&#8594;</span>
							</button>
						</div>

						<div class="mwsb-contact-form__notice" data-mwsb-contact-notice></div>
					</form>
				</div>
			</div>
		</section>
		<?php
	}

	/**
	 * AJAX handler: mails the submission to the configured recipient
	 * (or the site admin email if none was set on the widget instance).
	 */
	public static function handle_submit() {
		if ( ! check_ajax_referer( 'mwsb_contact_form', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed. Please refresh and try again.', 'mw-showband' ) ) );
		}

		// Honeypot: bots fill hidden fields — pretend success, send nothing.
		$honeypot = isset( $_POST['mwsb_hp'] ) ? sanitize_text_field( wp_unslash( $_POST['mwsb_hp'] ) ) : '';
		if ( '' !== $honeypot ) {
			wp_send_json_success( array( 'message' => __( 'Thanks!', 'mw-showband' ) ) );
		}

		$to = isset( $_POST['mwsb_recipient'] ) ? sanitize_email( wp_unslash( $_POST['mwsb_recipient'] ) ) : '';
		if ( ! $to || ! is_email( $to ) ) {
			$to = get_option( 'admin_email' );
		}

		$skip = array( 'action', 'nonce', 'mwsb_hp', 'mwsb_recipient', '_wp_http_referer' );
		$lines = array();

		foreach ( $_POST as $key => $value ) {
			if ( in_array( $key, $skip, true ) || 0 !== strpos( $key, 'mwsb_field_' ) ) {
				continue;
			}
			$label = str_replace( array( 'mwsb_field_', '-' ), array( '', ' ' ), $key );
			$val   = is_array( $value )
				? implode( ', ', array_map( 'sanitize_textarea_field', wp_unslash( $value ) ) )
				: sanitize_textarea_field( wp_unslash( $value ) );

			if ( '' === $val ) {
				continue;
			}
			$lines[] = ucwords( $label ) . ': ' . $val;
		}

		if ( empty( $lines ) ) {
			wp_send_json_error( array( 'message' => __( 'Please fill in the form before sending.', 'mw-showband' ) ) );
		}

		$subject = sprintf( __( 'New enquiry from %s', 'mw-showband' ), get_bloginfo( 'name' ) );
		$body    = implode( "\n", $lines );
		$sent    = wp_mail( $to, $subject, $body );

		if ( $sent ) {
			wp_send_json_success( array( 'message' => __( "Thanks — your message is in. We'll be in touch shortly.", 'mw-showband' ) ) );
		}

		wp_send_json_error( array( 'message' => __( 'Something went wrong sending your message. Please try again.', 'mw-showband' ) ) );
	}
}
