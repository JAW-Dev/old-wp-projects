<?php
/**
 * Main
 *
 * @package    FP_Core
 * @subpackage FP_Core/Admin
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Admin;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Main
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Main {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		if ( is_admin() ) {
			new GroupMembers\AcSync();
			new GroupMembers\AcSyncGroup();
			new RCP\CompedMembership();
			new RCP\MembershipTable();
			new RCP\AcSync();
			new RCP\Reinvite();
			new RCP\Discounts\Main();
			new RCP\Groups\Main();
		}
	}
}
