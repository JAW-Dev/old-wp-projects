<?php
/**
 * UserMeta
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/Utils
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\User;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * UserMeta
 *
 * @author Eldon Yoder
 * @since  1.0.0
 */
class UserMeta {

	/**
	 * Initialize the class
	 *
	 * @author Eldon Yoder
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_shortcode( 'mk-user-meta', array( $this, 'shortcode' ) );
	}

	public function shortcode( $atts = null ) {

		if ( ! is_array( $atts ) ) {
			return null;
		}

		$field = mk_key_value( $atts, 'field' );

		if ( $field ) {
			$meta_white_list = array(
				'nickname',
				'first_name',
				'last_name',
				'ac_firstname',
				'ac_lastname',
				'ac_tags',
			);

			$user_obj_whitelist = array(
				'ID',
				'user_nicename',
				'user_email',
				'display_name',
			);

			// Allow lowercase id
			if ( 'id' === $field ) {
				$field = 'ID';
			}

			$whitelisted = in_array( $field, $meta_white_list ) || in_array( $field, $user_obj_whitelist );

			if ( $field && is_user_logged_in() && $whitelisted ) {
				$current_user_id = get_current_user_id();
				$user_meta = get_user_meta( $current_user_id, $field, true );

				if ( empty( $user_meta ) ) {
					$current_user            = wp_get_current_user();
					$current_user_data       = $current_user->data;
					$current_user_data_array = (array) $current_user_data;

					$user_meta = mk_key_value( $current_user_data_array, $field );
				}

				if ( ! empty( $user_meta ) ) {

					if ( is_array( $user_meta ) ) {
						return implode( ', ', $user_meta );
					}

					return $user_meta;
				}
			}
		}
	}
}
