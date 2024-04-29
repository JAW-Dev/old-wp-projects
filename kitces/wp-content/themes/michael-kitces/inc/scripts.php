<?php

define( 'MK_SCRIPTS_STYLE_VERSION', '1.0.23' );

add_action( 'wp_enqueue_scripts', 'kitces_enqueue_scripts' );
function kitces_enqueue_scripts() {
	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i&display=swap' );
	wp_enqueue_style( 'printfriendly-css', get_stylesheet_directory_uri() . '/printfriendly.css', array(), MK_SCRIPTS_STYLE_VERSION, 'print' );

	// Subscriber Count.
	$subscriber_count = get_field( 'current_subscriber_count', 'option' );
	if ( ! empty( $subscriber_count ) ) {
		wp_register_script( 'opt-in-monster-vars', get_stylesheet_directory_uri() . '/lib/js/opt-in-vars.js' );

		wp_localize_script(
			'opt-in-monster-vars',
			'oim_vars',
			array(
				'sub_count' => $subscriber_count,
			)
		);
		wp_enqueue_script( 'opt-in-monster-vars' );
	}

	// Main JS file.
	$file_path = get_stylesheet_directory_uri() . '/src/scripts/index.js';
	$file_time = ! empty( $file ) && file_exists( $file_path ) ? filemtime( $file_path ) : MK_SCRIPTS_STYLE_VERSION;
	wp_register_script( 'frontpage', $file_path, array( 'jquery' ), $file_time, true );

	if ( is_user_logged_in() ) {
		global $post;

		$is_quiz_parent_page = in_array( $post->ID, kitces_get_quiz_parent_pages(), true );
		$is_quiz_page        = kitces_is_quiz_page(); // CFP BOARD ETHICS page

		$ce_numbers = array(
			do_shortcode( '[kitces_members_contact field=CFP_CE_NUMBER none_text=""]' ),
			do_shortcode( '[kitces_members_contact field=IMCA_CE_NUMBER none_text=""]' ),
			do_shortcode( '[kitces_members_contact field=CPA_CE_NUMBER none_text=""]' ),
			do_shortcode( '[kitces_members_contact field=PTIN_CE_NUMBER none_text=""]' ),
			do_shortcode( '[kitces_members_contact field=AMERICAN_COLLEGE_ID none_text=""]' ),
		);

		$ce_numbers = array_filter( $ce_numbers );

		$data_array = array(
			'ajax_url'      => admin_url( 'admin-ajax.php' ),
			'show_ce_modal' => ( $is_quiz_page || $is_quiz_parent_page ) && empty( $ce_numbers ) ? 'true' : 'false',
		);

		wp_localize_script( 'frontpage', 'kitces_data', $data_array );
	}

	wp_enqueue_script( 'frontpage' );

	wp_enqueue_script( 'modaaal', get_stylesheet_directory_uri() . '/lib/modaal/js/modaal.min.js' );
	wp_enqueue_script( 'ie-check', get_stylesheet_directory_uri() . '/lib/js/scripts/ie-check.js', array( 'jquery' ), MK_SCRIPTS_STYLE_VERSION );

	// Turn off JetPack devicepx.
	wp_dequeue_script( 'devicepx' );

	if ( is_page() ) {
		wp_dequeue_script( 'disqus_count' );
		wp_dequeue_script( 'disqus_embed' );
	}

	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_style( 'jquery-ui-datepicker-style', 'https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css' );
}

// REMOVE EMOJI ICONS.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

// Add some gutenberg editor styles
function mk_gutenberg_assets() {
	wp_enqueue_style( 'mk-guten-block-styles', get_stylesheet_directory_uri() . '/src/gutenstyle.css', array(), MK_SCRIPTS_STYLE_VERSION );
}
add_action( 'enqueue_block_editor_assets', 'mk_gutenberg_assets' );

if ( is_admin() ) {
	function jba_disable_editor_fullscreen_by_default() {
		$script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";
		wp_add_inline_script( 'wp-blocks', $script );
	}
	add_action( 'enqueue_block_editor_assets', 'jba_disable_editor_fullscreen_by_default' );
}
