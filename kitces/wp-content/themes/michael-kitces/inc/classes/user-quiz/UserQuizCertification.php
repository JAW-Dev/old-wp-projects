<?php
/**
 * User Quiz Certification
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

if ( ! class_exists( 'UserQuizCertification' ) ) {

	/**
	 * User Quiz Certification
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class UserQuizCertification {

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
		 * Hooks
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function hooks() {
			add_action( 'wp_ajax_kitces_cpf_cert', array( $this, 'kitces_cpf_cert' ) );
			add_action( 'wp_ajax_nopriv_kitces_cpf_cert', array( $this, 'kitces_cpf_cert' ) );
		}

		/**
		 * Output Table
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function kitces_cpf_cert() {
			?>
			<!DOCTYPE html>
			<html lang="en-US" prefix="og: https://ogp.me/ns#">
				<head>
					<meta charset="UTF-8" />
					<meta name="viewport" content="width=device-width, initial-scale=1" />
					<meta name="referrer" content="always"><title>Quiz Certificate</title>
					<link rel="icon" href="<?php echo esc_url( get_home_url() ); ?>/wp-content/themes/michael-kitces/lib/images/favicon.ico" />
					<style>
						.button-wrap {
							text-align: center;
							margin-bottom:28px;
						}
						.print-button {
							display: inline-block;
							background: #82a502;
							color: #fff;
							font-size: .875rem;
							font-weight: 700;
							text-transform: uppercase;
							padding: .5rem .9375rem;
							line-height: 1;
							letter-spacing: 1px;
							text-decoration: none;
							transition: all .1s ease-in-out;
							box-sizing: border-box;
							margin: 0 auto;
						}
						@media print {
							.print-button,
							.button-wrap {
								display: none;
							}
						}
					</style>
				</head>
				<body class="quiz-certificate" style="background-color: white; box-shadow: none;">
					<div style="margin: 0 auto; width: 600px;">
						<?php echo wp_kses_post( wpautop( $this->get_certificate_template() ) ); ?>
						<p class="button-wrap"><a href="#" class="print-button" onclick="window.print();return false;">Print The Certificate</a></p>
					</div>
				</body>
			</html>
			<?php
			exit;
		}

		/**
		 * Get Certificate Template
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return string
		 */
		public function get_certificate_template() {
			$option = get_option( 'cecredits_settings' );
			return $this->tag_replace( stripslashes( $option['passNotificationEmailTemplate'] ) );
		}

		/**
		 * Tag Replace
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param string $string The string to do the replacements on.
		 *
		 * @return string
		 */
		public function tag_replace( $string ) {
			$current_form = isset( $_GET['form_id'] ) ? sanitize_text_field( wp_unslash( $_GET['form_id'] ) ) : 0;
			$data         = UserQuizData::get_quiz_data();
			$current_user = wp_get_current_user();
			$display_name = $current_user->display_name;
			$tags         = array();



			if ( $current_form ) {

				foreach ( $data as $entry ) {
					if ( $current_form === $entry['form_id'] ) {
						$tags = array(
							'{Display Name:5}'     => $display_name,
							'{entry:date_created}' => kitces_timezone( $entry['date'] ),
							'{form_title}'         => $entry['title'],
							'{cfp_program_id}'     => ! empty( $entry['cfp_program_id'] ) ? $entry['cfp_program_id'] : 'n/a',
							'{imca_program_id}'    => ! empty( $entry['imca_program_id'] ) ? $entry['imca_program_id'] : 'n/a',
							'{ea_program_id}'      => ! empty( $entry['ea_program_id'] ) ? $entry['ea_program_id'] : 'n/a',
							'{hours}'              => ! empty( $entry['cfp_hours'] ) ? $entry['cfp_hours'] : 'n/a',
							'{iar_program_id}'     => ! empty( $entry['iar_program_id'] ) ? $entry['iar_program_id'] : 'n/a',
							'{ea_hours}'           => ! empty( $entry['ea_hours'] ) ? $entry['ea_hours'] : 'n/a',
							'{nasba_hours}'        => ! empty( $entry['nasba_hours'] ) ? $entry['nasba_hours'] : 'n/a',
							'{iar_epr}'            => ! empty( $entry['iar_epr'] ) ? $entry['iar_epr'] : 'n/a',
							'{iar_pp}'             => ! empty( $entry['iar_pp'] ) ? $entry['iar_pp'] : 'n/a',
     						'{quiz_percent}'       => ! empty( $entry['passPercent'] ) ? $entry['passPercent'] : 'n/a',
							'{entry:time_created}' => strtolower( str_replace( ' ', '', $entry['time'] ) ) . ' Eastern Time Zone',
						);
					}
				}

				if ( ! empty( $tags ) ) {
					return strtr( $string, $tags );
				}
			}

			return '';
		}
	}
}
