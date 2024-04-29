<?php
/**
 * AcSync
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/Utils
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\User;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Username
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class AcSync {

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

		$this->ac_api = new \ActiveCampaign( KITCES_AC_API_URL, KITCES_AC_API_KEY );
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
		add_action( 'wp_ajax_sync_ac_member', array( $this, 'sync_member' ) );
		add_action( 'wp_ajax_nopriv_sync_ac_member', array( $this, 'sync_member' ) );
		add_action( 'show_user_profile', array( $this, 'sync_member_view' ) );
		add_action( 'edit_user_profile', array( $this, 'sync_member_view' ) );
	}

	/**
	 * Synce Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function sync_member() {
		$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) ?? '';

		if ( ! wp_verify_nonce( $nonce, 'sync-member' ) ) {
			wp_die();
		}

		$user_id = sanitize_text_field( wp_unslash( $_POST['userid'] ) ) ?? '';

		$contact = $this->get_contact( $user_id );

		$this->update_fields( $contact, $user_id );

		$tags = $contact->tags;

		update_user_meta( $user_id, 'ac_tags', $tags );

		echo 'success';
		wp_die();
	}
	public function sync_member_non_ajax( $user_id = null ) {

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		if ( ! empty( $user_id ) ) {
			$contact = $this->get_contact( $user_id );

			$this->update_fields( $contact, $user_id );

			$tags = $contact->tags;

			update_user_meta( $user_id, 'ac_tags', $tags );
		}
	}

	/**
	 * Sync Member View
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param WP_User $user The user object.
	 *
	 * @return void
	 */
	public function sync_member_view( $user ) {
		$nonce = wp_create_nonce( 'sync-member' );

		?>
		<h3>Re-sync from ActiveCampaign</h3>

		<table class="form-table ac-sync">
			<tr>
				<th>
					<a href="" id="member-sync-button" class="button" data-nonce="<?php echo esc_attr( $nonce ); ?>" data-userid="<?php echo esc_attr( $user->ID ); ?>"><?php _e( 'Sync Member', 'fp-core' ); ?></a>
					<span id="member-sync-indicator" style="display: inline-block; line-height: 2; height: 30px; margin-left: 16px;"></span>
				</th>
			</tr>
		</table>
		<?php
	}

	/**
	 * Get
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return object
	 */
	public function get_contact( $user_id = '' ) {
		if ( empty( $user_id ) ) {
			return;
		}

		$contact_id = get_user_meta( $user_id, 'ac_contact_id', true );

		if ( empty( $contact_id ) ) {
			$user_data = get_userdata( $user_id );

			if ( empty( $user_data ) ) {
				return new \stdClass();
			}

			$user_email = $user_data->user_email;

			$get_contact = $this->ac_api->api( "contact/view?email=$user_email" );

			if ( (int) $get_contact->success ) {
				$contact = $get_contact;

				update_user_meta( $user_id, 'ac_contact_id', $contact->id );

				return $contact;
			}
		}

		if ( ! empty( $contact_id ) ) {
			$get_contact = $this->ac_api->api( "contact/view?id=$contact_id" );

			if ( (int) $get_contact->success ) {
				$contact = $get_contact;
			}
		}

		return $contact;
	}

	/**
	 * Update Fields
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param object $contact The AC contact object.
	 * @param int    $user_id The user ID.
	 *
	 * @return void
	 */
	public function update_fields( $contact, $user_id ) {
		$first_name = $contact->first_name;
		$last_name  = $contact->last_name;
		$email      = $contact->email;
		$user       = get_user_by( 'ID', $user_id );

		update_user_meta( $user_id, 'first_name', $first_name );
		update_user_meta( $user_id, 'last_name', $last_name );
		update_user_meta( $user_id, 'ac_firstname', $first_name );
		update_user_meta( $user_id, 'ac_lastname', $last_name );

		$fields     = $contact->fields;
		$get_fields = array(
			'CFP_CE_NUMBER',
			'IMCA_CE_NUMBER',
			'CPA_CE_NUMBER',
			'PTIN_CE_NUMBER',
			'AMERICAN_COLLEGE_ID',
			'IAR_CE_NUMBER',
			'EXPIRATION_DATE',
		);

		if ( empty( $fields ) ) {
			return;
		}

		foreach ( $fields as $field ) {
			foreach ( $get_fields as $get_field ) {
				if ( $field->perstag === $get_field ) {
					update_user_meta( get_current_user_id(), 'ac_' . strtolower( $field->perstag ), $field->val );
				}
			}
		}

		$role = ( new \Kitces_ChargeBee_Connector() )->get_wp_role_from_ac( $email, $contact->tags );
		$user->add_role( $role );
	}
}
