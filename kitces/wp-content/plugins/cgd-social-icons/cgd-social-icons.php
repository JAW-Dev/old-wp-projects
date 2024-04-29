<?php

/*
Plugin Name:  CGD Social Icons
Description:  Display social media profile icons throughout the site.
Version: 1.0
Author: Clif Griffin Development Inc.
Author URI: http://cgd.io
*/

define('GLOBAL_SOCIAL_ICONS', dirname( __FILE__) );
define( 'GLOBAL_SOCIAL_ICONS_BASE_FILE', __FILE__ );

$social_links = array(
	'facebook'	=> 'https://www.facebook.com/Kitces',
	'twitter'	=> 'https://twitter.com/MichaelKitces',
	'linkedin'	=> 'https://www.linkedin.com/in/michaelkitces',
	'google'	=> 'https://plus.google.com/104673107673251747276',
	'pinterest'	=> 'https://www.pinterest.com/michaelkitces/',
	'youtube'	=> 'http://www.youtube.com/user/MichaelKitces?feature=watch',
	'rss'	=> 'http://feeds.kitces.com/KitcesNerdsEyeView',
	'contact'	=> 'https://www.kitces.com/contact',
);

include_once( 'inc/widgets.php' );

class GLOBAL_SOCIAL_ICONS {

	public function __construct() {
		// oh the good ol' silence
	}

	function start() {

	}
}

$GLOBAL_SOCIAL_ICONS = new GLOBAL_SOCIAL_ICONS();
$GLOBAL_SOCIAL_ICONS->start();
