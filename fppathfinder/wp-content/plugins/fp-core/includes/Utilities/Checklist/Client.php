<?php
/**
 * Client
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Utilities/Checklist
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Utilities\Checklist;

use FP_Core\InteractiveLists\Utilities\Page;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Client
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Client {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Get Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_name() {
		$name = ! empty( $_POST['client_name'] ) ? sanitize_text_field( wp_unslash( $_POST['client_name'] ?? '' ) ) : '';

		if ( fp_is_feature_active( 'checklists_v_two' ) && fp_is_share_link() ) {
			$name = ! empty( $_POST['share-link-client-name'] ) ? sanitize_text_field( wp_unslash( $_POST['share-link-client-name'] ?? '' ) ) : '';
		}

		if ( Page::is_shared_link_post() ) {
			$entry = fp_get_share_link_db_entry();

			if ( empty( $entry ) ) {
				return '';
			}

			return $name;
		}

		return $name;
	}
}
