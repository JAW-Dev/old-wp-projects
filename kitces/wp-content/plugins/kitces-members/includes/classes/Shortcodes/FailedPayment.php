<?php
/**
 * Failed Payment
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Shortcodes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Shortcodes;

use Kitces_Members\Includes\Classes\ActiveCampaign;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Failed Payment
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class FailedPayment {

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
		add_shortcode( 'kitces_member_has_payf', array( $this, 'has_failed_payment_tag' ) );
	}

	/**
	 * Has Failed Payment Tag
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $atts    Attributes.
	 * @param string $content The content to output.
	 *
	 * @return string
	 */
	public function has_failed_payment_tag( $atts, $content = null ) {
	    global $Kitces_ChargeBee_Connector;

		$atts = shortcode_atts(
			array(),
			$atts,
			'kitces_member_has_payf'
		);

		$contact_tags = ( new ActiveCampaign\Tags() )->list();
		$has_tag      = false;

		if ( empty( $contact_tags ) ) {
			return '';
		}

		foreach ( $contact_tags as $contact_tag ) {
			if ( strpos( $contact_tag, 'PAYF' ) !== false ) {
				$has_tag = true;
				break;
			}
		}

        $user_data = get_userdata( get_current_user_id() );

		if ( $user_data && $has_tag && $Kitces_ChargeBee_Connector->get_wp_role_from_ac( $user_data->user_email, $contact_tags ) === 'subscriber' ) {
			return $content;
		}
	}
}
