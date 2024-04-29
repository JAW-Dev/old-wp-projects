<?php
/**
 * Send
 *
 * @package    FpCore/
 * @subpackage FpCore/Actions
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Actions;

use FP_Core\Crms\Utilities as CRMUtilities;
use FP_Core\InteractiveChecklistsNotification;
use FP_Core\Crms\NoteCreator;
use FP_Core\Crms\Notes\Checklist;
use FpAccountSettings\Includes\Utilites\Media\Image;
use Mpdf\Mpdf;
use FP_PDF_Generator\PDF;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Send
 *
 * @author Jason Witt
 * @since  1.0.0
 */
abstract class SendAbstract extends PDF {
	/**
	 * WPDB
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $db;

	/**
	 * is_email_button_submission
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $is_email_button_submission;

	/**
	 * is_email_button_no_crm_submission
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $is_email_button_no_crm_submission;

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		global $wpdb;

		$this->db = $wpdb;

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
		add_action( 'before_single_interactive_resource_view', array( $this, 'listen_for_submit_button' ) );
	}

	/**
	 * List for email note submit button
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function listen_for_submit_button() {
		if ( empty( $_POST ) ) {
			return;
		}

		$this->is_email_button_submission        = $_POST['email_note_button'] ?? '';
		$this->is_email_button_no_crm_submission = $_POST['email_no_crm_note_button'] ?? '';

		$this->submit_handler();
	}

	/**
	 * Submit Handler
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function submit_handler() {
	}

	/**
	 * Maybe Send Email
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $to          Who to send the email to.
	 * @param string $subject     The email subject.
	 * @param string $body        The email body.
	 * @param array  $headers     The email headers.
	 * @param object $advisor     The advisor info.
	 * @param bool   $message     Add notification.
	 *
	 * @return void
	 */
	public function maybe_send_email( $to, $subject, $body, $headers, $advisor, $attachment = '' ) {
		if ( strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false ) {
			add_filter( 'sent_mail_get_setting', array( $this, 'maybe_disable_wpsm' ), 10, 2 );
		}

		if ( ! empty( $attachment ) ) {
			$note_attachment = $this->generate_notes_pdf( $advisor, $attachment );
			$sent_email      = wp_mail( $to, $subject, $body, $headers, $note_attachment );
		} else {
			$sent_email = wp_mail( $to, $subject, $body, $headers );
		}

		if ( defined( 'WPSM_AIRPLANE_MODE' ) && ! $sent_email ) {
			$sent_email = true;
		}

		if ( strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false ) {
			remove_filter( 'sent_mail_get_setting', array( $this, 'maybe_disable_wpsm' ), 10, 2 );
		}

		if ( $sent_email ) {
			return true;
		} else {
			InteractiveChecklistsNotification::add( 'There was a problem sending this resource to your advisor!', true );
		}

		return $sent_email;
	}

	/**
	 * Generate Notes PDF
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param \WP_User $advisor    The advisor user object.
	 * @param string   $attachment The attachment.
	 *
	 * @return void
	 */
	public function generate_notes_pdf( $advisor, $attachment ) {
		$user_settings = fp_get_user_settings( $advisor->ID );
		$pdf_settings  = fp_get_pdf_settings( $user_settings );
		$temp_dir      = random_int( 1, 999999999 );
		$dir_path      = $this->create_directory( $temp_dir );
		$filename      = 'Checklist.pdf';
		$logo          = ! empty( $pdf_settings['logo'] ) ? $pdf_settings['logo'] : $this->get_default_logo();

		$this->set_logo( $logo );

		$this->mpdf = new Mpdf( $this->get_mpdf_options() );

		$this->write_back_page( $user_settings, $attachment );
		$this->mpdf->Output( $dir_path . $filename, 'F' );

		return $dir_path . $filename;
	}

