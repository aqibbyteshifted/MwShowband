<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

/**
 * MW Showband — "Mega footer": headline + live stats + CTA up top, a
 * contact/status column beside link columns in the middle, a credit bar,
 * and an oversized bleed wordmark at the base. Approved client layout,
 * rebuilt entirely on the MW Showband design tokens.
 * Author: Aqib Dev
 */
class MWSB_Footer_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-footer';
	}

	public function get_title() {
		return __( 'MW Footer', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-footer';
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

		$this->add_control( 'heading_line1', array( 'label' => __( 'Heading Line 1', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Booking is complicated.' ) );
		$this->add_control( 'heading_line2', array( 'label' => __( 'Heading Line 2 (muted)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'The show is not.' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Tell us your date. Our booking team takes it from there.' ) );
		$this->add_control( 'cta_text', array( 'label' => __( 'Button Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Get in touch' ) );
		$this->add_control( 'cta_link', array( 'label' => __( 'Button Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#contact' ) ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'stats_section',
			array(
				'label' => __( 'Header Stats', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$stats_repeater = new Repeater();
		$stats_repeater->add_control( 'stat_label', array( 'label' => __( 'Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'SHOWS PERFORMED' ) );
		$stats_repeater->add_control( 'stat_value', array( 'label' => __( 'Value', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '1,200+' ) );

		$this->add_control(
			'stats',
			array(
				'label'       => __( 'Stat Rows', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $stats_repeater->get_controls(),
				'default'     => array(
					array( 'stat_label' => 'SHOWS PERFORMED', 'stat_value' => '1,200+' ),
					array( 'stat_label' => 'COUNTRIES TOURED', 'stat_value' => '40+' ),
				),
				'title_field' => '{{{ stat_label }}}',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'contact_section',
			array(
				'label' => __( 'Contact Column', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'contact_label', array( 'label' => __( 'Column Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'BOOKINGS · 24/7' ) );
		$this->add_control( 'contact_phone', array( 'label' => __( 'Phone', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '+44 7707 159384' ) );
		$this->add_control( 'contact_phone_link', array( 'label' => __( 'Phone Link', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'tel:+447707159384' ) );
		$this->add_control( 'contact_email', array( 'label' => __( 'Email', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'info@mw-showband.com' ) );
		$this->add_control( 'show_status', array( 'label' => __( 'Show Status Line', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'status_text', array( 'label' => __( 'Status Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'ACCEPTING 2026 DATES', 'condition' => array( 'show_status' => 'yes' ) ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'links_section',
			array(
				'label' => __( 'Link Columns', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		/*
		 * A flat repeater, not a repeater-of-repeaters. Elementor's editor
		 * cannot safely handle a REPEATER control nested inside another
		 * REPEATER's fields — it recurses infinitely while building the
		 * panel template and crashes with "Maximum call stack size
		 * exceeded". Instead, each row is one link; setting a
		 * "New Column Heading" on a row starts a fresh column in render()
		 * (the same grouping trick used by the Contact Form widget).
		 */
		$link_repeater = new Repeater();
		$link_repeater->add_control(
			'column_heading',
			array(
				'label'       => __( 'New Column Heading (optional)', 'mw-showband' ),
				'type'        => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ),
				'description' => __( 'Fill this in to start a new column at this link; leave blank to add to the current column.', 'mw-showband' ),
			)
		);
		$link_repeater->add_control( 'label', array( 'label' => __( 'Link Label', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'Link' ) );
		$link_repeater->add_control( 'link', array( 'label' => __( 'Link URL', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#' ) ) );

		$this->add_control(
			'footer_links',
			array(
				'label'       => __( 'Links (grouped into columns)', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $link_repeater->get_controls(),
				'default'     => array(
					array( 'column_heading' => 'SITE', 'label' => 'Shows', 'link' => array( 'url' => '#interactive-timeline' ) ),
					array( 'column_heading' => '', 'label' => 'Experience', 'link' => array( 'url' => '#experience' ) ),
					array( 'column_heading' => '', 'label' => 'The Band', 'link' => array( 'url' => '#the-band' ) ),
					array( 'column_heading' => 'EVENTS', 'label' => 'Weddings', 'link' => array( 'url' => '#event-builder' ) ),
					array( 'column_heading' => '', 'label' => 'Corporate', 'link' => array( 'url' => '#event-builder' ) ),
					array( 'column_heading' => '', 'label' => 'Private Parties', 'link' => array( 'url' => '#event-builder' ) ),
					array( 'column_heading' => 'COMPANY', 'label' => 'Contact', 'link' => array( 'url' => '#contact' ) ),
					array( 'column_heading' => '', 'label' => 'FAQ', 'link' => array( 'url' => '#' ) ),
					array( 'column_heading' => '', 'label' => 'Press Kit', 'link' => array( 'url' => '#' ) ),
				),
				'title_field' => '{{{ label }}}',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'bottom_section',
			array(
				'label' => __( 'Bottom Bar', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'copyright', array( 'label' => __( 'Copyright Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '© ' . gmdate( 'Y' ) . ' MW Showband. All rights reserved.' ) );
		$this->add_control( 'credit_text', array( 'label' => __( 'Credit Text (optional)', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '' ) );
		$this->add_control( 'credit_link', array( 'label' => __( 'Credit Link', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '#' ), 'condition' => array( 'credit_text!' => '' ) ) );
		$this->add_control( 'show_back_to_top', array( 'label' => __( 'Show Back-to-Top Button', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control(
			'lang_shortcode',
			array(
				'label'       => __( 'Language Switcher Shortcode', 'mw-showband' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '[gtranslate]',
				'placeholder' => '[gtranslate]',
				'description' => __( 'Shown at the bottom right of the footer. GTranslate: [gtranslate] — also works with [language-switcher] (TranslatePress), [wpml_language_selector_widget] (WPML) or [weglot_switcher]. Clear the field to hide it. If the plugin is not active nothing is shown. Turn the plugin\'s own floating switcher off (GTranslate → Settings) so there aren\'t two.', 'mw-showband' ),
				'separator'   => 'before',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->start_controls_section(
			'wordmark_section',
			array(
				'label' => __( 'Bleed Wordmark', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'show_wordmark', array( 'label' => __( 'Show Wordmark', 'mw-showband' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'wordmark_text', array( 'label' => __( 'Wordmark Text', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'MW SHOWBAND', 'condition' => array( 'show_wordmark' => 'yes' ) ) );

		$this->end_controls_section();

		/* ---------------------------------------------------------------- */
		$this->register_section_style_controls();

		$this->start_controls_section(
			'mwsb_footer_style',
			array(
				'label' => __( 'Accent', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'accent_color',
			array(
				'label'       => __( 'Accent Color', 'mw-showband' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'placeholder' => 'var(--mwsb-primary)',
				'selectors'   => array(
					'{{WRAPPER}} .mwsb-footer' => '--mwsb-footer-accent: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_footer_header_style',
			array(
				'label' => __( 'Header Text', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'footer_heading_typography',
				'selector' => '{{WRAPPER}} .mwsb-footer__heading',
			)
		);

		$this->add_control(
			'footer_desc_heading',
			array(
				'label'     => __( 'Description', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'footer_desc_typography',
				'selector' => '{{WRAPPER}} .mwsb-footer__desc',
			)
		);

		$this->add_control(
			'footer_stat_heading',
			array(
				'label'     => __( 'Stat Value', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'footer_stat_value_typography',
				'selector' => '{{WRAPPER}} .mwsb-footer__stat-value',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_footer_columns_style',
			array(
				'label' => __( 'Contact & Link Columns', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'footer_col_heading_heading',
			array(
				'label' => __( 'Column Heading', 'mw-showband' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'footer_col_heading_typography',
				'selector' => '{{WRAPPER}} .mwsb-footer__col h4, {{WRAPPER}} .mwsb-footer__contact-col h4',
			)
		);

		$this->add_control(
			'footer_link_heading',
			array(
				'label'     => __( 'Links', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'footer_link_typography',
				'selector' => '{{WRAPPER}} .mwsb-footer__col a, {{WRAPPER}} .mwsb-footer__contact-value',
			)
		);

		$this->add_control(
			'footer_link_color',
			array(
				'label'     => __( 'Link Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-text-muted)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-footer__col a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'footer_link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mwsb-footer__col a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_footer_bottom_style',
			array(
				'label' => __( 'Bottom Bar & Wordmark', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'footer_copyright_typography',
				'label'    => __( 'Copyright Typography', 'mw-showband' ),
				'selector' => '{{WRAPPER}} .mwsb-footer__bottom',
			)
		);

		$this->add_control(
			'footer_wordmark_color',
			array(
				'label'     => __( 'Wordmark Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-footer__wordmark' => '-webkit-text-stroke-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Regroups the flat "footer_links" repeater into columns, splitting on
	 * every row that has a non-empty "column_heading".
	 */
	private function group_footer_links( $links ) {
		$columns = array();
		$index   = -1;

		foreach ( $links as $link ) {
			if ( ! empty( $link['column_heading'] ) || $index < 0 ) {
				$columns[] = array(
					'heading' => ! empty( $link['column_heading'] ) ? $link['column_heading'] : '',
					'links'   => array(),
				);
				$index++;
			}
			$columns[ $index ]['links'][] = $link;
		}

		return $columns;
	}

	protected function render() {
		$s             = $this->get_settings_for_display();
		$link_columns  = $this->group_footer_links( ! empty( $s['footer_links'] ) ? $s['footer_links'] : array() );
		?>
		<footer class="mwsb-widget mwsb-section mwsb-footer">
			<div class="mwsb-container">

				<div class="mwsb-footer__head">
					<div class="mwsb-footer__head-copy">
						<h2 class="mwsb-heading-display mwsb-footer__heading">
							<?php echo esc_html( $s['heading_line1'] ); ?><br />
							<span class="mwsb-gradient-text"><?php echo esc_html( $s['heading_line2'] ); ?></span>
						</h2>
						<?php if ( ! empty( $s['description'] ) ) : ?>
							<p class="mwsb-footer__desc"><?php echo esc_html( $s['description'] ); ?></p>
						<?php endif; ?>
					</div>

					<div class="mwsb-footer__head-meta">
						<?php if ( ! empty( $s['stats'] ) ) : ?>
							<div class="mwsb-footer__stats">
								<?php foreach ( $s['stats'] as $stat ) : ?>
									<div class="mwsb-footer__stat">
										<span class="mwsb-footer__stat-label"><?php echo esc_html( $stat['stat_label'] ); ?></span>
										<span class="mwsb-footer__stat-value"><?php echo esc_html( $stat['stat_value'] ); ?></span>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $s['cta_text'] ) ) : ?>
							<a href="<?php echo esc_url( $s['cta_link']['url'] ); ?>" class="mwsb-btn mwsb-btn--primary mwsb-footer__cta">
								<?php echo esc_html( $s['cta_text'] ); ?> <span class="mwsb-footer__cta-icon">&#8594;</span>
							</a>
						<?php endif; ?>
					</div>
				</div>

				<div class="mwsb-footer__divider"></div>

				<div class="mwsb-footer__grid">
					<div class="mwsb-footer__contact-col">
						<h4><?php echo esc_html( $s['contact_label'] ); ?></h4>
						<a href="<?php echo esc_attr( $s['contact_phone_link'] ); ?>" class="mwsb-footer__contact-value"><?php echo esc_html( $s['contact_phone'] ); ?></a>
						<a href="mailto:<?php echo esc_attr( $s['contact_email'] ); ?>" class="mwsb-footer__contact-value"><?php echo esc_html( $s['contact_email'] ); ?></a>
						<?php if ( 'yes' === $s['show_status'] && ! empty( $s['status_text'] ) ) : ?>
							<div class="mwsb-footer__status">
								<span class="mwsb-footer__status-dot"></span>
								<?php echo esc_html( $s['status_text'] ); ?>
							</div>
						<?php endif; ?>
					</div>

					<?php foreach ( $link_columns as $col ) : ?>
						<div class="mwsb-footer__col">
							<?php if ( ! empty( $col['heading'] ) ) : ?><h4><?php echo esc_html( $col['heading'] ); ?></h4><?php endif; ?>
							<ul>
								<?php foreach ( $col['links'] as $link ) : ?>
									<li><a href="<?php echo esc_url( $link['link']['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="mwsb-footer__divider"></div>

				<div class="mwsb-footer__bottom">
					<span><?php echo esc_html( $s['copyright'] ); ?></span>
					<?php if ( ! empty( $s['credit_text'] ) ) : ?>
						<a href="<?php echo esc_url( $s['credit_link']['url'] ); ?>" class="mwsb-footer__credit"><?php echo esc_html( $s['credit_text'] ); ?></a>
					<?php endif; ?>
					<?php $lang_html = $this->render_language_switcher( $s['lang_shortcode'] ); ?>
					<?php if ( '' !== $lang_html ) : ?>
						<div class="mwsb-footer__lang notranslate" data-no-translation><?php echo $lang_html; // phpcs:ignore WordPress.Security.EscapeOutput -- shortcode output, set by an admin. ?></div>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( 'yes' === $s['show_wordmark'] && ! empty( $s['wordmark_text'] ) ) : ?>
				<div class="mwsb-footer__wordmark" aria-hidden="true"><?php echo esc_html( $s['wordmark_text'] ); ?></div>
			<?php endif; ?>

			<?php if ( 'yes' === $s['show_back_to_top'] ) : ?>
				<button type="button" class="mwsb-footer__back-to-top" data-mwsb-back-to-top aria-label="<?php esc_attr_e( 'Back to top', 'mw-showband' ); ?>">&#8593;</button>
			<?php endif; ?>
		</footer>
		<?php
	}
}
