<?php
/**
 *
 * Plugin Name: fpPathfinder Interactive Checklists
 * Description: Enables custom functionality associated with the Interactive Checklists feature.
 * Version:     1.0.0
 * Author:      Objectiv
 * Author URI:  https://objectiv.co/
 * License:     GPLv2
 * Domain Path: /languages
 *
 * @package    FP_Interactive_Checklists
 * @author     Objectiv
 * @copyright  Copyright (c) 2019, Objectiv
 * @license    GNU General Public License v2 or later
 * @version    1.0.0
**/

namespace FP_Interactive_Checklists;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

require dirname( __FILE__ ) . '/vendor/autoload.php';
require dirname( __FILE__ ) . '/js/setup.php';

// new Main();
// new Flow();

// $db = new Database();
// $db->setup_tables();
