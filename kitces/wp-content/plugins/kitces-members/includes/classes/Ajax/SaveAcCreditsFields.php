<?php
/**
 * Save AC Credits Fields
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Ajax
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Ajax;

use Kitces_Members\Includes\Classes\ActiveCampaign\CustomFields;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Save AC Credits Fields
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class SaveAcCreditsFields {

	/**
	 * AC API
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $ac_api;

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
		add_action( 'wp_ajax_ac_credits_form', array( $this, 'save' ) );
		add_action( 'wp_ajax_nopriv_ac_credits_form', array( $this, 'save' ) );
	}

	/**
	 * Save
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function save() {
		$post = sanitize_post( wp_unslash( $_POST ) ) ?? '';

		if ( empty( $post ) ) {
			echo wp_json_encode(
				array(
					'success' => 0,
					'message' => 'Post Empty',
				)
			);
			wp_die();
		}

		parse_str( $post['data'], $data );

		if ( empty( $data ) ) {
			echo wp_json_encode(
				array(
					'success' => 0,
					'message' => 'No Data',
				)
			);
			wp_die();
		}

		if ( wp_verify_nonce( $data['ac_credits_form_submit'], 'ac_credits_form_submit' ) ) {
			echo wp_json_encode(
				array(
					'success' => 0,
					'message' => 'Nonce Failed',
				)
			);
			wp_die();
		}

		$fields = array(
			'cfp_ce_number',
			'imca_ce_number',
			'cpa_ce_number',
			'ptin_ce_number',
			'american_college_id',
			'imca_ce_submitted',
			'iar_ce_number',
		);

		foreach ( $fields as $field ) {
			$save_fields = ( new CustomFields() )->save_field( $field, $data[ $field ] );

			if ( (int) $save_fields->success ) {
				update_user_meta( get_current_user_id(), 'ac_' . $field, $data[ $field ] );
			}
		}

		echo wp_json_encode(
			array(
				'success' => 1,
				'message' => 'Success',
			)
		);
		wp_die();
	}
}
