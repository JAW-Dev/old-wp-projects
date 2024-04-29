<?php
/**
 * Filters
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
 * Filters
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Filters {

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
	 * Date Range
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug The report slug.
	 *
	 * @return void
	 */
	public static function date_range( $slug ) {
		$date              = new \DateTime();
		$from_default_date = gmdate( $date->format( 'Y-m' ) . '-01' );
		$to_default_date   = gmdate( $date->format( 'Y-m' ) . '-t' );
		$from_value        = sanitize_text_field( wp_unslash( $_GET['from-date'] ?? '' ) );
		$to_value          = sanitize_text_field( wp_unslash( $_GET['to-date'] ?? '' ) );
		?>
		<div class="custom-reports__filters-date-range">
			<label for="<?php echo esc_attr( $slug ); ?>-from-date">From</label>
			<input type="text" id="<?php echo esc_attr( $slug ); ?>-from-date" name="date_from" class="report-filter" placeholder="Date From" value="<?php echo esc_attr( $from_value ); ?>">
			<label for="<?php echo esc_attr( $slug ); ?>-to-date">To</label>
			<input type="text" id="<?php echo esc_attr( $slug ); ?>-to-date" name="date_to" class="report-filter" placeholder="Date To" value="<?php echo esc_attr( $to_value ); ?>">
		</div>
		<?php
	}

	/**
	 * Subscriptions
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug The report slug.
	 *
	 * @return void
	 */
	public static function subscriptions( $slug ) {
		$param = sanitize_text_field( wp_unslash( $_GET['subscriptions'] ?? '' ) );
		?>
		<select id="<?php echo esc_attr( $slug ); ?>-subscriptions" name="subscriptions" class="custom-reports__filters-subscriptions">
			<option value="">Select Membership Level</option>
			<?php
			foreach ( rcp_get_membership_levels() as $membership ) {
				?>
				<option value="<?php echo esc_attr( $membership->get_id() ); ?>" <?php selected( $membership->get_id(), $param ); ?>><?php echo esc_html( $membership->get_name() ); ?></option>
				<?php
			}
			?>
		</select>
		<?php
	}

	/**
	 * Status
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug The report slug.
	 *
	 * @return void
	 */
	public static function status( $slug ) {
		$param  = sanitize_text_field( wp_unslash( $_GET['status'] ?? '' ) );
		$status = array(
			'active'       => __( 'Active', 'custom-reports' ),
			'pending'      => __( 'Pending', 'custom-reports' ),
			'cancelled'    => __( 'Cancelled', 'custom-reports' ),
			'expired'      => __( 'Expired', 'custom-reports' ),
			'group_member' => __( 'Group Member', 'custom-reports' ),
		);
		?>
		<select id="<?php echo esc_attr( $slug ); ?>-status" name="status" class="custom-reports__filters-status">
			<option value="">Select Status</option>
			<?php
			foreach ( $status as $key => $value ) {
				?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $param ); ?>><?php echo esc_html( $value ); ?></option>
				<?php
			}
			?>
		</select>
		<?php
	}

	/**
	 * Groups
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug The report slug.
	 *
	 * @return void
	 */
	public static function groups( $slug ) {
		$param  = sanitize_text_field( wp_unslash( $_GET['group'] ?? '' ) );
		$groups = rcpga_get_groups( array( 'number' => 999999 ) );

		?>
		<select id="<?php echo esc_attr( $slug ); ?>-group" name="group" class="custom-reports__filters-group">
			<option value="">Select Group</option>
			<?php
			foreach ( $groups as $group ) {
				?>
				<option value="<?php echo esc_attr( $group->get_group_ID() ); ?>" <?php selected( $group->get_group_ID(), $param ); ?>><?php echo esc_html( $group->get_name() ); ?></option>
				<?php
			}
			?>
		</select>
		<?php
	}

	/**
	 * Activity
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug The report slug.
	 *
	 * @return void
	 */
	public static function activity( $slug ) {
		$param    = sanitize_text_field( wp_unslash( $_GET['activity'] ?? '' ) );
		$activity = array(
			'activated_date'    => __( 'Actived Date', 'custom-reports' ),
			'renewed_date'      => __( 'Renewed Date', 'custom-reports' ),
			'cancellation_date' => __( 'Cancelled Date', 'custom-reports' ),
			'trial_end_date'    => __( 'Trial End Date', 'custom-reports' ),
			'registered_date'   => __( 'Registered Date', 'custom-reports' ),
		);
		?>
		<select id="<?php echo esc_attr( $slug ); ?>-activity" name="activity" class="custom-reports__filters-activity">
			<option value="">Select Dates</option>
			<?php
			foreach ( $activity as $key => $value ) {
				?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $param ); ?>><?php echo esc_html( $value ); ?></option>
				<?php
			}
			?>
		</select>
		<?php
	}

	/**
	 * Had Trial
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug The report slug.
	 *
	 * @return void
	 */
	public static function had_trial( $slug ) {
		$param = sanitize_text_field( wp_unslash( $_GET['has-trialed'] ?? '' ) );
		?>
		<select id="<?php echo esc_attr( $slug ); ?>-has-trialed" name="has-trialed" class="custom-reports__filters-has-trialed">
			<option value="">Trial</option>
			<option value="1" <?php selected( $param, 1 ); ?>>Had Tial</option>
		</select>
		<?php
	}

	/**
	 * Upgrated
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug The report slug.
	 *
	 * @return void
	 */
	public static function upgraded( $slug ) {
		$param = sanitize_text_field( wp_unslash( $_GET['upgraded-from'] ?? '' ) );
		?>
		<select id="<?php echo esc_attr( $slug ); ?>-upgraded-from" name="upgraded-from" class="custom-reports__filters-upgraded-from">
			<option value="">Upgraded</option>
			<option value="1" <?php selected( $param, 1 ); ?>>Has Upgraded</option>
		</select>
		<?php
	}
}