	public function get_back_page_html( $user_settings = [], $attachment = '' ) {
		$settings               = fp_get_pdf_settings( $user_settings );
		$chevron_image          = $this->get_chevron_image();
		$title_background_color = $this->get_colors()[0];

		$settings_advisor_name           = ! empty( $settings['advisor_name'] ) ? $settings['advisor_name'] : '';
		$settings_job_title              = ! empty( $settings['job_title'] ) ? $settings['job_title'] : '';
		$settings_email                  = ! empty( $settings['email'] ) ? $settings['email'] : '';
		$settings_address                = ! empty( $settings['address'] ) ? $settings['address'] : '';
		$settings_phone                  = ! empty( $settings['phone'] ) ? $settings['phone'] : '';
		$settings_website                = ! empty( $settings['website'] ) ? $settings['website'] : '';
		$settings_second_page_body_title = ! empty( $settings['second_page_body_title'] ) ? $settings['second_page_body_title'] : '';
		$settings_second_page_title      = ! empty( $settings['second_page_title'] ) ? $settings['second_page_title'] : 'Test';
		$settings_second_page_body_copy  = ! empty( $settings['second_page_body_copy'] ) ? $settings['second_page_body_copy'] : '';
		$settings_use_advanced           = ! empty( $settings['use_advanced'] ) ? $settings['use_advanced'] : false;
		$settings_advanced_body          = ! empty( $settings['advanced_body'] ) ? stripslashes( $settings['advanced_body'] ) : '';

		$adviser_detail_line_1 = join( ', ', array_filter( array( $settings_advisor_name, $settings_job_title ) ) );
		$adviser_detail_line_3 = join( ' | ', array_filter( array( $settings_email, $settings_phone, $settings_website ) ) );
		$no_advisor_name       = fp_use_advisor_name();

		if ( $no_advisor_name ) {
			$adviser_detail_line_1 = $settings_advisor_name;
		}

		$chevron_div                    = "<div style='text-align:center; margin:0 auto 48px; width:10px; height:7px;'><img style='width: 11px; height: 6px;' src='{$chevron_image}'></div>";
		$second_page_body_title_section = empty( $settings_second_page_body_title ) ? '' : "<div style='text-align:center; background:none; margin-bottom:48px; font-family:opensans-bold;'>{$settings_second_page_body_title}</div>{$chevron_div}";

		ob_start();
		?>
		<div style="position: relative">
			<div style=" width:77%; background:#ffffff; position:relative;">
				<div style="text-align: center; background: <?php echo $title_background_color; ?>; color: rgb(255, 255, 255); font-size: 1.5em; padding: 28px 0 28px">
					<div style='color: #ffffff; font-family:opensans-regular;'><?php echo get_the_title(); ?></div>
				</div>
			</div>
			<?php
			if ( $settings_use_advanced === 'true' && empty( $attachment ) ) {
				?>
				<div style="background:#f3f3f4; width:100%; float:none; padding-left: 32px; padding-right: 32px;">
					<?php
					if ( ! empty( $settings_advanced_body ) && ! empty( $second_page_body_title_section ) ) {
						?>
						<div style="padding-top: 80px"><?php echo $second_page_body_title_section; ?></div>
						<?php
					}
					?>
					<div style="text-align:left;">
						<pre style="color: #000; font-family:opensans-regular;"><?php echo$settings_advanced_body; ?></pre>
					</div>
				</div>
				<?php
			} else {
				if ( ! empty( $attachment ) ) {
					?>
					<div style="width:100%; float:none; position: absolute; top: 100px;">
						<?php echo $attachment; ?>
					</div>
					<?php
				} else {
					?>
					<div style="background:#f3f3f4; padding:80px; width:100%; float:none; position: absolute; top: 100px;">
						<div><?php echo $second_page_body_title_section; ?></div>
						<div style="text-align:left; color: #000; font-family:opensans-regular;">
							<?php echo $settings_second_page_body_copy; ?>
						</div>
						<div style="margin-top:80px; text-align:center;">
							<div style="font-family:opensans-bold;"><?php echo $adviser_detail_line_1; ?></div>
							<div style="font-family:museosansrounded-100;"><?php echo $settings_address ; ?></div>
							<div style="font-family:museosansrounded-100;"><?php echo $adviser_detail_line_3; ?></div>
						</div>
					</div>
					<?php
				}
			}
			?>
		</div>
		<?php

		$content = ob_get_clean();

		return $content;
	}

	/**
	 * Get Temp Dir Parent
	 *
	 * Get the parent directory for the temporary directory to be created in.
	 *
	 * @return string path to the parent directory
	 */
	private function get_temp_dir_parent( $temp_dir ) {
		return wp_get_upload_dir()['basedir'] . '/pdf-notes-temporary/' . $temp_dir;
	}

	/**
	 * Generate Temp Dir
	 *
	 * Create and return the temporary directory for this bundle to use.
	 *
	 * @return string path to new directory
	 */
	public function create_directory( $temp_dir ) {
		$path = $this->get_temp_dir_parent( $temp_dir ) . '/';
		$dir  = wp_mkdir_p( $path );

		if ( $dir ) {
			return $path;
		} else {
			throw new \Exception( "Couldn't generate temporary directory for pdf bundle" );
		}
	}

