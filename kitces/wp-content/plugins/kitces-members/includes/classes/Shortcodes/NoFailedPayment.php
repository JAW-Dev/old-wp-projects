<?php
/**
 * No Failed Payment
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
 * No Failed Payment
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class NoFailedPayment {

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
		add_shortcode( 'kitces_member_has_not_payf', array( $this, 'has_failed_payment_tag' ) );
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
		$atts = shortcode_atts(
			array(),
			$atts,
			'kitces_member_has_not_payf'
		);

        $contact_tags = ( new ActiveCampaign\Tags() )->list();
		$has_tag      = false;

		if ( empty( $contact_tags ) ) {
			return $content;
		}

		foreach ( $contact_tags as $contact_tag ) {
			if ( stripos( $contact_tag, 'PAYF' ) !== false ) {
				$has_tag = true;
				break;
			}
		}

		if ( $has_tag ) {
			return '';
		}

		return $content;
	}
}
