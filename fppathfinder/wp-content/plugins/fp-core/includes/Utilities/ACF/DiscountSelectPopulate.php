<?php
/**
 * Discount Select Populate.
 *
 * @package    FP_Core
 * @subpackage FP_Core/Inlcudes/Utilities/ACF
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FP_Core\Utilities\ACF;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Discount.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class DiscountSelectPopulate {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
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
		$settings_page = ! empty( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
		if ( is_admin() && $settings_page === 'theme-general-settings' ) {
			// add_filter( 'acf/load_field/name=discount_codes', [ $this, 'set' ] );
		}
	}

	/**
	 * Set
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $field The ACF field.
	 *
	 * @return boolean
	 */
	public function set( $field ) {
		$field['choices'] = array();

		$codes = function_exists( 'rcp_get_discounts' ) ? rcp_get_discounts( [ 'number' => 999 ] ) : [];

		if ( empty( $codes ) ) {
			return $field;
		}

		foreach ( $codes as $code ) {
			$field['choices'][ $code->code ] = $code->code;
		}

		return $field;
	}
}
