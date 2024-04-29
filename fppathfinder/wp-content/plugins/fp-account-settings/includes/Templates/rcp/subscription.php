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

use RCP\Membership_Level;
use FpAccountSettings\Includes\Classes\Conditionals;

global $user_ID, $rcp_options;

$member = new RCP_Member( $user_ID );

$customer            = rcp_get_customer(); // currently logged in customer
$memberships         = is_object( $customer ) ? $customer->get_memberships() : false;
$payments            = is_object( $customer ) ? $customer->get_payments() : false;
$enable_cancel_modal = get_field( 'cancel_modal_enable_cancel_modal', 'option' );

fp_rcp_display_messages();

if ( isset( $_GET['profile'] ) && 'cancelled' == $_GET['profile'] && ! empty( $_GET['membership_id'] ) ) :
	$cancelled_membership = rcp_get_membership( absint( $_GET['membership_id'] ) );
	?>
	<p class="rcp_success">
		<span><?php printf( __( 'Your %1$s subscription has been successfully cancelled. Your membership will expire on %2$s.', 'rcp' ), $cancelled_membership->get_membership_level_name(), $cancelled_membership->get_expiration_date() ); ?></span>
	</p>
<?php elseif ( isset( $_GET['cancellation_failure'] ) ) : ?>
	<p class="rcp_error"><span><?php echo esc_html( urldecode( $_GET['cancellation_failure'] ) ); ?> </span></p>
	<?php
endif;

$has_payment_plan = false;

$is_active_membership = false;

