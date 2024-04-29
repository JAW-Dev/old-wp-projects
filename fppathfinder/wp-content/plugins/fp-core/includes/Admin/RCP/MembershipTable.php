<?php
/**
 * Membership Table
 *
 * @package    FP_Core
 * @subpackage FP_Core/Admin/RCP
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Admin\RCP;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Membership Table
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class MembershipTable {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'rcp_memberships_list_table_columns', array( $this, 'add_column' ) );
		add_filter( 'rcp_memberships_list_table_column_group', array( $this, 'group_column_value' ), 10, 2 );
	}

	/**
	 * Add Column
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $columns The existsing columns.
	 *
	 * @return void
	 */
	public function add_column( $columns ) {
		$key  = 'group';
		$name = __( 'Group', 'rcp' );

		$columns[ $key ] = $name;

		return $columns;
	}

	/**
	 * Group Column Value
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function group_column_value( $value, $membership ) {

		$user_id      = $membership->get_user_id();
		$group_member = rcpga_user_is_group_member( $user_id );

		if ( ! $group_member ) {
			return '';
		}

		$group_member_query_args = array(
			'user_id'      => $user_id,
			'role__not_in' => array( 'invited' ),
		);

		$groups   = rcpga_get_group_members( $group_member_query_args );
		$group_id = isset( $groups[0] ) ? $groups[0]->get_group_id() : '';
		$group    = rcpga_get_group( $group_id );

		if ( ! empty( $group ) ) {
			return $group->name;
		}

		return $value;
	}
}
