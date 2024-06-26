<?php
/**
 * Subscription Details
 *
 * This template displays the current user's membership details with [subscription_details]
 *
 * @link        http://docs.restrictcontentpro.com/article/1600-subscriptiondetails
 *
 * For modifying this template, please see: http://docs.restrictcontentpro.com/article/1738-template-files
 *
 * @package     Restrict Content Pro
 * @subpackage  Templates/Subscription
 * @copyright   Copyright (c) 2017, Restrict Content Pro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

global $user_ID, $rcp_options;

$member = new RCP_Member( $user_ID );

$customer    = rcp_get_customer(); // currently logged in customer
$memberships = is_object( $customer ) ? $customer->get_memberships() : false;

do_action( 'rcp_subscription_details_top' );

if ( isset( $_GET['profile'] ) && 'cancelled' == $_GET['profile'] && ! empty( $_GET['membership_id'] ) ) :
	$cancelled_membership = rcp_get_membership( absint( $_GET['membership_id'] ) );
	?>
	<p class="rcp_success">
		<span><?php printf( __( 'Your %s subscription has been successfully cancelled. Your membership will expire on %s.', 'rcp' ), $cancelled_membership->get_membership_level_name(), $cancelled_membership->get_expiration_date() ); ?></span>
	</p>
<?php elseif ( isset( $_GET['cancellation_failure'] ) ) : ?>
	<p class="rcp_error"><span><?php echo esc_html( urldecode( $_GET['cancellation_failure'] ) ); ?> </span></p>
<?php endif;

$has_payment_plan = false;

if ( ! empty( $memberships ) ) {
	foreach ( $memberships as $membership ) {
		/**
		 * @var RCP_Membership $membership
		 */
		if ( $membership->is_recurring() && $membership->is_expired() && $membership->can_update_billing_card() ) : ?>
			<p class="rcp_error">
				<span><?php printf( __( 'Your %s membership has expired. <a href="%s">Update your payment method</a> to reactivate and renew your membership.', 'rcp' ), rcp_get_subscription_name( $membership->get_object_id() ), esc_url( get_permalink( $rcp_options['update_card'] ) ) ); ?></span>
			</p>
		<?php endif;

		if ( $membership->has_payment_plan() ) {
			$has_payment_plan = true;
		}
	}
}
?>
	<table class="rcp-table" id="rcp-account-overview">
		<thead>
		<tr>
			<th><?php _e( 'Membership', 'rcp' ); ?></th>
			<th><?php _e( 'Status', 'rcp' ); ?></th>
			<th><?php _e( 'Expiration/Renewal Date', 'rcp' ); ?></th>
			<?php if ( $has_payment_plan ) : ?>
				<th><?php _e( 'Times Billed', 'rcp' ); ?></th>
			<?php endif; ?>
			<th><?php _e( 'Actions', 'rcp' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php if ( ! empty( $memberships ) ) : ?>
			<?php foreach ( $memberships as $membership ) : ?>
				<tr>
					<td data-th="<?php esc_attr_e( 'Membership', 'rcp' ); ?>">
						<?php echo rcp_get_subscription_name( $membership->get_object_id() ); ?>
					</td>
					<td data-th="<?php esc_attr_e( 'Status', 'rcp' ); ?>">
						<?php rcp_print_membership_status( $membership->get_id() ); ?>
					</td>
					<td data-th="<?php esc_attr_e( 'Expiration/Renewal Date', 'rcp' ); ?>">
						<?php
						echo $membership->get_expiration_date();

						if ( $membership->is_recurring() && $membership->is_active() ) {
							echo '<p class="rcp-membership-auto-renew-notice">' . __( '(renews automatically)', 'rcp' ) . '</p>';
						}
						?>
					</td>
					<?php
					if ( $has_payment_plan ) {
						?>
						<td data-th="<?php esc_attr_e( 'Times Billed', 'rcp' ); ?>">
							<?php
							$membership_level = rcp_get_subscription_details( $membership->get_object_id() );

							if ( 0 == $membership->get_maximum_renewals() && $membership_level->duration > 0 && $membership_level->price > 0 ) {
								printf( __( '%d / Until Cancelled', 'rcp' ), $membership->get_times_billed() );
							} else {
								$renewals = ( 0 == $membership_level->price ) ? 1 : $membership->get_maximum_renewals() + 1;

								printf( __( '%d / %d', 'rcp' ), $membership->get_times_billed(), $renewals );
							}
							?>
						</td>
						<?php
					}
					?>
					<td data-th="<?php esc_attr_e( 'Actions', 'rcp' ); ?>">
						<?php
						$links = array();
						if ( apply_filters( 'fppathfinder_show_membership_upgrade_link', $membership->upgrade_possible(), $membership ) ) {
							$links[] = apply_filters( 'rcp_subscription_details_action_upgrade', '<span class="button red-button"><a href="' . esc_url( rcp_get_membership_upgrade_url( $membership->get_id() ) ) . '" title="' . esc_attr__( 'Upgrade or change your membership', 'rcp' ) . '" class="rcp_sub_details_renew">' . __( 'Upgrade/Change', 'rcp' ) . '</a></span>', $user_ID );
						}

						if ( apply_filters( 'fppathfinder_show_membership_renew_link', $membership->can_renew(), $membership ) ) {
							$links[] = apply_filters( 'rcp_subscription_details_action_renew', '<span class="button"><a href="' . esc_url( rcp_get_membership_renewal_url( $membership->get_id() ) ) . '" title="' . esc_attr__( 'Renew your membership', 'rcp' ) . '" class="rcp_sub_details_renew">' . __( 'Renew', 'rcp' ) . '</a></span>', $user_ID );
						}

						if ( $membership->can_update_billing_card() ) {
							$links[] = '<a href="' . esc_url( add_query_arg( 'membership_id', urlencode( $membership->get_id() ), get_permalink( $rcp_options['update_card'] ) ) ) . '" title="' . esc_attr__( 'Update payment method', 'rcp' ) . '" class="rcp_sub_details_update_card">' . __( 'Update payment method', 'rcp' ) . '</a>';
						}

						if ( $membership->is_active() && $membership->can_cancel() && ! $membership->has_payment_plan() ) {
							$links[] = apply_filters( 'rcp_subscription_details_action_cancel', '<a href="' . esc_url( rcp_get_membership_cancel_url( $membership->get_id() ) ) . '" title="' . esc_attr__( 'Cancel your membership', 'rcp' ) . '" class="rcp_sub_details_cancel" id="rcp_cancel_membership_' . esc_attr( $membership->get_id() ) . '">' . __( 'Cancel your membership', 'rcp' ) . '</a>', $user_ID );
						}

						/**
						 * Filters the action links HTML.
						 *
						 * @param string         $actions    Formatted HTML links.
						 * @param array          $links      Array of links before they're imploded into an HTML string.
						 * @param int            $user_ID    ID of the current user.
						 * @param RCP_Membership $membership Current membership record being displayed.
						 */
						echo apply_filters( 'rcp_subscription_details_actions', implode( '<div style="padding:0.25rem;"></div>', $links ), $links, $user_ID, $membership );

						/**
						 * Add custom HTML to the "Actions" column.
						 *
						 * @param array          $links      Existing links.
						 * @param RCP_Membership $membership Current membership record being displayed.
						 */
						do_action( 'rcp_subscription_details_action_links', $links, $membership );

						if ( $membership->is_active() && $membership->can_cancel() && ! $membership->has_payment_plan() ) {
							?>
							<script>
								// Adds a confirm dialog to the cancel link
								var cancel_link = document.querySelector( "#rcp_cancel_membership_<?php echo $membership->get_id(); ?>" );

								if ( cancel_link ) {

									cancel_link.addEventListener( "click", function ( event ) {
										event.preventDefault();

										var message = '<?php printf( __( "Are you sure you want to cancel your %s subscription? If you cancel, your membership will expire on %s.", "rcp" ), $membership->get_membership_level_name(), $membership->get_expiration_date() ); ?>';
										var confirmed = confirm( message );

										if ( true === confirmed ) {
											location.assign( event.target.href );
										} else {
											return false;
										}
									} );

								}
							</script>
							<?php
						}
						?>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td data-th="<?php esc_attr_e( 'Membership', 'rcp' ); ?>" colspan="4"><?php _e( 'You do not have any memberships.', 'rcp' ); ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>
	<table class="rcp-table" id="rcp-payment-history">
		<thead>
		<tr>
			<th><?php _e( 'Invoice #', 'rcp' ); ?></th>
			<th><?php _e( 'Membership', 'rcp' ); ?></th>
			<th><?php _e( 'Amount', 'rcp' ); ?></th>
			<th><?php _e( 'Payment Status', 'rcp' ); ?></th>
			<th><?php _e( 'Date', 'rcp' ); ?></th>
			<th><?php _e( 'Actions', 'rcp' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		$payments = is_object( $customer ) ? $customer->get_payments() : false;
		if ( $payments ) : ?>
			<?php foreach ( $payments as $payment ) : ?>
				<tr>
					<td data-th="<?php esc_attr_e( 'Invoice #', 'rcp' ); ?>"><?php echo $payment->id; ?></td>
					<td data-th="<?php esc_attr_e( 'Membership', 'rcp' ); ?>"><?php echo esc_html( $payment->subscription ); ?></td>
					<td data-th="<?php esc_attr_e( 'Amount', 'rcp' ); ?>"><?php echo rcp_currency_filter( $payment->amount ); ?></td>
					<td data-th="<?php esc_attr_e( 'Payment Status', 'rcp' ); ?>"><?php echo rcp_get_payment_status_label( $payment ); ?></td>
					<td data-th="<?php esc_attr_e( 'Date', 'rcp' ); ?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $payment->date, current_time( 'timestamp' ) ) ); ?></td>
					<td data-th="<?php esc_attr_e( 'Actions', 'rcp' ); ?>">
						<a href="<?php echo esc_url( rcp_get_invoice_url( $payment->id ) ); ?>"><?php _e( 'View Receipt', 'rcp' ); ?></a>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td data-th="<?php _e( 'Membership', 'rcp' ); ?>" colspan="6"><?php _e( 'You have not made any payments.', 'rcp' ); ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>
<?php do_action( 'rcp_subscription_details_bottom' );
