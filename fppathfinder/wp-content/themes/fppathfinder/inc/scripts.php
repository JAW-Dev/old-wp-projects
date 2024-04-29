<?php
/**
 * Scripts / Styles
 *
 * Handles front end scripts and styles.
 *
 * @package     Objectiv-Genesis-Child
 * @since       1.0
 * @author      Wes Cole <wes@objectiv.co>
 */

function objectiv_enqueue_scripts() {

	wp_register_script( 'typekit', '//use.typekit.net/cwl6dkj.js' );
	wp_enqueue_script( 'typekit' );

	// Google Fonts
	wp_register_style( 'google-fonts', '//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' );
	wp_enqueue_style( 'google-fonts' );

	// SVG Shim
	wp_register_script( 'svg4everybody', get_stylesheet_directory_uri() . '/assets/components/svg4everybody/dist/svg4everybody.min.js', array() );
	wp_enqueue_script( 'svg4everybody' );

	// Slick
	wp_register_script( 'slick', get_stylesheet_directory_uri() . '/assets/components/slick-carousel/slick/slick.min.js', '', true );
	wp_register_style( 'slick-css', get_stylesheet_directory_uri() . '/assets/components/slick-carousel/slick/slick.css' );

	// Modaal
	wp_register_script( 'modaal', get_stylesheet_directory_uri() . '/assets/components/modaal/dist/js/modaal.min.js', array( 'jquery' ), false, true );
	wp_register_style( 'modaal-css', get_stylesheet_directory_uri() . '/assets/components/modaal/dist/css/modaal.min.css' );

	// Accessible Menu
	wp_register_script( 'gamajo-accessible-menu', get_stylesheet_directory_uri() . '/assets/components/accessible-menu/dist/jquery.accessible-menu.min.js', array( 'jquery' ), '1.0.0', true );

	$filename = '/assets/js/build/site-wide.js';
	$file     = get_stylesheet_directory() . $filename;
	$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.9';
	wp_register_script( 'sitewide', get_stylesheet_directory_uri() . $filename, array( 'gamajo-accessible-menu', 'jquery' ), $version, true );

	wp_enqueue_style( 'slick-css' );
	wp_enqueue_script( 'modaal' );
	wp_enqueue_style( 'modaal-css' );
	wp_enqueue_script( 'typescript' );
	wp_enqueue_script( 'sitewide' );
	wp_enqueue_script( 'slick' );

	$data_array = array(
		'stylesheetUrl' => get_stylesheet_directory_uri(),
		'ajax_url'      => admin_url( 'admin-ajax.php' ),
		'post_id'       => get_the_ID(),
		'nps_data'      => array(
			'nps_skip_days_closed' => get_field( 'days_to_skip_if_closed', 'nps_settings' ),
			'nps_skip_days_filled' => get_field( 'days_to_skip_if_form_filled_out', 'nps_settings' ),
			'nps_time_till_run'    => get_field( 'time_to_display', 'nps_settings' ),
			'nps_show_admin'       => get_field( 'always_show_for_admin', 'nps_settings' ),
			'nps_form_page'        => get_field( 'form_page', 'nps_settings' ),
			'disable_nps'          => get_field( 'disable_nps_entirely', 'nps_settings' ),
		),
	);

	wp_localize_script( 'sitewide', 'data', $data_array );

}

add_action( 'wp_enqueue_scripts', 'objectiv_enqueue_scripts' );
