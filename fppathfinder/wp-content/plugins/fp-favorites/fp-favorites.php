<?php
/*
Plugin Name: FP Favorites
Plugin URI:  https://objectiv.co
Description: Allows users to favorite downloads
Version:     1.0
Author:      Objectiv
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: fp-favorites
*/

namespace FP_Favorites;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


define( 'FILE', __FILE__ );
define( 'PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'VERSION', 1.0 );


require_once dirname( __FILE__ ) . '/vendor/autoload.php';
require_once dirname( __FILE__ ) . '/includes/functions.php';

$fp_favorites = new Main();
