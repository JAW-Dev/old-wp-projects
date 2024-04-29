<?php
/**
 * RCP Display Messages.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use FpAccountSettings\Includes\Utilites\RCP\Messages;

if ( ! function_exists( 'fp_rcp_display_messages' ) ) {
	/**
	 * RCP Display Messages.
	 *
	 * @return array
	 */
	function fp_rcp_display_messages() {
		return ( new Messages() )->display();
	}
}
