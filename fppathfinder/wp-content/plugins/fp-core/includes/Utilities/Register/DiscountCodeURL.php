<?php
/**
 * Discount Code URL.
 *
 * @package    FP_Core
 * @subpackage FP_Core/Inlcudes/Utilities/Register
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FP_Core\Utilities\Register;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Discount Code URL.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class DiscountCodeURL {

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
		if ( is_admin() && $settings_page === 'rcp-discounts' ) {
			add_action( 'rcp_edit_discount_form', [ $this, 'get' ] );
		}
	}

	/**
	 * Get
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public function get( $code_id ) {
		$code = function_exists( 'rcp_get_discount' ) ? rcp_get_discount( $code_id ) : '';

		if ( empty( $code ) ) {
			return;
		}
		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="rcp-max-uses"><?php echo esc_html_e( 'Discount Code URLs', 'rcp' ); ?></label>
			</th>
			<td>
				<table>
					<tr>
						<td>
							<strong>Essentials URL:</strong>
						</td>
						<td>
							<?php $url = add_query_arg( 'discount', $code->code, home_url( '/register/essentials-package/' ) ); ?>
							<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $url ); ?></a>
						</td>
					</tr>
					<tr>
						<td>
							<strong>Deluxe URL:</strong>
						</td>
						<td>
							<?php $url = add_query_arg( 'discount', $code->code, home_url( '/register/deluxe/' ) ); ?>
							<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $url ); ?></a>
						</td>
					</tr>
					<tr>
						<td>
							<strong>Premier URL:</strong>
						</td>
						<td>
							<?php $url = add_query_arg( 'discount', $code->code, home_url( '/register/premier/' ) ); ?>
							<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $url ); ?></a>
						</td>
					</tr>
					<tr>
						<td>
							<strong>Partnership URL:</strong>
						</td>
						<td>
							<?php $url = add_query_arg( 'partnership', $code->code, home_url( '/become-a-member/' ) ); ?>
							<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $url ); ?></a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<?php
	}
}
