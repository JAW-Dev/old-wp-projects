<?php
/**
 * Template
 *
 * @package    Custom_Reports
 * @subpackage Custom_Reports/Includes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace CustomReports\QuizTimeRaw;

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
			'quiz_time_raw_report',
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
			<form method="get" action="/">
				<button id="<?php echo esc_attr( $this->id ); ?>" class="button" data-nonce="<?php echo esc_attr( $this->name ); ?>">Download <?php echo esc_html( $this->title ); ?> CSV</button>
			</form>
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

		$data    = [];
		$entries = Data::get_data();

		foreach ( $entries as $entry ) {
			$data[] = (object) $entry;
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
			<form method="get">
				<input type="hidden" name="page" value="<?php echo esc_html( $_REQUEST['page'] ); // phpcs:ignore ?>" />
				<?php
				$this->table->display();
				?>
			</form>
		</div>
		<?php
	}
}
