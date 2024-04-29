<?php
/*
Plugin Name: Kitces Link Builder
Plugin URI:  https://objectiv.co
Description: Allows building a trackable link in the post edit page
Version:     1.0
Author:      Objectiv
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

namespace MK_Link_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


define( 'MK_LIST_BUILDER_FILE', __FILE__ );
define( 'MK_LIST_BUILDER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MK_LIST_BUILDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MK_LIST_BUILDER_VERSION', 1.0 );


require dirname( __FILE__ ) . '/vendor/autoload.php';

$mk_link_builder = new Main();
