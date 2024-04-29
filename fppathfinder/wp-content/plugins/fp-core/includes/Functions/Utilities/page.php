<?php
/**
 * Page.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FP_Core\InteractiveLists\Utilities\Page;

if ( ! function_exists( 'fp_is_share_link' ) ) {
	/**
	 * Check if is the sahre link
	 *
	 * @return array
	 */
	function fp_is_share_link() {
		return ( new Page() )->is_shared_link();
	}
}
