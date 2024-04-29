<?php
/**
 * View
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Admin/RCP/Discounts
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Admin\RCP\Discounts;

use FP_Core\Utilities\ACF\DiscountSelectPopulate;
use FP_Core\Admin\RCP\Discounts\MemberDiscountCodes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * View
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class View {

	/**
	 * Member Discount Codes
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var MemberDiscountCodes
	 */
	protected $member_discount_codes;

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->member_discount_codes = new MemberDiscountCodes();
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
		if ( ! isset( $_GET['page'] ) ) {
			return;
		};

		$settings_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

		if ( ! is_admin() || ! $settings_page === 'rcp-members' ) {
			return;
		}

		$discount_populate = new DiscountSelectPopulate();

		add_action( 'rcp_membership_details_after_payments', [ $this, 'render' ] );

		if ( is_admin() && $settings_page === 'rcp-members') {
			add_filter( 'acf/load_field/name=discount_codes', [ $discount_populate, 'set' ] );
			add_filter( 'acf/update_value/name=discount_codes', [ $this->member_discount_codes, 'save' ] );
		}

		if ( function_exists( 'acf_form_head' ) ) {
			acf_form_head();
		}

	}

	/**
	 * Render
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function render() {
		$user_id   = $this->member_discount_codes->get_user_id();
		$discounts = get_user_meta( $user_id, 'rcp_user_discounts', true );

		$this->member_discount_codes->remove();
		$this->applied_discounts( $discounts );
		$this->add_discounts();
	}

	/**
	 * Applied Discounts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $discounts The applied member discounts.
	 *
	 * @return void
	 */
	public function applied_discounts( $discounts ) {
		?>
		<div id="rcp-membership-payments-wrapper" class="rcp-item-section">
			<h3><?php _e( 'Applied Discounts:', 'rcp' ); ?></h3>
			<table class="wp-list-table widefat striped">
				<thead>
				<tr>
					<th class="column-primary"><?php _e( 'Discount', 'rcp' ); ?></th>
					<th><?php _e( 'Remove', 'rcp' ); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php
					if ( ! empty( $discounts ) ) {
						foreach ( $discounts as $discount ) {
							$current_page = admin_url( sprintf( 'admin.php?%s', http_build_query( $_GET ) ) );
							$current_url  = add_query_arg( [ 'rcp_remove_code' => $discount ], $current_page );
							?>
							<tr>
								<td><?php echo esc_html( $discount ); ?></td>
								<td><a href="<?php echo esc_url( $current_url ); ?>">Remove</a></td>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Add Discount
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $discounts The applied member discounts.
	 *
	 * @return void
	 */
	public function add_discounts() {
		$groups         = function_exists( 'acf_get_field_groups' ) ? acf_get_field_groups() : [];
		$discount_group = [];
		$applied_codes  = ( new MemberDiscountCodes() )->get_applied_codes();

		if ( ! empty( $applied_codes ) ) {
			return;
		}

		foreach ( $groups as $group ) {
			if ( $group['title'] === 'RCP Discount Codes' ) {
				$discount_group = $group;
			}
		}

		?>
		<div id="rcp-membership-payments-wrapper" class="rcp-item-section">
			<h3><?php _e( 'Discount Codes:', 'rcp' ); ?></h3>
			<?php
			global $wp;
			$current_url = admin_url(
				add_query_arg(
					array( $_GET ),
					$wp->request
				)
			);

			acf_form(
				array(
					'id'           => 'apply_member_discount_code',
					'post_id'      => 'new_post',
					'field_groups' => array( $discount_group['ID'] ),
					'post_title'   => false,
					'post_content' => false,
					'form'         => true,
					'return'       => $current_url,
					'submit_value' => 'Apply Code',
				)
			);
			?>
		</div>
		<?php
	}
}
