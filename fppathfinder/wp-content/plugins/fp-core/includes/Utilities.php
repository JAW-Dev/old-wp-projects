<?php

namespace FP_Core;

class Utilities {

	/**
	 * Group Is Active At Level
	 *
	 * Determine if an RCP Group is active and at the membership level id passed in.
	 *
	 * @param \RCPGA_Group $group
	 * @param string $level_id
	 *
	 * @return bool
	 */
	static function group_is_active_at_level( \RCPGA_Group $group, string $level_id ) {
		return $group->is_active() && $level_id === $group->get_membership()->get_object_id();
	}

	/**
	* Membership Is Active of Level
	*
	* Determine if an RCP Membership is active and at the membership level id passed in.
	*
	* @param RCP_Membership $membership
	*
	* @return bool
	*/
	static function rcp_membership_is_active_at_level(  $membership, string $level_id ) {
		$membership_level_id = method_exists( $membership, 'get_object_id' ) ? $membership->get_object_id() : 0;
		$is_active           = method_exists( $membership, 'is_active' ) ? $membership->is_active() : 0;

		return $is_active && $membership_level_id === $level_id;
	}

	/**
	 * Get Groups
	 *
	 * Get the groups a user belongs to.
	 *
	 * @param int $user_id
	 */
	static function get_groups( int $user_id ) {
		if ( ! function_exists( 'rcpga_get_group_members' ) ) {
			return array();
		}

		$group_member_query_args = array(
			'user_id'      => $user_id,
			'role__not_in' => array( 'invited' ), // not applicable if they're not actually a member of the group yet
		);

		$group_members = rcpga_get_group_members( $group_member_query_args );

		$get_group = function( \RCPGA_Group_Member $group_member ) {
			return $group_member->get_group();
		};

		return array_map( $get_group, $group_members );
	}

	/**
	 * Get Group Memberships
	 *
	 * Get the memberships of groups a user belongs to.
	 *
	 * @param int $user_id
	 *
	 * @return \RCP_Membership[]
	 */
	static function get_group_memberships( int $user_id ) {

		$get_group_membership = function( \RCPGA_Group $group ) {
			return $group->get_membership();
		};

		$groups = self::get_groups( $user_id );

		return array_map( $get_group_membership, $groups );
	}

	/**
	 * Response errors
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $response The response.
	 *
	 * @return void
	 */
	public static function response_errors( $response ) {
		if ( is_wp_error( $response ) ) {
			$error_codes   = function_exists( 'array_key_first' ) ? array_key_first( $response->errors ) : self::custom_array_key_first( $response->errors );
			$error_code    = $response->errors[ $error_codes ][0] ?? '';
			$error_message = $response->get_error_message();

			throw new \Exception( '[code]: ' . $error_code . ' [message]: ' . $error_message );
		}

		$success_codes = array(
			200,
			201,
			204,
		);

		if ( ! in_array( $response['response']['code'], $success_codes, true ) ) {
			throw new \Exception( '[code]: ' . $response['response']['code'] . ' [message]: ' . $response['response']['message'] );
		}
	}

	/**
	 * Custom Array Key First
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function custom_array_key_first( array $array ) {
		foreach ( $array as $key => $value ) {
			return $key;
		}
	}
}
