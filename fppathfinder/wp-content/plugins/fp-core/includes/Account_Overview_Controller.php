<?php

namespace FP_Core;

/**
 * This class handles customizations to the account overview on /your-membership
 */
class Account_Overview_Controller {
	public function __construct() {
		add_filter( 'fppathfinder_show_membership_renew_link', array( $this, 'show_membership_renew_link_filter' ), 10, 2 );
		add_filter( 'fppathfinder_show_membership_upgrade_link', array( $this, 'show_membership_upgrade_link_filter' ), 10, 2 );
	}

	/**
	 * Show Membership Renew Link Filter
	 *
	 * Determine if there are additional reasons for a renew link to not be shown in the accounts overview on /your-membership.
	 *
	 * @param bool            $should_show
	 * @param \RCP_Membership $membership
	 *
	 * @return bool
	 */
	public function show_membership_renew_link_filter( bool $should_show, \RCP_Membership $membership ) {
		return $should_show && Level_Eligibility_Controller::check( strval( $membership->get_object_id() ) );
	}

	/**
	 * Show Membership Upgrade Link Filter
	 *
	 * Determine if there are additional reasons for an upgrade link to not be shown in the accounts overview on /your-membership.
	 *
	 * @param bool            $should_show
	 * @param \RCP_Membership $membership
	 *
	 * @return bool
	 */
	public function show_membership_upgrade_link_filter( bool $should_show, \RCP_Membership $membership ) {
		if ( ! $should_show ) {
			return false;
		}

		return true;
	}
}
