<?php
/**
 * Get Client Name.
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Functions/Checklist
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

use FP_Core\Utilities\Checklist\Client;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! function_exists( 'fp_get_crm_client_name' ) ) {
	/**
	 * Get Client Name.
	 *
	 * @return array
	 */
	function fp_get_crm_client_name() {
		return ( new Client() )->get_name();
	}
}