if ( ! empty( $memberships ) ) {
	foreach ( $memberships as $membership ) {
		if ( $membership->get_status() === 'active' ) {
			$is_active_membership = true;
			break;
		}
	}

	foreach ( $memberships as $membership ) {
		/**
		 * @var RCP_Membership $membership
		 */
		if ( $membership->is_recurring() && $membership->is_expired() && $membership->can_update_billing_card() && ! $is_active_membership ) :
			?>
			<p class="rcp_error">
				<span>
					<?php
					printf(
						__( 'Your %1$s membership has expired. <a href="%2$s">Update your payment method</a> to reactivate and renew your membership.', 'rcp' ),
						$membership->get_membership_level_name(),
						esc_url( add_query_arg( 'membership_id', urlencode( $membership->get_id() ), get_permalink( $rcp_options['update_card'] ) ) )
					);
					?>
				</span>
			</p>
			<?php
		endif;

		if ( $membership->has_payment_plan() ) {
			$has_payment_plan = true;
		}
	}
}
?>
<section class="body-section body-section__rcp_membership">
	<header class="body-section__header">
		<h2>Membership</h2>
	</header>

	<div class="body-section__membership">
		<?php
		if ( ! empty( $memberships ) ) :
			foreach ( $memberships as $membership ) :
				?>
				<div class="white-content-box rcp_membership">
					<div class="white-content-box__inner">
						<div class="white-content-box__left rcp_membership__stats">
							<div class="rcp_membership__type">
								<?php echo esc_html( $membership->get_membership_level_name() ); ?>
							</div>

							<div class="rcp_membership__status pill-button">
								<?php rcp_print_membership_status( $membership->get_id() ); ?>
							</div>

							<div class="rcp_membership__expiration">
								<?php
								if ( $has_payment_plan ) {
									$membership_level = rcp_get_membership_level( $membership->get_object_id() );

									if ( $membership_level instanceof Membership_Level ) {
										if ( 0 == $membership->get_maximum_renewals() && ! $membership_level->is_lifetime() && ! $membership_level->is_free() ) { // phpcs:ignore
											printf( __( '%d / Until Cancelled', 'rcp' ), $membership->get_times_billed() ); // phpcs:ignore
										} else {
											$renewals = $membership_level->is_free() ? 1 : $membership->get_maximum_renewals() + 1;

											printf( __( '%d / %d', 'rcp' ), $membership->get_times_billed(), $renewals ); // phpcs:ignore
										}
									}
								} else {
									echo esc_html( 'Expires ' . $membership->get_expiration_date() );
								}
								?>
							</div>

							<div class="rcp_membership__renew">
								<?php
								if ( $membership->is_recurring() && 'active' === $membership->get_status() ) {
									echo '<div class="rcp-membership-auto-renew-notice">' . __( '(renews automatically)', 'rcp' ) . '</div>'; // phpcs:ignore
								}

								if ( $membership->is_active() && $membership->can_toggle_auto_renew() ) {
									echo '<div class="rcp-auto-renew-toggle">';
									if ( $membership->is_recurring() ) {
										$toggle_off_url = wp_nonce_url(
											add_query_arg(
												array(
													'rcp-action'    => 'disable_auto_renew',
													'membership-id' => urlencode( $membership->get_id() ), // phpcs:ignore
												)
											),
											'rcp_toggle_auto_renew_off'
										);

										echo '<a href="' . esc_url( $toggle_off_url ) . '" class="rcp-disable-auto-renew">' . __( 'Disable auto renew', 'rcp' ) . '</a>'; // phpcs:ignore
									} else {
										$toggle_on_url = wp_nonce_url(
											add_query_arg(
												array(
													'rcp-action'    => 'enable_auto_renew',
													'membership-id' => urlencode( $membership->get_id() ), // phpcs:ignore
												)
											),
											'rcp_toggle_auto_renew_on'
										);

										echo '<a href="' . esc_url( $toggle_on_url ) . '" class="rcp-enable-auto-renew" data-expiration="' . esc_attr( $membership->get_expiration_date( true ) ) . '">' . __( 'Enable auto renew', 'rcp' ) . '</a>'; // phpcs:ignore
									}
									echo '</div>';
								}
								?>
							</div>
						</div>

						<div class="white-content-box__right rcp_membership__actions">
							<?php
							$links         = array();
							$membership_id = fp_get_membership_package_id();

							if ( $membership->upgrade_possible() && ! Conditionals::is_deluxe_or_premier_group_member() ) {
								if ( fp_get_membership_access_level( get_current_user_id() ) === 4 ) {
									$links[] = apply_filters( 'rcp_subscription_details_action_upgrade', '<a href="' . esc_url( rcp_get_membership_upgrade_url( $membership->get_id() ) ) . '" title="' . esc_attr__( 'Change', 'rcp' ) . '" class="rcp_sub_details_change_membership">' . __( 'Change', 'rcp' ) . '</a>', $user_ID );
								} elseif ( fp_get_membership_access_level( get_current_user_id() ) === 5 || fp_get_membership_access_level( get_current_user_id() ) === 6 || fp_get_membership_access_level( get_current_user_id() ) === 7 ) {
									$links[] = apply_filters( 'rcp_subscription_details_action_upgrade', '<a href="mailto:support@fppathfinder.com" title="' . esc_attr__( 'Contact fpPathfinder Support', 'rcp' ) . '" class="rcp_sub_details_change_membership">Contact ' . __( 'support@fppathfinder.com', 'rcp' ) . '</br>to change your membership level</a>', $user_ID );
								} else {
									$links[] = apply_filters( 'rcp_subscription_details_action_upgrade', '<a href="' . esc_url( rcp_get_membership_upgrade_url( $membership->get_id() ) ) . '" title="' . esc_attr__( 'Upgrade/Change', 'rcp' ) . '" class="rcp_sub_details_change_membership">' . __( 'Upgrade/Change', 'rcp' ) . '</a>', $user_ID );
								}
							}

							if ( $membership->can_update_billing_card() ) {
								$links[] = '<a href="' . esc_url( add_query_arg( 'membership_id', urlencode( $membership->get_id() ), get_permalink( $rcp_options['update_card'] ) ) ) . '" title="' . esc_attr__( 'Update Payment Method', 'rcp' ) . '" class="rcp_sub_details_update_card">' . __( 'Update Payment Method', 'rcp' ) . '</a>'; // phpcs:ignore
							}

							if ( $membership->can_renew() ) {
								$links[] = apply_filters( 'rcp_subscription_details_action_renew', '<a href="' . esc_url( rcp_get_membership_renewal_url( $membership->get_id() ) ) . '" title="' . esc_attr__( 'Renew', 'rcp' ) . '" class="rcp_sub_details_renew">' . __( 'Renew', 'rcp' ) . '</a>', $user_ID );
							}

							if ( $membership->is_active() && $membership->can_cancel() && ! $membership->has_payment_plan() ) {
								if ( $enable_cancel_modal ) {
									$membership_id    = $membership->get_id();
									$cancel_link_text = get_field( 'cancel_modal_link_text', 'option' );
									if ( empty( $cancel_link_text ) ) {
										$cancel_link_text = 'Cancel your membership';
									}
									$links[] = '<a class="subscription-cancel-link" href="#cancel-modal-' . $membership_id . '">' . $cancel_link_text . '</a>';
								} else {
									$links[] = apply_filters( 'rcp_subscription_details_action_cancel', '<a href="' . esc_url( rcp_get_membership_cancel_url( $membership->get_id() ) ) . '" title="' . esc_attr__( 'Cancel', 'rcp' ) . '" class="rcp_sub_details_cancel" id="rcp_cancel_membership_' . esc_attr( $membership->get_id() ) . '">' . __( 'Cancel', 'rcp' ) . '</a>', $user_ID );
								}
							}

							/**
							 * Filters the action links HTML.
							 *
							 * @param string         $actions    Formatted HTML links.
							 * @param array          $links      Array of links before they're imploded into an HTML string.
							 * @param int            $user_ID    ID of the current user.
							 * @param RCP_Membership $membership Current membership record being displayed.
							 */
							echo apply_filters( 'rcp_subscription_details_actions', implode( '<br/>', $links ), $links, $user_ID, $membership ); // phpcs:ignore

							/**
							 * Add custom HTML to the "Actions" column.
							 *
							 * @param array          $links      Existing links.
							 * @param RCP_Membership $membership Current membership record being displayed.
							 */
							do_action( 'rcp_subscription_details_action_links', $links, $membership );

							if ( $membership->is_active() && $membership->can_cancel() && ! $membership->has_payment_plan() && $enable_cancel_modal ) {
								$downgrade_title                = get_field( 'cancel_modal_downgrade_title', 'option' );
								$downgrade_blurb                = get_field( 'cancel_modal_downgrade_blurb', 'option' );
								$downgrade_button_text          = get_field( 'cancel_modal_downgrade_button_text', 'option' );
								$downgrade_continue_button_text = get_field( 'cancel_modal_downgrade_continue_button_text', 'option' );
								$cancel_title                   = get_field( 'cancel_modal_cancel_title', 'option' );
								$cancel_blurb                   = get_field( 'cancel_modal_cancel_blurb', 'option' );
								$cancel_form                    = get_field( 'cancel_modal_cancel_form', 'option' );
								$cancel_left_button             = get_field( 'cancel_modal_cancel_left_button', 'option' );
								$finish_cancel_button_text      = get_field( 'cancel_modal_finish_cancel_button_text', 'option' );

								$ess_check_user_id     = get_current_user_id();
								$ess_memb_check_member = $ess_check_user_id ? new \FP_Core\Member( $ess_check_user_id ) : false;
								$is_essentials_member  = $ess_memb_check_member && $ess_memb_check_member->is_active_at_level( FP_ESSENTIALS_ID );

								if ( empty( $cancel_membership_button_text ) ) {
									$cancel_membership_button_text = 'Continue & Cancel';
								}

								$cancel_url = esc_url( rcp_get_membership_cancel_url( $membership->get_id() ) );

								$downgrade_link = null;
								if ( apply_filters( 'fppathfinder_show_membership_upgrade_link', $membership->upgrade_possible(), $membership ) && ! $is_essentials_member ) {
									if ( empty( $downgrade_button_text ) ) {
										$downgrade_button_text = 'Switch Plan';
									}

									$downgrade_link = apply_filters( 'rcp_subscription_details_action_upgrade', '<span class="button small-pad"><a href="' . esc_url( rcp_get_membership_upgrade_url( $membership->get_id() ) ) . '" title="' . esc_attr__( 'Upgrade or change your membership', 'rcp' ) . '" class="rcp_sub_details_renew">' . $downgrade_button_text . '</a></span>', $user_ID );
								}

								$display_downgrade = ! $is_essentials_member && ! empty( $downgrade_blurb ) && ! empty( $downgrade_button_text ) && ! empty( $downgrade_link );

								$downgrade_class = '';
								if ( $display_downgrade ) {
									$downgrade_class = 'has-downgrade-option';
								}

								?>
								<div class="display-none" id="cancel-modal-<?php echo $membership_id; ?>">
									<div class="inner-cancel-content <?php echo $downgrade_class; ?>">

										<?php if ( $display_downgrade ) : ?>
											<div class="downgrade-option">
												<?php if ( ! empty( $downgrade_title ) ) : ?>
													<h4 class=""><?php echo $downgrade_title; ?></h4>
												<?php else : ?>
													<h4 class="">Are You Sure You Want to Cancel?</h4>
												<?php endif; ?>

												<div class="downgrade-blurb"><?php echo $downgrade_blurb; ?></div>

												<div class="button-wrap">
													<?php if ( ! empty( $downgrade_link ) ) : ?>
														<?php echo $downgrade_link; ?>
													<?php endif; ?>
													<span class="continue-cancel button red-button small-pad">
														<?php if ( ! empty( $downgrade_continue_button_text ) ) : ?>
															<a href="#" class=""><?php echo $downgrade_continue_button_text; ?></a>
														<?php else : ?>
															<a href="">Continue to Cancel</a>
														<?php endif; ?>
													</span>
												</div>

											</div>
										<?php endif; ?>

										<div class="cancel-form-wrap">
											<?php if ( ! empty( $cancel_title ) ) : ?>
												<h4 class=""><?php echo $cancel_title; ?></h4>
											<?php else : ?>
												<h4 class="">We're Sorry To See You Go 😢</h4>
											<?php endif; ?>

											<?php if ( ! empty( $cancel_blurb ) ) : ?>
												<div class="cancel-blurb"><?php echo $cancel_blurb; ?></div>
											<?php endif; ?>

											<?php if ( ! empty( $cancel_form ) ) : ?>
												<div class="form-wrap"><?php gravity_form( $cancel_form['id'], false, false, false, $field_values = array( 'cancel_url' => get_site_url() . $cancel_url ), true, null, true ); ?></div>
											<?php endif; ?>

											<div class="warning"><?php printf( __( 'Are you sure you want to continue and cancel your %1$s subscription? Your membership will expire on %2$s.', 'rcp' ), $membership->get_membership_level_name(), $membership->get_expiration_date() ); ?></div>

											<div class="button-wrap">
												<?php if ( ! empty( $cancel_left_button ) ) : ?>
													<span class="button">
														<a href="<?php echo $cancel_left_button['url']; ?>"><?php echo $cancel_left_button['title']; ?></a>
													</span>
												<?php endif; ?>
												<span class="submit-cancel-button button red-button">
													<?php if ( ! empty( $finish_cancel_button_text ) ) : ?>
														<a href="#"><?php echo $finish_cancel_button_text; ?></a>
													<?php else : ?>
														<a href="#">Submit and Cancel</a>
													<?php endif; ?>
												</span>
											</div>

										</div>

									</div>
								</div>
								<?php
							}

							?>
						</div>
					</div>
				</div>
				<?php
			endforeach;
		endif;
		?>
	</div>
