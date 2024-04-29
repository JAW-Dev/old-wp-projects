<?php
/**
 * Is Feature Active.
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Functions/Features
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

use FP_Core\Utilities\Features\Feature;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! function_exists( 'fp_is_feature_active' ) ) {
	/**
	 * Is Feature Active.
	 *
	 * @param string $feature The feature to check if is active.
	 *
	 * @return array
	 */
	function fp_is_feature_active( $feature ) {
		return ( new Feature() )->is_active( $feature );
	}
}
