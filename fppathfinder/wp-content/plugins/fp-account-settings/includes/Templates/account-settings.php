<?php
/**
 * Account Settings Template
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Templates
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 *
 * Template Name: Account Settings
 */

use FpAccountSettings\Includes\Classes\TemplateParts;

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

/**
 * Add body class.
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @param array $classes An array of the body classes.
 *
 * @return array
 */
function objectiv_body_class( $classes ) {
	$classes[] = 'template-account-settings';
	return $classes;

}
add_filter( 'body_class', 'objectiv_body_class' );

// Remove 'site-inner' from structural wrap.
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

/**
 * Template content.
 *
 * @author Objectiv
 * @since  1.0.0
 *
 * @return void
 */
function account_settings_page_content() {
	new TemplateParts\Init();
}
add_action( 'objectiv_page_content', 'account_settings_page_content' );

get_header();
do_action( 'objectiv_page_content' );
get_footer();
