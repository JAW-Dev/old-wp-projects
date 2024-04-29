<?php

namespace FP_Core\Integrations\Active_Campaign;

class Custom_Field_Registrar {
	static public function register_fields() {
		try {
			new Custom_Fields\Group_Membership_Level();
			new Custom_Fields\Group_Name();
			new Custom_Fields\Individual_Membership_Level();
			new Custom_Fields\Membership_Access_Level();
			new Custom_Fields\Membership_Cancellation_Date();
			new Custom_Fields\Membership_Expiration_Date();
			new Custom_Fields\Membership_Status();
			new Custom_Fields\Membership_Start_Date();
			new Custom_Fields\MasterList();
		} catch ( \Throwable $th ) {

		}
	}
}
