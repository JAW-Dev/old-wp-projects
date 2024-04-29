<?php
require_once get_stylesheet_directory() . '/lib/functions/_widgets/announcements-ajax.php';
require_once get_stylesheet_directory() . '/lib/functions/_widgets/announcements.php';
require_once get_stylesheet_directory() . '/lib/functions/_widgets/michael-reading.php';
require_once get_stylesheet_directory() . '/lib/functions/_widgets/out-about.php';
require_once get_stylesheet_directory() . '/lib/functions/_widgets/praise.php';
require_once get_stylesheet_directory() . '/lib/functions/_widgets/social.php';

// register Foo_Widget widget
function register_kitces_widgets() {
	register_widget( 'Nerds_Eye_View_Praise_Widget' );
	register_widget( 'Mobile_Menu_Social_Widget' );
	register_widget( 'What_Michael_Reading_Widget' );
	register_widget( 'Out_And_About_Widget' );
	register_widget( 'Announcements_Widget' );
	register_widget( 'Announcements_Ajax_Widget' );
}

add_action( 'widgets_init', 'register_kitces_widgets' );