	/**
	 * Send Note
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $entry      The share link database entry.
	 * @param int    $advisor_id The advisor ID.
	 * @param int    $client_id  The Client ID
	 * @param string $client_name  The client name.
	 * @param string $to         Who to send the email to.
	 * @param array  $hheaders   The email headers.
	 *
	 * @return void
	 */
	public function send_note( $entry, $advisor_id, $client_id, $client_name, $to, $headers, $account_id ) {
		$note = NoteCreator::build_note();
		$crm  = CRMUtilities::get_active_crm( $advisor_id );

		$response = NoteCreator::create_note( $crm, $advisor_id, $client_id, $note, $account_id );

		return $this->maybe_sent_resource_error( $response, $client_name, $to, $headers, $crm );
	}

	/**
	 * Create Note
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug       The CRM slug.
	 * @param int    $user_id    The user ID.
	 * @param int    $contact_id The contact ID.
	 * @param string $note       The note.
	 *
	 * @return void
	 */
	public function create_note( $slug, $advisor_id, $client_id, $note ) {
		$crm       = ucfirst( str_replace( '_', '', $slug ) );
		$classname = 'FP_Core\\Crms\\Apis\\' . $crm . 'API';

		return $classname::create_note( $advisor_id, $client_id, $note );
	}

	/**
	 * Send Tasks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function send_tasks( $advisor_id, $client_id, $client_name, $to, $headers ) {
		$tasks = Checklist::build_tasks();
		$crm   = CRMUtilities::get_active_crm( $advisor_id );

		if ( ! empty( $tasks ) ) {
			foreach ( $tasks as $task ) {
				if ( empty( $task ) ) {
					continue;
				}

				$response = NoteCreator::create_task( $crm, $advisor_id, $client_id, $task );

				$sent_error = $this->maybe_sent_resource_error( $response, $client_name, $to, $headers, $crm );

				if ( ! $sent_error ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Maybe Disable WPSM
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_disable_wpsm( $value, $setting ) {
		if ( 'enable' === $setting ) {
			return 'no';
		}

		return $value;
	}

	/**
	 * Maybe Sent Resource Error
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array  $response    The API response.
	 * @param string $client_name The client name.
	 * @param string $to          Who to send the email to.
	 * @param string $subject     The email subject.
	 * @param string $body        The email body.
	 * @param array  $headers     The email headers.
	 * @param string $crm         The CRM.
	 *
	 * @return void
	 */
	public function maybe_sent_resource_error( $response, $client_name, $to, $headers, $crm ) {
		$subject       = 'Client fpPathfinder Resource';
		$body          = $client_name . ' attempted to send you the resource ' . get_the_title() . ', but there was a problem.';
		$error_message = 'There was a problem! We Could not send this resource to your advisor.';

		if ( is_wp_error( $response ) ) {
			InteractiveChecklistsNotification::add( $error_message, true );

			if ( strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false ) {
				add_filter( 'sent_mail_get_setting', array( $this, 'maybe_disable_wpsm' ), 10, 2 );
			}

			$body = $body . ' There was an internal server error at fpPathfinder. We recommend trying again, if this error persists please contact <a href="mailto:support@fpPathfinder.com">fpPathfinder support</a>';

			$sent_email = wp_mail( $to, $subject, $body, $headers );

			if ( strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false ) {
				remove_filter( 'sent_mail_get_setting', array( $this, 'maybe_disable_wpsm' ), 10, 2 );
			}

			return false;
		}

		if ( empty( $response ) ) {
			InteractiveChecklistsNotification::add( $error_message, true );

			$body = $body . ' We did not receive a reponse from ' . $crm . '. We recommend trying again, if this error persists please contact <a href="mailto:support@fpPathfinder.com">fpPathfinder support</a>';

			if ( strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false ) {
				add_filter( 'sent_mail_get_setting', array( $this, 'maybe_disable_wpsm' ), 10, 2 );
			}

			$sent_email = wp_mail( $to, $subject, $body, $headers );

			if ( strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false ) {
				remove_filter( 'sent_mail_get_setting', array( $this, 'maybe_disable_wpsm' ), 10, 2 );
			}

			return false;
		}

		$codes = [
			200,
			201,
			204,
		];

		$response_code = ! empty( $response['response']['code'] ) ? $response['response']['code'] : 0;

		if ( ! in_array( (int) $response_code, $codes, true ) ) {
			InteractiveChecklistsNotification::add( $error_message, true );

			$body = $body . ' We did not received a bad reponse code from ' . $crm . '. We recommend trying again, if this error persists please contact <a href="mailto:support@fpPathfinder.com">fpPathfinder support</a>';

			if ( strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false ) {
				add_filter( 'sent_mail_get_setting', array( $this, 'maybe_disable_wpsm' ), 10, 2 );
			}

			$sent_email = wp_mail( $to, $subject, $body, $headers );

			if ( strpos( $_SERVER['HTTP_HOST'], 'fppathfinder.com' ) !== false ) {
				remove_filter( 'sent_mail_get_setting', array( $this, 'maybe_disable_wpsm' ), 10, 2 );
			}

			return false;
		}

		return true;
	}

