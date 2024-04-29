<?php
/**
 * Tags
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/ActiveCampaign
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\ActiveCampaign;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

require WP_CONTENT_DIR . '/vendor/autoload.php';

/**
 * Tags
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Tags extends Core  {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * List
	 *
	 * @param bool $force
	 * @return array
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 */
	public function list( bool $force = false ): array {
		$contact = ( new Contact() )->get();

		$tags = get_user_meta( get_current_user_id(), 'ac_tags', true );

		if ( empty( $tags ) ) {
			return array();
		}

		if ( is_null( $tags ) || ! is_array( $tags ) || $force ) {
			$tags = $contact->tags;

			update_user_meta( get_current_user_id(), 'ac_tags', $tags );
		}

		return $tags ?? array();
	}
}
