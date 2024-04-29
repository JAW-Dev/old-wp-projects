<?php
/**
 * Kitces Members Get Meta.
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Functions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use Kitces_Members\Includes\Classes\ActiveCampaign\CustomFields;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! function_exists( 'kitces_members_get_meta' ) ) {
    /**
     * Kitces Members Get Meta.
     *
     * @param string $field_name The field to lookup.
     * @param int|null $user_id The user ID.
     *
     * @return string|null
     * @since  1.0.0
     *
     * @author Jason Witt
     */
	function kitces_members_get_meta( string $field_name, ?int $user_id ) {
		return ( new CustomFields() )->get_field( $field_name, $user_id );
	}
}
