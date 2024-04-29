<?php
/**
 * Core
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
 * Core
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Core {

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
		$this->ac_api = new \ActiveCampaign( KICTES_MEMBERS_AC_API_URL, KICTES_MEMBERS_AC_API_KEY );
	}
}