	/**
	 * Build HTML Note
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function build_html_note( $client_name ) {
		ob_start();
		?>
		<div class="checklist-summary">
			<p>Name: <?php echo $client_name; ?></p>
			<p>Completed Date: <?php echo substr( current_time( 'mysql' ), 0, 10 ); ?> </p>
			<p></p>
			<?php echo $this->note_inner(); ?>
		</div>
		<?php

		$content = ob_get_clean();

		return $content;
	}

	/**
	 * Note Inner
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function note_inner() {
		$question_groups   = get_field( 'question_groups' );
		$group_id          = 0;
		$question_id       = 0;
		$entry             = fp_get_share_link_db_entry();
		$advisor_settings  = ! empty( $entry ) ? json_decode( $entry['fields'] ) : [];
		$show_more_details = ! empty( $advisor_settings ) ? $advisor_settings->show_more_details : 0;

		$questions_groups_array = Checklist::build_questions_array( $question_groups, $_POST );
		$yes_answers            = $questions_groups_array['yes'];
		$not_answers            = $questions_groups_array['not'];

		if ( ! empty( $yes_answers ) ) {
			$lines[] = '<h3>Possible Planning Issues Identified</h3><div class="question-group">';

			foreach ( $yes_answers as $yes_answer ) {
				if ( empty( $yes_answer ) ) {
					continue;
				}

				$question_id = 0;
				$heading     = $yes_answer['heading'] ?? '';
				$lines[]     = '<h4>' . $heading . '</h4>';

				if ( empty( $yes_answer['questions'] ) ) {
					continue;
				}

				foreach ( $yes_answer['questions'] as $question_array ) {
					$lines[] = $this->get_line( $question_array, $show_more_details );

					$question_id++;
				}

				$group_id++;
			}

			$lines[] = '</div>';

		}

		if ( ! empty( $not_answers ) ) {
			$lines[] = '<h3>Review the Answers</h3><div class="question-group">';

			foreach ( $not_answers as $not_answer ) {
				if ( empty( $not_answer ) ) {
					continue;
				}

				$question_id       = 0;
				$heading           = $not_answer['heading'] ?? '';
				$show_more_details = $not_answer['show_more_details'] ?? '';
				$lines[]           = '<h4>' . $heading . '</h4>';

				if ( empty( $not_answer['questions'] ) ) {
					continue;
				}

				foreach ( $not_answer['questions'] as $question_array ) {
					$lines[] = $this->get_line( $question_array, $show_more_details );

					$question_id++;
				}

				$group_id++;
			}
		}

		return implode( '', $lines );
	}

	/**
	 * Get Line
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $question_array    The questions array.
	 * @param bool  $show_more_details If to sdhow the tooltip.
	 *
	 * @return void
	 */
	public function get_line( array $question_array, bool $show_more_details ) {
		$value         = $question_array['value'];
		$note          = $question_array['note'];
		$line          = '<div class="question-outer"><p>' . $question_array['question'] . '</p>';
		$hide          = $question_array['hide'];
		$value_summary = $hide ? '' : Checklist::get_checkbox_value( $value );
		$notes_line    = '';
		$line         .= ' <p>' . $value_summary . '</p>';

		if ( ! $hide ) {

			if ( $show_more_details && ! empty( $question_array['tooltip'] ) ) {
				$line .= '<p> ! ' . $question_array['tooltip'] . '</p>';
			}

			if ( ! $note ) {
				$notes_line .= '<p> No Notes.<p>';
			} else {
				$notes_line .= '<p> Note: ' . $note . '</p>';
			}
		} else {
			$notes_line .= '<p> Question was not presented.</p>';
		}

		$line .= $notes_line . '</div>';

		return $line;
	}
}
