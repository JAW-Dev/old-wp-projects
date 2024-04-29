<?php
global $rcp_options;

$user_id       = get_current_user_id();
$group_id      = rcpga_group_accounts()->members->get_group_id( $user_id );
$total_seats   = rcpga_group_accounts()->groups->get_seats_count( $group_id );
$used_seats    = rcpga_group_accounts()->groups->get_member_count( $group_id );
$can_add       = false;
$membership_id = rcpga_group_accounts()->groups->get_membership_id( $group_id );
$membership    = false;

if ( ! empty( $membership_id ) && function_exists( 'rcp_get_membership' ) ) {
	$membership = rcp_get_membership( $membership_id );
}

if ( $membership ) {
	$can_add = $total_seats > $used_seats && $membership->is_active();
} else {
	$can_add = $total_seats > $used_seats && ! in_array( rcp_get_status( $user_id ), array( 'expired', 'pending' ) ) && ! rcp_is_expired();
}
?>

<div class="rcpga-group-dashboard">

	<?php do_action( 'rcpga_dashboard_notifications' ); ?>

	<?php do_action( 'fppathfinder_custom_group_settings' ); ?>

	<?php // require group name for member management functionality ?>
	<?php if ( rcpga_group_accounts()->groups->get_name( $group_id ) ) : ?>

		<?php echo apply_filters( 'rcpga-group-status-message', sprintf( '<p>' . __( 'You are currently using %s out of %s seats available on your account.', 'rcp-group-accounts' ) . '</p>', esc_html( $used_seats ), esc_html( $total_seats ) ), $group_id, $user_id ); ?>

		<?php rcp_get_template_part( 'group', 'members-list' ); ?>

		<?php if ( $can_add ) : ?>
			<?php rcp_get_template_part( 'group', 'member-add' ); ?>
			<?php rcp_get_template_part( 'group', 'member-import' ); ?>
		<?php endif; ?>


	<?php else : ?>
		<?php echo apply_filters( 'rcpga-group-name-required-message', '<p>' . __( 'Please add a name and description for your group to add members.', 'rcp-group-accounts' ) . '</p>', $group_id, $user_id ); ?>
	<?php endif; ?>

</div>
