<?php
/**
 * Template
 *
 * @package    Custom_Reports
 * @subpackage Custom_Reports/Includes/Classes/Enterprise
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace CustomReports\Members;

use CustomReports\ReportAbstract;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Template
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Template extends ReportAbstract {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct(
			'member_report',
			'',
			true
		);
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
		?>
		<div class="custom-reports__item">
			<h2><?php echo esc_html( $this->title ); ?></h2>
			<?php if ( ! empty( $this->description ) ) { ?>
				<p><?php echo wp_kses_post( $this->description ); ?></p>
			<?php } ?>
			<p><a href="<?php echo esc_url( admin_url( "admin.php?page={$this->slug}" ) ); ?>" class="button">View Report</a></p>
			<br/>
			<hr/>
		</div>
		<?php
	}

	/**
	 * Report
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function report() {
		// Bail if none check fails.
		if ( $this->nonce !== $_POST['nonce'] ) { // phpcs:ignore
			wp_die();
		}

		$data      = [];
		$favorites = Data::get_data();

		foreach ( $favorites as $favorite ) {
			$data[] = (object) $favorite;
		}

		echo wp_json_encode( $data, true );
		wp_die();
	}

	/**
	 * Sub Page
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function sub_page() {
		$this->table->prepare_items();
		?>
		<div class="wrap">
			<h2><?php echo esc_html( $this->title ); ?></h2>
			<?php $this->table->views(); ?>
			<form method="get">
				<input type="hidden" name="page" value="<?php echo esc_html( $_REQUEST['page'] ); // phpcs:ignore ?>" />
				<?php
				$this->table->search_box( __( 'Search', 'custom-reports' ), 'search_id' );
				$this->table->display();
				?>
			</form>
		</div>
		<?php
	}
}