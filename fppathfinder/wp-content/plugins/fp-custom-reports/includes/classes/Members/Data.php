<?php
/**
 * Data
 *
 * @package    Custom_Reports
 * @subpackage Custom_Reports/Includes/Classes/Enterprise
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace CustomReports\Members;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Data
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Data {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function get_data() {
		$data = self::get_members();

		return $data;
	}

	/**
	 * Get Trial Members
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function get_members() {
		$data    = [];
		$members = self::query_members();

		foreach ( $members as $key => $value ) {
			if ( $value->role === 'invited' ) {
				unset( $members[ $key ] );
			}
		}

		foreach ( $members as $member ) {
			$subscription      = rcp_get_membership_level( $member->data->object_id );
			$subscription_name = method_exists( $subscription, 'get_name' ) ? $subscription->get_name() : '';

			$upgraded         = $member->data->upgraded_from ?? '';
			$old_subscription = '';
			$upgraded_from    = '';

			if ( ! empty( $upgraded ) && $upgraded > 0 ) {
				$upgraded_from    = rcp_get_membership( $upgraded );
				$old_subscription = ( rcp_get_membership_level( $upgraded_from->get_object_id() ) )->get_name();
			}

			$essentials_group = rcp_get_membership_meta( $member->data->rcp_membership_id, 'essentials-access-code', true );

			$data[] = [
				'ID'                => $member->data->ID ?? '',
				'email'             => $member->data->user_email ?? '',
				'group'             => $member->data->name ?? '',
				'status'            => ! empty( $member->data->status ) ? ucwords( $member->data->status ) : ( ! empty( $member->data->group_id ) ? 'Group Member' : '' ),
				'subscription'      => $subscription_name ?? '',
				'activated_date'    => $member->data->activated_date === '0000-00-00 00:00:00' ? null : gmdate( 'F j, Y', strtotime( $member->data->activated_date ) ),
				'renewed_date'      => $member->data->renewed_date === '0000-00-00 00:00:00' ? null : gmdate( 'F j, Y', strtotime( $member->data->renewed_date ) ),
				'cancellation_date' => $member->data->cancellation_date === '0000-00-00 00:00:00' ? null : gmdate( 'F j, Y', strtotime( $member->data->cancellation_date ) ),
				'trial_end_date'    => $member->data->trial_end_date === '0000-00-00 00:00:00' ? null : gmdate( 'F j, Y', strtotime( $member->data->trial_end_date ) ),
				'registered_date'   => $member->data->user_registered === '0000-00-00 00:00:00' ? null : gmdate( 'F j, Y', strtotime( $member->data->user_registered ) ),
				'upgraded_from'     => $upgraded_from ?? '',
				'had_trial'         => $member->data->has_trialed ? 'Yes' : '',
				'has_upgraded'      => $member->data->upgraded_from > 0 ? 'Yes' : '',
				'old_subscription'  => $member->data->upgraded_from > 0 ? $old_subscription : '',
				'essentials_group'  => $essentials_group['name'] ?? '',
			];
		}

		return $data;
	}

	/**
	 * Query Members
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function query_members() {
		global $wpdb;

		$where  = "WHERE um.meta_key = 'wp_capabilities'";
		$order  = 'DESC';
		$values = [];

		$from_date          = ! empty( $_GET['from-date'] ) ? sanitize_text_field( wp_unslash( $_GET['from-date'] ) ) . ' 00:00:00' : '';
		$to_date            = ! empty( $_GET['to-date'] ) ? sanitize_text_field( wp_unslash( $_GET['to-date'] ) ) . ' 23:59:59' : '';
		$date               = new \DateTime();
		$first_day_of_month = gmdate( $date->format( 'Y-m' ) . '-01 00:00:00' );
		$last_day_of_month  = gmdate( $date->format( 'Y-m' ) . '-t 23:59:59' );

		if ( ! empty( $from_date ) ) {
			$first_day_of_month = gmdate( $from_date . ' 00:00:00' );
		}

		if ( ! empty( $to_date ) ) {
			$last_day_of_month = gmdate( $to_date . ' 23:59:59' );
		}

		$dates_between = ! empty( $from_date ) && ! empty( $to_date );

		$activity = sanitize_text_field( wp_unslash( $_GET['activity'] ?? '' ) );

		$limit = sanitize_text_field( wp_unslash( $_GET['limit'] ?? '2000' ) );

		// Had Trialed.
		$has_trialed = sanitize_text_field( wp_unslash( $_GET['has-trialed'] ?? null ) );

		if ( $has_trialed && ! empty( $actvity ) ) {
			$activity = 'trial_end_date';
		}

		if ( ! empty( $activity ) ) {
			switch ( $activity ) {
				case 'activated_date':
					if ( $dates_between ) {
						$where .= ' AND (m.activated_date BETWEEN %s AND %s)';

						if ( $has_trialed ) {
							$where .= ' AND m.activated_date IS NOT NULL';
						}

						$values[] = $first_day_of_month;
						$values[] = $last_day_of_month;
					} else {
						$where .= ' AND m.activated_date IS NOT NULL';
					}
					break;
				case 'renewed_date':
					if ( $dates_between ) {
						$where .= ' AND (m.renewed_date BETWEEN %s AND %s)';

						if ( $has_trialed ) {
							$where .= ' AND m.renewed_date IS NOT NULL';
						}

						$values[] = $first_day_of_month;
						$values[] = $last_day_of_month;
					} else {
						$where .= ' AND m.renewed_date IS NOT NULL';
					}
					break;
				case 'cancellation_date':
					if ( $dates_between ) {
						$where .= ' AND (m.cancellation_date BETWEEN %s AND %s)';

						if ( $has_trialed ) {
							$where .= ' AND m.cancellation_date IS NOT NULL';
						}

						$values[] = $first_day_of_month;
						$values[] = $last_day_of_month;
					} else {
						$where .= ' AND m.cancellation_date IS NOT NULL';
					}
					break;
				case 'trial_end_date':
					if ( $dates_between ) {
						$where .= ' AND (m.trial_end_date BETWEEN %s AND %s)';

						if ( $has_trialed ) {
							$where .= ' AND m.trial_end_date IS NOT NULL';
						}

						$values[] = $first_day_of_month;
						$values[] = $last_day_of_month;
					} else {
						$where .= ' AND m.trial_end_date IS NOT NULL ';
					}
					break;
				default:
					break;
			}
		} else {
			if ( $dates_between ) {
				$where   .= ' AND (m.activated_date BETWEEN %s AND %s)';
				$values[] = $first_day_of_month;
				$values[] = $last_day_of_month;
			}
		}

		// Had Trialed.
		if ( ! empty( $has_trialed ) ) {
			$where .= ' AND c.has_trialed = 1 ';
		}

		$status = sanitize_text_field( wp_unslash( $_GET['status'] ?? '' ) );

		// If filter not set default to active.
		if ( ! empty( $status ) ) {
			$where   .= ' AND m.status = %s ';
			$values[] = sanitize_text_field( $status );
		}

		$subscription = sanitize_text_field( wp_unslash( $_GET['subscriptions'] ?? null ) );

		// Subscription.
		if ( ! empty( $subscription ) ) {
			$where   .= ' AND m.object_id = %d ';
			$values[] = absint( $subscription );

			if ( $subscription === '9' ) {
				$where .= " AND m.trial_end_date<>'' ";
			}
		}

		$upgraded = sanitize_text_field( wp_unslash( $_GET['upgraded-from'] ?? null ) );
		if ( ! empty( $upgraded ) ) {
			$where .= ' AND m.upgraded_from > 0 ';
		}

		$group = sanitize_text_field( wp_unslash( $_GET['group'] ?? null ) );
		if ( ! empty( $group ) ) {
			$where   .= ' AND g.group_id = %d ';
			$values[] = sanitize_text_field( $group );
		}

		// SQL.
		$users_table            = $wpdb->users;
		$users_meta_table       = $wpdb->usermeta;
		$customers_table        = rcp_get_customers_db_name();
		$memberships_table      = rcp_get_memberships_db_name();
		$memberships_meta_table = restrict_content_pro()->membership_meta_table->get_table_name();
		$group_members          = rcpga_group_accounts()->table_group_members->get_table_name();
		$groups_table           = rcpga_group_accounts()->table_groups->get_table_name();

		$query = $wpdb->prepare(
			"SELECT
				u.ID,
				u.user_email,
				u.user_registered,
				c.has_trialed,
				m.status,
				m.customer_id,
				m.object_id,
				m.activated_date,
				m.trial_end_date,
				m.renewed_date,
				m.cancellation_date,
				m.expiration_date,
				m.upgraded_from,
				mm.rcp_membership_id,
				gm.group_id,
				gm.role,
				g.owner_id,
				g.membership_id,
				g.name
				FROM {$users_table} u
				INNER JOIN {$users_meta_table} um ON u.ID = um.user_id
				LEFT JOIN {$customers_table} c ON u.ID = c.user_id
				LEFT JOIN {$memberships_table} m ON u.ID = m.user_id
				LEFT JOIN {$memberships_meta_table} mm ON m.id = mm.rcp_membership_id
				LEFT JOIN {$group_members} gm ON u.ID = gm.user_id
				LEFT JOIN {$groups_table} g ON gm.group_id = g.group_id
				{$where}
				ORDER BY u.ID {$order}
				LIMIT {$limit}",
			$values
		);

		$results = $wpdb->get_results( $query );

		if ( empty( $results ) ) {
			return [];
		}

		$get_the_members = [];

		// Set up WP_User objects.
		foreach ( $results as $user ) {
			$get_the_members[] = new \WP_User( $user );
		}

		return $get_the_members;
	}
}
