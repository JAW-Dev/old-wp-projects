<?php

namespace FP_Core\Group_Settings\Settings;
use \FP_Core\Member;

class No_Advisor_Names_On_PDFs extends Checkbox {
	public function __construct() {}

	public function get_label(): string {
		return "Don't show advisor names on PDFs";
	}

	public function add_hooks() {
		add_filter( 'fppathfinder_pdf_generator_person_name', array( $this, 'maybe_disable_advisor_names_on_pdfs' ), 10, 2 );
		add_filter( 'fppathfinder_include_advisor_name_field', array( $this, 'maybe_remove_advisor_name_field' ), 10, 2 );
	}

	public function should_disable( int $user_id ): bool {
		if ( ! $user_id ) {
			return false;
		}

		$member = new Member( $user_id );

		if ( ! $member->get_group() || ! $member->get_group()->is_active() ) {
			return false;
		}

		$group_id             = $member->get_group()->get_group_id();
		$prerequisite_setting = new Enable_No_Advisor_Names_On_PDFs();

		if ( ! $prerequisite_setting->get( $group_id ) ) {
			return false;
		}

		return $this->get( $group_id );
	}

	public function maybe_disable_advisor_names_on_pdfs( string $person, int $user_id ) {
		if ( ! $this->should_disable( $user_id ) ) {
			return $person;
		}

		return '';
	}

	public function maybe_remove_advisor_name_field( bool $include_field, int $user_id ) {
		if ( $this->should_disable( $user_id ) ) {
			return false;
		}

		return $include_field;
	}

	static public function maybe_init() {
		$user_id = get_current_user_id();
		$member  = new Member( $user_id );

		if ( ! $member->get_group() || ! $member->get_group()->get_group_id() ) {
			return;
		}

		$group_id          = $member->get_group()->get_group_id();
		$dependent_setting = new Enable_No_Advisor_Names_On_PDFs();
		$enabled           = $dependent_setting->get( $group_id );

		if ( ! $enabled ) {
			return false;
		}

		return new self();
	}
}
