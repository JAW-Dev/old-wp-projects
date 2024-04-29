<?php
/**
 * Lookup
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Included/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms\ContactLookup;

use FP_Core\Crms\Utilities;

class Lookup {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_ajax_redtail_contact_lookup', array( $this, 'handle_contact_lookup_request' ) );
		add_filter( 'crm_resource_contact_id', array( $this, 'check_for_result_contact_id' ) );
		add_filter( 'interactive_resource_is_complete', array( $this, 'check_for_result_submit' ) );
		add_filter( 'interactive_checklist_client_name', array( $this, 'return_contact_name' ) );
	}

	/**
	 * Check for result Contact ID
	 *
	 * @param int $contact_id The contact ID
	 *
	 * @return string
	 */
	public function check_for_result_contact_id( $contact_id ) {
		$crm_contact_submit_buttons = preg_grep( '/crm\-contact\-select\-\d+/', array_keys( $_POST ) );

		if ( empty( $crm_contact_submit_buttons ) ) {
			return $contact_id;
		}

		$buttons = explode( '-', current( $crm_contact_submit_buttons ) );

		return end( $buttons );
	}

	/**
	 * Check for result Submit
	 *
	 * @param boolean $is_complete If is complete.
	 *
	 * @return boolean
	 */
	public function check_for_result_submit( bool $is_complete ): int {
		$crm_contact_submit_buttons = preg_grep( '/crm\-contact\-select\-\d+/', array_keys( $_POST ) );

		if ( empty( $crm_contact_submit_buttons ) ) {
			return $is_complete;
		}

		return false;
	}

	/**
	 * Check for result Submit
	 *
	 * @param boolean $is_complete If is complete.
	 *
	 * @return boolean
	 */
	public function return_contact_name( string $client_name ): string {
		$crm_contact_submit_buttons = preg_grep( '/crm\-contact\-select\-\d+/', array_keys( $_POST ) );

		if ( empty( $crm_contact_submit_buttons ) ) {
			return $client_name;
		}

		$buttons = explode( '-', current( $crm_contact_submit_buttons ) );

		$contact_id = end( $buttons );

		if ( ! $contact_id ) {
			return $client_name;
		}

		$response = $this->get_crm_contact( Utilities::get_active_crm(), $contact_id );

		if ( is_wp_error( $response ) ) {
			return $client_name;
		}

		if ( ! $response || 200 !== $response['response']['code'] ) {
			return $client_name;
		}

		$contact_details = json_decode( $response['body'] );

		return ContactNameGetter::get_crm_contact_name( Utilities::get_active_crm(), $contact_details );
	}

	/**
	 * Get CRM Contact
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug       The CRM slug.
	 * @param int    $contact_id The contact ID.
	 *
	 * @return void
	 */
	public function get_crm_contact( $slug, $contact_id ) {
		$crm       = ucfirst( str_replace( '_', '', $slug ) );
		$classname = 'FP_Core\\Crms\\Apis\\' . $crm . 'API';

		return $classname::get_contact( get_current_user_id(), $contact_id );
	}

	/**
	 * Get None Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_nonce_name(): string {
		return 'resource-contact-lookup';
	}

	/**
	 * Get None Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function handle_contact_lookup_request() {
		if ( ! wp_verify_nonce( $_POST['nonce'], $this->get_nonce_name() ) ) {
			return;
		}

		$query = $_POST['search'];

		if ( ! $query ) {
			return;
		}

		$contacts = $this->search_contacts( Utilities::get_active_crm(), $query );
		$response = array();

		foreach ( $contacts as $contact ) {
			$name       = $this->get_one_name_from_contact( $contact );
			$contact_id = $this->get_contact_id_from_contact( $contact );
			$account_id = $this->get_account_id_from_contact( $contact );

			if ( $name && $contact_id ) {
				$response[] = array(
					'name'       => $name,
					'contact_id' => $contact_id,
					'account_id' => $account_id,
				);
			}
		}

		$this->send_response( $response );
	}

	/**
	 * Share Link Contact Lookup
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function share_link_contact_lookup() {
		$query      = isset( $_POST['share-link-client-name'] ) ? sanitize_text_field( wp_unslash( $_POST['share-link-client-name'] ) ) : '';
		$advisor_id = isset( $_REQUEST['a'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['a'] ) ) : 0;

		if ( ! $advisor_id ) {
			return;
		}

		$contacts = $this->search_contacts( Utilities::get_active_crm( $advisor_id ), $query );

		if ( empty( $contacts ) ) {
			return;
		}

		$contacts_info = array();

		foreach ( $contacts as $contact ) {
			$name       = $this->get_one_name_from_contact( $contact );
			$contact_id = $this->get_contact_id_from_contact( $contact );
			$account_id = $this->get_account_id_from_contact( $contact );

			if ( $name && $contact_id ) {
				$contacts_info[] = array(
					'name'       => $name,
					'contact_id' => $contact_id,
					'account_id' => $account_id,
				);
			}
		}

		$response = '';

		foreach ( $contacts_info as $contact_info ) {
			$contact = trim( implode( ' ', array_reverse( explode( ' ', str_replace( ',', '', $contact_info['name'] ) ) ) ) );

			if ( strtolower( $contact ) === strtolower( $query ) ) {
				$response = array(
					'name'       => $contact_info['name'],
					'contact_id' => $contact_info['contact_id'],
					'account_id' => $contact_info['account_id'],
				);
				break;
			}
		}

		return $response;
	}

	/**
	 * Search Contacts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug  The CRM slug.
	 * @param string $query The API URL query string.
	 *
	 * @return array
	 */
	public function search_contacts( $slug, $query ) {
		if ( empty( $slug ) && fp_is_feature_active( 'checklists_v_two' ) ) {
			return [];
		}

		$crm       = ucfirst( str_replace( '_', '', $slug ) );
		$classname = 'FP_Core\\Crms\\Apis\\' . $crm . 'API';

		return $classname::search_contacts( get_current_user_id(), $query );
	}

	/**
	 * Send Response
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $contacts The contact array list from the API response.
	 *
	 * @return void
	 */
	private function send_response( array $contacts ) {
		$active_crm = Utilities::get_active_crm();
		$crm        = Utilities::get_crm_info( $active_crm );
		$crm_name   = $crm['name'];

		?>
		<div class="crm-contact-lookup-results">
			<p class="crm-heading"><?php echo esc_html( $crm_name ); ?> Contacts</p>
			<?php if ( empty( $contacts ) ) : ?>
				No Matched <?php echo esc_html( $crm_name ); ?> Contacts.
			<?php else : ?>
			<ul class="result-list">
				<?php foreach ( $contacts as $contact ) : ?>
				<li class="crm-contact-lookup-result">
					<div class="name"><?php echo $contact['name']; ?></div>
					<input type="hidden" name="account_id" value="<?php echo esc_attr( $contact['account_id'] ); ?>">
					<input class="crm-contact-lookup-result-button" type="submit" name="crm-contact-select-<?php echo $contact['contact_id']; ?>" value="link">
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
		<?php
		wp_die();
	}

	/**
	 * Get One Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param object $contact The contact.
	 *
	 * @return boolean
	 */
	public function get_one_name_from_contact( object $contact ) {
		$possible_keys = array( 'FullName4', 'FullName3', 'FullName2', 'fullname1', 'Name', 'name' );

		foreach ( $possible_keys as $key ) {
			if ( property_exists( $contact, $key ) && ! empty( $contact->{ $key } ) ) {
				return $contact->{ $key };
			}
		}

		return false;
	}

	/**
	 * Get Contact ID
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param object $contact The contact.
	 *
	 * @return boolean
	 */
	public function get_contact_id_from_contact( object $contact ) {
		$possible_keys = array( 'ContactID', 'id', 'Id' );

		foreach ( $possible_keys as $key ) {
			if ( property_exists( $contact, $key ) && ! empty( $contact->{ $key } ) ) {
				return $contact->{ $key };
			}
		}

		return false;
	}

	/**
	 * Get Account ID
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param object $contact The contact.
	 *
	 * @return boolean
	 */
	public function get_account_id_from_contact( object $contact ) {
		$possible_keys = array( 'AccountId' );

		foreach ( $possible_keys as $key ) {
			if ( property_exists( $contact, $key ) && ! empty( $contact->{ $key } ) ) {
				return $contact->{ $key };
			}
		}

		return false;
	}
}