</section>
<section class="body-section body-section__rcp_invoice">
	<header class="body-section__header">
		<h2>Invoices / Payments</h2>
	</header>

	<div class="body-section__invoices">
		<?php
		if ( ! empty( $payments ) ) :
			foreach ( $payments as $payment ) :
				?>
				<div class="white-content-box rcp_invoice">
					<div class="white-content-box__inner">
						<div class="white-content-box__left rcp_membership__stats">
							<div class="rcp_invoice__date"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $payment->date, current_time( 'timestamp' ) ) ) ); // phpcs:ignore ?></div>
							<div class="rcp_invoice__id">ID: #<?php echo esc_html( $payment->id ); ?></div>
							<div class="rcp_invoice__status pill-button pill-button__red"><?php echo esc_html( rcp_get_payment_status_label( $payment ) ); ?></div>
							<div class="rcp_invoice__type"><?php echo esc_html( $payment->subscription ); ?></div>
							<div class="rcp_invoice__amount"><?php echo esc_html( rcp_currency_filter( $payment->amount ) ); ?></div>
						</div>
						<div class="white-content-box__right rcp_membership__actions">
							<div class="rcp_invoice__receipt">
								<?php if ( in_array( $payment->status, array( 'pending', 'abandoned', 'failed' ) ) && empty( $payment->transaction_id ) ) : // phpcs:ignore ?>
									<a href="<?php echo esc_url( rcp_get_payment_recovery_url( $payment->id ) ); ?>">
										<?php echo 'failed' === $payment->status ? __( 'Retry Payment', 'rcp' ) : __( 'Complete Payment', 'rcp' ); // phpcs:ignore ?>
									</a> <br/>
								<?php endif; ?>
								<a href="<?php echo esc_url( rcp_get_invoice_url( $payment->id ) ); ?>"><?php _e( 'View Receipt', 'rcp' ); // phpcs:ignore ?></a>
							</div>
						</div>
					</div>
				</div>
				<?php
			endforeach;
		else :
			if ( Conditionals::is_enterprise_essentials() || Conditionals::is_enterprise_deluxe() || Conditionals::is_enterprise_premier() ) :
				?>
				<div class="body-section__box rcp_invoice"><?php echo wp_kses_post( 'email <a href="mailto:support@fppathfinder.com">support@fpPathfinder.com</a> for your receipt' ); ?></div>
			<?php else : ?>
				<div class="body-section__box rcp_invoice"><?php esc_html_e( 'You have not made any payments.', 'rcp' ); ?></div>
				<?php
			endif;
		endif;
		?>
	</div>
