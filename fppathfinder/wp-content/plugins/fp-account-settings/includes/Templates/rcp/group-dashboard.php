<?php
/**
 * Template: Group Dashboard
 *
 * For modifying this template, please see: http://docs.restrictcontentpro.com/article/1738-template-files
 *
 * @package   rcp-group-accounts
 * @copyright Copyright (c) 2019, Restrict Content Pro team
 * @license   GPL2+
 * @since     1.0
 */

use function RCPGA\Shortcodes\get_dashboard;

global $rcp_options;

$dashboard = get_dashboard();
?>

<div class="rcpga-group-dashboard">
	<?php
	/**
	 * Display group notifications.
	 *
	 * @param \RCPGA_Group        $current_group
	 * @param \RCPGA_Group_Member $current_group_member
	 */
	do_action( 'rcpga_dashboard_notifications', $dashboard->get_group(), $dashboard->get_group_member() );

	/**
	 * Show seat count if specified.
	 */
	if ( $dashboard->show( 'seat_count' ) ) {
		$seats_message = sprintf(
			'<div class="tabs__body-section-heading"><p>' . __( 'You are currently using %d out of %d seats available in your group.', 'rcp-group-accounts' ) . '</p></div>',
			esc_html( $dashboard->get_group()->get_member_count() ),
			esc_html( $dashboard->get_group()->get_seats() )
		);

		/**
		 * Filters the group seats message on the dashboard.
		 *
		 * @param string $seats_message Formatted message.
		 * @param int    $group_id      ID of the group being rendered.
		 * @param int    $user_id       ID of the current user.
		 */
		echo apply_filters( 'rcpga-group-status-message', $seats_message, $dashboard->get_group()->get_group_id(), $dashboard->get_group_member()->get_user_id() );
	}

	/**
	 * Show members list if specified.
	 */
	if ( $dashboard->show( 'members_list' ) ) {
		rcp_get_template_part( 'group', 'members-list' );
	}

	/**
	 * Show member add/import if allowed and specified.
	 */
	if ( $dashboard->get_group()->can_add_members() ) {
		if ( $dashboard->show( 'member_add' ) ) {
			rcp_get_template_part( 'group', 'member-add' );
		}
		if ( $dashboard->show( 'csv_import' ) ) {
			rcp_get_template_part( 'group', 'member-import' );
		}
		if ( $dashboard->show( 'group_member_join_info' ) && ! empty( $rcp_options['groups_allow_member_registration'] ) ) {
			rcp_get_template_part( 'group', 'member-join-info' );
		}
	}

	/**
	 * Show group edit if specified.
	 */
	// if ( $dashboard->show( 'group_edit' ) ) {
	// 	rcp_get_template_part( 'group', 'edit' );
	// }
	?>

</div>
