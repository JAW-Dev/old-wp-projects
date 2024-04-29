<?php
/**
 * Group Type
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Admin/RCP/Groups
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Admin\RCP\Groups;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Group Type
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class GroupType {

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
		add_action( 'rcpga_db_groups_post_insert', [ $this, 'set' ] );
	}

	/**
	 * Set
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $group_id The newly created group ID.
	 *
	 * @return void
	 */
	public function set( $group_id ) {
		if ( empty( $group_id ) ) {
			return;
		}

		$requested_type = ! empty( $_REQUEST['rcpga-group-type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['rcpga-group-type'] ) ) : '';

		if ( empty( $requested_type ) ) {
			return;
		}

		update_metadata( 'rcp_group', $group_id, 'group_type', $requested_type );

		die();
	}
}