</section>
<?php
do_action( 'rcp_subscription_details_bottom' );

function display_messages() {
	if ( ! isset( $_GET['rcp-message'] ) ) {
		return;
	}

	static $displayed = false;

	// Only one message at a time.
	if ( $displayed ) {
		return;
	}

	$message = '';
	$type    = 'success';
	$notice  = $_GET['rcp-message']; // phpcs:ignore

	switch ( $notice ) {
		case 'email-verified':
			$message = __( 'Your email address has been successfully verified.', 'rcp' );
			break;
		case 'verification-resent':
			$message = __( 'Your verification email has been re-sent successfully.', 'rcp' );
			break;
		case 'auto-renew-enabled':
			$message = __( 'Auto renew has been successfully enabled.', 'rcp' );
			break;
		case 'auto-renew-disabled':
			$message = __( 'Auto renew has been successfully disabled.', 'rcp' );
			break;
		case 'auto-renew-enable-failure':
			$error   = isset( $_GET['rcp-auto-renew-message'] ) ? rawurldecode( $_GET['rcp-auto-renew-message'] ) : ''; // phpcs:ignore
			$message = sprintf( __( 'Failed to enable auto renew: %s' ), esc_html( $error ) );
			break;

		case 'auto-renew-disable-failure':
			$error   = isset( $_GET['rcp-auto-renew-message'] ) ? rawurldecode( $_GET['rcp-auto-renew-message'] ) : ''; // phpcs:ignore
			$message = sprintf( __( 'Failed to disable auto renew: %s' ), esc_html( $error ) );
			break;

	}

	if ( empty( $message ) ) {
		return;
	}

	$class = ( 'success' === $type ) ? 'rcp_success' : 'rcp_error';
	printf( '<p class="%s"><span>%s</span></p>', $class, esc_html( $message ) ); // phpcs:ignore

	$displayed = true;
}
