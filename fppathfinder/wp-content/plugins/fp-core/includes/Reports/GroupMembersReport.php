<?php
/**
 * Group Members Report
 *
 * @package    FP_Core
 * @subpackage FP_Core/Reports
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Reports;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Group Members Report
 *
 * @author John Geesey|Jason Witt
 * @since  1.0.0
 */
class GroupMembersReport {
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
	 * Initialize the class
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'after_fp_core_plugins_loaded_init', array( $this, 'report_form' ) );
		add_action( 'init', array( $this, 'check_for_actions' ), 100 );
	}

	/**
	 * Check for Actions
	 *
	 * @author John Geesey
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function check_for_actions() {
		$actions = array(
			'group_report_form' => array( $this, 'group_report_form_listener' ),
		);

		foreach ( $actions as $action => $handler ) {
			if ( $this->verify_nonce( $action ) ) {
				call_user_func( $handler );
			}
		}
	}

	/**
	 * Group Report Listener
	 *
	 * @author John Geesey
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function group_report_form_listener() {
		$this->build_report();
	}

	/**
	 * Build Report
	 *
	 * @author John Geesey
	 * @since  1.0.0
	 *
	 * @return void
	 */
	private function build_report() {
		set_time_limit( 0 );

		try {
			$output = fopen( 'php://output', 'w' ) or die( "Can't open php://output" );

			header( 'Content-Type:application/csv' );
			header( 'Content-Disposition:attachment;filename=group-members.csv' );

			fputcsv(
				$output,
				array(
					'Name',
					'Email',
					'Role',
				)
			);

			foreach ( $this->get_group_members() as $group_member ) {
				$user = get_userdata( $group_member->get_user_id() );
				$line = array(
					$user->display_name,
					$user->user_email,
					$group_member->get_role(),
				);

				if ( ! $line ) {
					continue;
				}

				fputcsv( $output, $line );
			}

			fclose( $output );
			die();
		} catch ( \Exception $e ) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
			die();
		}
	}

	/**
	 * Get Group Members
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_group_members() {
		$group_id      = absint( $_GET['rcpga-group'] );
		$group         = rcpga_get_group( $group_id );
		$group_members = rcpga_get_group_members(
			array(
				'group_id' => absint( $group_id ),
				'orderby'  => 'role',
				'order'    => 'DESC',
				'number'   => 0,
			)
		);
		return $group_members;
	}

	/**
	 * Get Nonce Name
	 *
	 * @author John Geesey
	 * @since  1.0.0
	 *
	 * @return string
	 */
	private function get_nonce_name() {
		return 'fp_group_report';
	}

	/**
	 *  Output Nonce Field
	 *
	 * @author John Geesey
	 * @since  1.0.0
	 *
	 * @return void
	 */
	private function output_nonce_field( string $action ) {
		wp_nonce_field( $action, $this->get_nonce_name() );
	}

	/**
	 *  Verify Nonce
	 *
	 * @author John Geesey
	 * @since  1.0.0
	 *
	 * @return bool
	 */
	private function verify_nonce( string $action ) {
		return isset( $_REQUEST[ $this->get_nonce_name() ] ) && wp_verify_nonce( $_REQUEST[ $this->get_nonce_name() ], $action );
	}

	/**
	 *  Output Group Report Form Nonce
	 *
	 * @author John Geesey
	 * @since  1.0.0
	 *
	 * @return void
	 */
	private function output_group_report_form_nonce() {
		$this->output_nonce_field( 'group_report_form' );
	}

	/**
	 * Report Form
	 *
	 * @author John Geesey
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function report_form() {
		add_action( 'restrict_page_rcp-groups', array( $this, 'output_report_form' ), 1000 );
	}

	/**
	 * Output General Settings Page
	 *
	 * Display the settings page.
	 *
	 * @return void
	 */
	function output_report_form() {
		$group_id = sanitize_text_field( wp_unslash( absint( $_GET['rcpga-group'] ?? '' ) ) );

		if ( ! $group_id ) {
			return;
		}
		?>
		<div style="padding: 1rem;">
		<h3>Custom Reports:</h3>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" target="_blank">
				<?php $this->output_group_report_form_nonce(); ?>
				<input class="button button-primary" type="submit" value="Download Members CSV" />
			</form>
		</div>
		<?php
	}
}
