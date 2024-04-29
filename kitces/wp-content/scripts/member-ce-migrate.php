<?php

set_time_limit( 0 );
require '../../wp/wp-load.php';
require '../vendor/autoload.php';

use Kitces_Members\Includes\Classes\ActiveCampaign\CustomFields;

/**
 * Init
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Init {

	/**
	 * AC API
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var Type
	 */
	protected $ac;

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init() {
		$this->add_new_roles();
	}

	/**
	 * Update CE meta
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function add_new_roles() {
		$users_args = array(
			'number' => -1,
		);

		$users = get_users( $users_args );

		foreach ( $users as $user ) {
			$fields = array(
				'CFP_CE_NUMBER',
				'IMCA_CE_NUMBER',
				'CPA_CE_NUMBER',
				'PTIN_CE_NUMBER',
				'AMERICAN_COLLEGE_ID',
				'IAR_CE_NUMBER',
			);

			foreach ( $fields as $field ) {
				if ( empty( get_user_meta( $user->ID, 'ac_' . strtolower( $field ), true ) ) ) {
					( new CustomFields() )->get_field( $field, $user->ID );
				}
			}
		}
	}
}

new init();

