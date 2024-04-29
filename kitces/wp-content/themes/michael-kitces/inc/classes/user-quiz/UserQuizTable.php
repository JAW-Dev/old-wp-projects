<?php
/**
 * User Quiz Table
 *
 * @package    Package_Name
 * @subpackage Package_Name/Subpackage_Name
 * @author     Author_Name
 * @copyright  Copyright (c) Date, Author_Name
 * @license    GPL-2.0
 * @version    1.0.0
 * @since      1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( 'UserQuizTable' ) ) {

	/**
	 * User Quiz Table
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class UserQuizTable {

		/**
		 * Filter
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var string
		 */
		protected $filter;

		/**
		 * Initialize the class
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function __construct() {
			$this->filter = isset( $_GET['filter'] ) ? sanitize_text_field( wp_unslash( $_GET['filter'] ) ) : 'all';
		}

		/**
		 * Output Table
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function output_table() {
			$data = UserQuizData::get_quiz_data();

			foreach ( $data as $item ) {
				if ( $item['has-entries'] ) {
					$has_entries = true;
					break;
				}
			}

			if ( $has_entries ) {
				echo wp_kses_post( $this->years_dropdown() );
			}
			?>
			<p class="">For questions related to quizzes and CE contact our CE Manager at <a href="mailto:cequiz@kitces.com">cequiz@kitces.com</a></p>

			<table class="kitces-quiz-list taken-quiz-list">
				<thead>
					<tr>
						<td>Quiz Name</td>
						<td style='text-align: center;'>Date Taken</td>
						<td style='text-align: center;'>Time *</td>
						<td style='text-align: center;'>CFP&reg; & IWI</td>
						<td style='text-align: center;'>CPA</td>
						<td style='text-align: center;'>IAR (EP&R)</td>
						<td style='text-align: center;'>IAR (P&P)</td>
						<td style='text-align: center;'>Score</td>
						<td style='text-align: center;'>Certificate</td>
					</tr>
				</thead>
			<?php if ( $has_entries ) { ?>
				<tbody>
					<?php
					foreach ( $data as $item ) {
						if ( $this->filter === $item['year'] ) {
							$this->get_table_rows( $item );
						} elseif ( $this->filter === 'all' ) {
							$this->get_table_rows( $item );
						}
					}
					?>
				</tbody>
			<?php } ?>
			</table>
			<?php if ( $has_entries ) { ?>
				<p><em>* The time is set to Eastern Standard Time.</em></p>
				<?php
			} else {
				$quiz_page = get_page_by_title( 'Nerd\'s Eye View Blog CE Quizzes' );
				$url       = get_the_permalink( $quiz_page->ID );
				?>
					<p>You have not taken a quiz yet. <a href="<?php echo esc_url( $url ); ?>">Click here to take your first one!</a></p>
				<?php
			}
			?>
			<p class="">For questions related to quizzes and CE contact our CE Manager at <a href="mailto:cequiz@kitces.com">cequiz@kitces.com</a></p>
			<?php
		}

		/**
		 * Get Table Rows
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array $item The quiz data array.
		 *
		 * @return void
		 */
		private function get_table_rows( $item ) {
			$date = kitces_timezone( $item['date'] );
			?>
			<tr>
				<td>
					<?php
					// NOTE: Had to wrap the div in the a tag for line truncation.
					if ( $this->get_quiz_url( $item['form_id'] ) ) {
						$url = $this->get_quiz_url( $item['form_id'] );
						?>
						<a href="<?php echo esc_url( $url ); ?>">
							<div class="taken-quiz-list__title">
								<?php echo esc_html( $item['title'] ); ?>
							</div>
						</a>
						<?php
					} else {
						?>
						<div class="taken-quiz-list__title">
							<?php echo esc_html( $item['title'] ); ?>
						</div>
						<?php
					}
					?>
				</td>
				<td style='text-align: center;'><?php echo esc_html( $date ); ?></td>
				<td style='text-align: center;'><?php echo esc_html( $item['time'] ); ?></td>
				<td style='text-align: center;'><?php echo esc_html( $item['cfp_hours'] ); ?></td>
				<td style='text-align: center;'><?php echo esc_html( $item['nasba_hours'] ); ?></td>
				<td style='text-align: center;'><?php echo esc_html( $item['iar_epr'] ); ?></td>
				<td style='text-align: center;'><?php echo esc_html( $item['iar_pp'] ); ?></td>
				<td style='text-align: center;'><?php echo wp_kses_post( $this->get_score( $item['passPercent'], $item['passed'] ) ); ?></td>
				<td style='text-align: center;'><?php echo wp_kses_post( $this->get_certificate( $item['passed'], $item['form_id'] ) ); ?></td>
			</tr>
			<?php
		}

		/**
		 * Get Quiz URL
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param int $form_id The ID of the form.
		 *
		 * @return string
		 */
		public function get_quiz_url( $form_id ) {
			global $CGD_CECredits; // phpcs:ignore
			$parent1  = get_page_by_title( 'Nerd\'s Eye View Blog CE Quizzes' );
			$parent2  = get_page_by_title( 'CE Quizzes' );
			$pid1     = $parent1->ID;
			$pid2     = $parent2->ID;
			$args     = array(
				'numberposts'     => -1,
				'orderby'         => 'date',
				'order'           => 'DESC',
				'post_type'       => 'page',
				'post_status'     => array( 'publish', 'draft' ),
				'post_parent__in' => kitces_get_quiz_parent_pages(),
			);
			$subpages = get_posts( $args );

			foreach ( $subpages as $subpage ) {
				if ( ! kitces_is_quiz_page( $subpage ) ) {
					continue;
				}

				$matches = false;

				preg_match( '@id="(\d+)"@', $subpage->post_content, $matches );

				if ( ! empty( $matches[1] ) && $form_id === $matches[1] ) {
					return get_the_permalink( $subpage->ID );
				}
			}
			return '';
		}

		/**
		 * Get Quiz Pages
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function get_quiz_pages() {
			$args = array(
				'numberposts'     => -1,
				'orderby'         => 'date',
				'order'           => 'DESC',
				'post_type'       => 'page',
				'post_status'     => array( 'publish', 'draft' ),
				'post_parent__in' => kitces_get_quiz_parent_pages(),
			);

			$pages = get_posts( $args );

			foreach ( $pages as $key => $value ) {
				if ( ! kitces_is_quiz_page( $value ) ) {
					unset( $pages[ $key ] );
				}
			}

			return $pages;
		}

		/**
		 * Get Parent Quiz Pages
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function get_parent_quiz_pages() {
			$pages   = $this->get_quiz_pages();
			$parents = array();

			foreach ( $pages as $key => $value ) {
				$parents[] = $key;
			}

			return $parents;
		}

		/**
		 * Years Dropdown
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function years_dropdown() {
			$years = array_unique( $this->get_quiz_years() );
			?>
			<p>
				<select id="quiz-year-filter" class="quiz-year-filter">
					<option value="all" <?php selected( $this->filter, 'all' ); ?>>All</option>
					<?php
					foreach ( $years as $year ) {
						?>
						<option value="<?php echo esc_attr( $year ); ?>" <?php selected( $this->filter, $year ); ?>><?php echo esc_html( $year ); ?></option>
						<?php
					}
					?>
				</select>
			</p>
			<?php
		}

		/**
		 * Get Quiz Years
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		private function get_quiz_years() {
			$data  = UserQuizData::get_quiz_data();
			$years = array();

			foreach ( $data as $entry ) {
				$years[] = $entry['year'];
			}

			return $years;
		}

		/**
		 * Get Score
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param string  $score  The quize score.
		 * @param boolean $passed If the quiz was passed.
		 *
		 * @return string
		 */
		private function get_score( $score, $passed ) {
			$html = '';

			if ( $passed ) {
				$html .= $score . '%';
			} else {
				$html .= '<span class="did-not-pass">' . $score . '%' . '</span>';
			}

			return $html;
		}

		/**
		 * Get Certificate
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param boolean $passed  If the quiz was passed.
		 * @param int     $form_id ID of the form.
		 *
		 * @return string
		 */
		private function get_certificate( $passed, $form_id ) {
			$html = '';

			if ( $passed ) {
				$html .= '<a href="' . admin_url( 'admin-ajax.php?action=kitces_cpf_cert&form_id=' . $form_id ) . '" target="_blank">View/Print</a>';
			} else {
				$html = 'Did not pass';
			}

			return $html;
		}
	}
}
