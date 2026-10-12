<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MWSB_THEME_VERSION', '1.0.0' );
define( 'MWSB_THEME_PATH', get_template_directory() );
define( 'MWSB_THEME_URL', get_template_directory_uri() );

/**
 * Theme setup: standard supports + Elementor Theme Builder compatibility.
 * Author: Aqib Dev
 */
function mwsb_theme_setup() {
	// Lets translation files (/languages/mw-showband-{locale}.mo, e.g. from Loco Translate, WPML or
	// Polylang string translation) translate every __()/esc_html__() string in the theme.
	load_theme_textdomain( 'mw-showband', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );

	// Full Elementor Pro Theme Builder support (header/footer/archive via Elementor).
	add_theme_support( 'elementor' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'mw-showband' ),
			'footer'  => __( 'Footer Menu', 'mw-showband' ),
		)
	);
}
add_action( 'after_setup_theme', 'mwsb_theme_setup' );

function mwsb_theme_assets() {
	wp_enqueue_style( 'mwsb-theme-style', get_stylesheet_uri(), array(), MWSB_THEME_VERSION );
}
add_action( 'wp_enqueue_scripts', 'mwsb_theme_assets' );

// Bundled Elementor widgets that power the MW Showband homepage design.
require_once MWSB_THEME_PATH . '/inc/class-elementor-widgets.php';

/**
 * Register the contact form's AJAX handler unconditionally.
 *
 * Elementor only instantiates widget classes when it actually needs to
 * render/edit them, so a plain admin-ajax.php POST (unrelated to Elementor's
 * own render cycle) never triggers that instantiation. If the AJAX hooks
 * were only added from the widget's constructor, WordPress would find no
 * handler for the action and respond with a bare 400 "Invalid request"
 * before our code ever runs. Loading the class and adding the hooks here,
 * on every request, guarantees the handler always exists.
 */
function mwsb_register_contact_form_ajax() {
	if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
		return;
	}
	require_once MWSB_THEME_PATH . '/inc/trait-section-controls.php';
	require_once MWSB_THEME_PATH . '/inc/widgets/class-contact-form-widget.php';
	add_action( 'wp_ajax_mwsb_contact_form_submit', array( 'MWSB_Contact_Form_Widget', 'handle_submit' ) );
	add_action( 'wp_ajax_nopriv_mwsb_contact_form_submit', array( 'MWSB_Contact_Form_Widget', 'handle_submit' ) );
}
add_action( 'init', 'mwsb_register_contact_form_ajax' );

// One-click demo import: Appearance → Import Demo Data.
require_once MWSB_THEME_PATH . '/inc/class-demo-importer.php';

// One-click sales proposal page builder: Appearance → Proposal Page (Paul).
require_once MWSB_THEME_PATH . '/inc/class-proposal-page-builder.php';

// One-click Thank You page (contact-form destination): Appearance → Thank You Page.
require_once MWSB_THEME_PATH . '/inc/class-thankyou-page-builder.php';
