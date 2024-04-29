<?php
/*
Plugin Name: Kitces List Builder
Plugin URI:  https://objectiv.co
Description: Allows for the generation and management of filterable lists.
Version:     1.0
Author:      Objectiv
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

namespace MKLB;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


define( 'MK_LIST_FILE', __FILE__ );
define( 'MK_LIST_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MK_LIST_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MK_LIST_VERSION', 1.0 );


require dirname( __FILE__ ) . '/vendor/autoload.php';

$MKLB = new Main();
