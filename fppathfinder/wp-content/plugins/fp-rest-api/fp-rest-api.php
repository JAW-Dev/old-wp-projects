<?php
/**
 *
 * Plugin Name: fpPathfinder REST API
 * Version:     1.0.0
 * Author:      Objectiv
 * Author URI:  https://objectiv.co/
 * License:     GPLv2
 * Domain Path: /languages
 *
 * @package    FP_REST_API
 * @author     Objectiv
 * @copyright  Copyright (c) 2019, Objectiv
 * @license    GNU General Public License v2 or later
 * @version    1.0.0
**/

namespace FP_REST_API;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

require dirname( __FILE__ ) . '/vendor/autoload.php';

new Main();
