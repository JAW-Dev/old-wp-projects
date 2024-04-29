<?php
/**
 * Template
 *
 * @package    FP_Core/
 * @subpackage FP_Core/InteractiveLists/Templates/ShareLink
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Templates\ShareLink;

use FP_Core\InteractiveLists\Tables\LinkShare;
use FP_Core\InteractiveLists\Utilities\CRM;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Template
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Template {

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
		add_filter( 'init', array( $this, 'set_up_share_link' ) );
	}

	public function set_up_share_link() {
		if ( fp_is_feature_active( 'link_share' ) ) {
			add_action( 'interactive_resource_client_links', array( $this, 'render' ), 10, 3 );
			add_filter( 'init', array( $this, 'maybe_can_access_resource' ) );
		}
	}

	/**
	 * Maybe Can Access Resource
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_can_access_resource() {
		global $wpdb;

		$share_key   = sanitize_text_field( wp_unslash( $_GET['sh'] ?? '' ) );
		$table       = LinkShare::get_resource_share_link_table_name();
		$entry       = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE share_key = %s", $share_key ), ARRAY_A ); // phpcs:ignore
		$resource_id = $entry['resource_id'] ?? '';

		if ( empty( $resource_id ) ) {
			return;
		}

		// Enable access if all checks passed.
		add_filter(
			'rcp_member_can_access',
			function( $can_access, $member_id, $post_id ) use ( $resource_id ) {

				// If not correct resource, restic access.
				if ( (string) $resource_id === (string) $post_id ) {
					return true;
				}

				return $can_access;
			},
			10,
			3
		);
	}

	/**
	 * Render
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $client_name    The client name.
	 * @param int    $crm_contact_id The CRM client ID.
	 * @param string $account_id     The XLR8 account ID.
	 *
	 * @return void
	 */
	public function render( $client_name, $crm_contact_id, $account_id ) {
		$nonce             = wp_create_nonce( 'share-link-nonce' );
		$is_options_active = fp_is_feature_active( 'share_link_options' );
		$current_user_id   = get_current_user_id();
		$post_id           = get_the_id();
		$button_data_attrs = " data-nonce='$nonce' data-contactId='$crm_contact_id' data-accountId='$account_id' data-advisorId='$current_user_id' data-resourceId='$post_id'";
		$button_id         = $is_options_active ? 'share-link-options-button' : 'resource-share-link-button';
		$attrs             = ! $is_options_active ? $button_data_attrs : '';
		$has_crm           = CRM::has_active_crm( get_current_user_id() ) ? 'true' : 'false';
		$linked_contact    = apply_filters( 'crm_resource_contact_id', $_POST['crm_contact_id'] ?? $_GET['contact_id'] ?? $_GET['wbcid'] ?? 0 );
		$is_linked         = $linked_contact ? 'true' : 'false';
		$button_text       = $is_options_active ? 'Share Link Options' : 'Share Link';
		$icons_dir         = get_stylesheet_directory() . '/assets/icons/src';

		?>
		<button
			id="<?php echo esc_attr( $button_id ); ?>"
			class="resource-share-link__button icon"
			<?php echo $attrs; ?>
			data-crm="<?php echo esc_attr( $has_crm ); ?>"
			data-linked="<?php echo esc_attr( $is_linked ); ?>"
			><?php echo fp_get_svg( $icons_dir, 'share' ); ?>
		</button>
		<div id="share-options-description" style="display: none;">Share Link Options</div>

		<div id="resource-share-link-modal" style="display: none">
			<p>Here is your resource URL to send to your client.</p>
			<p>The link will remain active for 30 days</p>
			<input id="resource-share-link-modal-copy-input" type="text" readonly valeu="">
			<div class="resource-share-link__modal-wrap">
				<button id="resource-share-link-modal-copy-button" class="resource-share-link__button resource-share-link__modal-copy-button" >Copy</button>
				<p id="resource-share-link-modal-copy-successful" class="resource-share-link__modal-copy-successful">The resource URL has been coppied to your clipboard!</p>
			</div>
		</div>
		<?php
	}
}
