<?php
/**
 * CE Credits
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes;

use Kitces_Members\Includes\Classes\ActiveCampaign\CustomFields;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * CE Credits
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class CeCredits {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
	}

	/**
	 * Get Field
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function get_field( $field, $user_id ) {
		if ( empty( $field ) ) {
			return;
		}

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		if ( metadata_exists( 'user', $user_id, 'ac_' . strtolower( $field ) ) ) {
			$ac_field = get_user_meta( $user_id, 'ac_' . strtolower( $field ), true );

			return $ac_field;
		}

		if ( ! metadata_exists( 'user', $user_id, 'ac_' . strtolower( $field ) ) && metadata_exists( 'user', $user_id, 'memb_%' . $field . '%' ) ) {
			$memb_field = get_user_meta( $user_id, 'memb_%' . $field . '%', true );

			update_user_meta( $user_id, 'ac_' . strtolower( $field ), $memb_field );

			return $memb_field;
		}

		if ( ! metadata_exists( 'user', $user_id, 'ac_' . strtolower( $field ) ) && ! metadata_exists( 'user', $user_id, 'memb_%' . $field . '%' ) ) {
			$custom_field = ( new CustomFields() )->get_field( $field, $user_id );

			return $custom_field;
		}

		return '';
	}
}
