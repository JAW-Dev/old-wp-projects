<?php
/**
 * Member
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Access
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Access;

use Kitces_Members\Includes\Classes\ActiveCampaign\Tags;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Member
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Member {

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
	 * Is Valid Member
	 *
	 * @return boolean
	 * @since  1.0.0
	 *
	 * @author Jason Witt
	 */
	public function is_valid_member(): bool {
		return $this->is_valid_premier_member() || $this->is_valid_basic_member() || $this->is_valid_student_member() || $this->is_valid_trial_member();
	}

	/**
	 * Is Valid Student Member
	 *
	 * @return boolean
	 * @since  1.0.0
	 *
	 * @author Jason Witt
	 */
	public function is_valid_student_member(): bool {
		$tags = $this->get_the_tags();

		if ( in_array( 'MemberAdmin-Student', $tags, true ) && ! in_array( 'MemberAdmin-StudentPAYF', $tags, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Is Valid Reader Member
	 *
	 * @return boolean
	 * @since  1.0.0
	 *
	 * @author Jason Witt
	 */
	public function is_valid_reader_member(): bool {
		$tags = $this->get_the_tags();

		if ( in_array( 'MemberAdmin-Reader', $tags, true ) && ! in_array( 'MemberAdmin-ReaderPAYF', $tags, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Is Valid Basic Member
	 *
	 * @return boolean
	 * @since  1.0.0
	 *
	 * @author Jason Witt
	 */
	public function is_valid_basic_member(): bool {
		$tags = $this->get_the_tags();

		if ( in_array( 'MemberAdmin-Basic', $tags, true ) && ! in_array( 'MemberAdmin-Basic-PAYF', $tags, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Is Valid Premier Member
	 *
	 * @return boolean
	 * @since  1.0.0
	 *
	 * @author Jason Witt
	 */
	public function is_valid_premier_member(): bool {
		$tags = $this->get_the_tags();

		if ( in_array( 'MemberAdmin-NewsletterMember', $tags, true ) && ! in_array( 'MemberAdmin-NewsletterMemberPAYF', $tags, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Is Valid Trial Member
	 *
	 * @return boolean
	 * @since  1.0.0
	 *
	 * @author Jason Witt
	 */
	public function is_valid_trial_member(): bool {
		$tags = $this->get_the_tags();

		if ( in_array( 'Reports-Legacy-TrialMembership-ReportMember', $tags, true ) && ! in_array( 'Reports-Legacy-TrialMembership-ReportMemberPAYF', $tags, true ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Get The Tags
	 *
	 * @return array
	 * @since  1.0.0
	 *
	 * @author Jason Witt
	 */
	public function get_the_tags(): array {
		return ( new Tags() )->list();
	}
}
