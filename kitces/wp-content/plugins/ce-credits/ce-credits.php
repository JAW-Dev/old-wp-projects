<?php
/*
Plugin Name: CE Credits
Plugin URI: http://cgd.io
Description:  Updates CE credits when quizzes are taken. Etc.
Version: 1.0.1
Author: CGD Inc.
Author URI: http://cgd.io

------------------------------------------------------------------------
Copyright 2009-2015 Clif Griffin Development Inc.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

// Composer Autoload
require_once 'vendor/autoload.php';

class CGD_CECredits extends WordPress_SimpleSettings {
	var $ac_api;
	var $max_attempts = 2;
	var $prefix       = 'cecredits';
	var $current_form = false;
	var $ac_api_url   = 'https://kitces.api-us1.com';
	var $ac_api_key   = 'befc79f7a64aad8af849ad6f8d417c99d00b879807377d39ab36d98f45e05738161a83c3';

	private $_correct_indicator_url;
	private $_incorrect_indicator_url;

	public function __construct() {
		parent::__construct();

		$this->ac_api = new ActiveCampaign( $this->ac_api_url, $this->ac_api_key );

		// Needed for Confirmation Message Overrides
		add_filter( 'gform_get_form_filter', array( $this, 'set_form_context' ), 1, 2 );
		add_action( 'gform_pre_submission', array( $this, 'detect_quiz_submission' ), 10, 1 );
		add_filter( 'gform_get_form_filter', array( $this, 'verify_date_access' ), 10, 2 );
		add_action( 'gform_after_submission', array( $this, 'after_quiz_submit' ), 10, 2 );

		// Enforce attempt limit
		add_filter( 'gform_get_form_filter', array( $this, 'enforce_max_attempts' ), 10, 2 );
		add_filter( 'gform_get_form_filter', array( $this, 'enforce_already_passed' ), 10, 2 );

		// Add User Fields
		add_action( 'show_user_profile', array( $this, 'add_quiz_fields' ) );
		add_action( 'edit_user_profile', array( $this, 'add_quiz_fields' ) );

		// Save User Fields
		add_action( 'personal_options_update', array( $this, 'save_quiz_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_quiz_fields' ) );

		// Admin menus
		add_filter( 'gform_addon_navigation', array( $this, 'add_menu_item' ), 1000, 1 );

		// Process download request.
		add_action( 'get_header', array( $this, 'download_report' ) );

		// Filter Gravity Form Confirmation Message Values
		add_filter( 'gf_module_gravityformsquiz_settings', array( $this, 'modify_confirmation_messages' ), 10, 1 );

		// Settings
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// Shortcodes
		add_shortcode( 'remaining_attempts', array( $this, 'remaining_attempts' ) );
		add_shortcode( 'max_attempts', array( $this, 'max_attempts' ) );

		add_filter( 'gform_confirmation', array( $this, 'quiz_confirmation' ), 100, 4 );

		// Add Quiz Date Field
		add_action( 'cmb2_admin_init', array( $this, 'quiz_metabox' ) );

		// Pass Notification Email Override
		add_action( "{$this->prefix}_settings_saved", array( $this, 'override_pass_notification_email' ) );

		// Custom Gravity Form Settings
		add_filter( 'gform_form_settings', array( $this, 'custom_gf_form_settings' ), 10, 2 );
		add_filter( 'gform_pre_form_settings_save', array( $this, 'save_custom_gf_form_settings' ) );
		add_filter( 'gform_replace_merge_tags', array( $this, 'filter_merge_tags' ), 10, 7 );

		// Add Before Form HTML
		add_filter( 'gform_get_form_filter', array( $this, 'add_before_quiz' ), 10, 2 );

		// Shortcodes for Course Catalog
		add_shortcode( 'cfp_hours', array( $this, 'cfp_hours_shortcode' ) );
		add_shortcode( 'nasba_hours', array( $this, 'nasba_hours_shortcode' ) );
		add_shortcode( 'iwi_ethics', array( $this, 'iwi_ethics_hours_shortcode' ) );
		add_shortcode( 'iwi_tr', array( $this, 'iwi_tr_hours_shortcode' ) );
		add_shortcode( 'iwi_gfp', array( $this, 'iwi_gfp_hours_shortcode' ) );
		add_shortcode( 'ea_hours', array( $this, 'ea_hours_shortcode' ) );
		add_shortcode( 'ea_program_id', array( $this, 'ea_program_id_shortcode' ) );

		add_shortcode( 'return_link', array( $this, 'return_link' ) );
	}

	function return_link( $atts, $content = null ) {
		$attributes = shortcode_atts(
			array(
				'class' => 'green-button retake-quiz-link',
			),
			$atts
		);

		$protocol    = is_ssl() ? 'https://' : 'http://';
		$current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		return '<a href="' . esc_url( $current_url ) . '" class="' . esc_attr( $attributes['class'] ) . '">' . $content . '</a>';
	}

	function add_menu_item( $menu_items ) {
		$menu_items[] = array(
			'name'       => 'ce_report',
			'label'      => 'CE Report',
			'callback'   => array( $this, 'show_admin' ),
			'permission' => 'edit_posts',
		);

		$menu_items[] = array(
			'name'       => 'quiz_settings',
			'label'      => 'Quiz Settings',
			'callback'   => array( $this, 'quiz_settings_page' ),
			'permission' => 'manage_options',
		);

		return $menu_items;
	}

	function show_admin() {
		ob_start();

		include 'inc/ce-credits-admin.php';

		echo ob_get_clean();
	}

	function download_report() {
		$start_date      = isset( $_GET['start_date'] ) ? wp_unslash( sanitize_text_field( $_GET['start_date'] ) ) : ''; // phpcs:ignore
		$end_date        = isset( $_GET['end_date'] ) ? wp_unslash( sanitize_text_field( $_GET['end_date'] ) ) : ''; // phpcs:ignore
		$download_report = isset( $_GET['download_report'] ) ? wp_unslash( sanitize_text_field( $_GET['download_report'] ) ) : ''; // phpcs:ignore

		if ( ! isset( $download_report ) || 'true' !== $download_report ) {
			return;
		}

		if ( ! isset( $start_date ) || ! $end_date ) {
			return;
		}

		$results = $this->get_ce_results( $start_date, $end_date );

		if ( empty( $results ) ) {
			die;
		}

		$output   = array();
		$output[] = array(
			'CE Sponsor Program ID Number',
			'Program Name',
			'Program ID',
			'IMCA Program ID',
			'CFP Hours',
			'NASBA Hours',
			'IAR E&PR Hours',
			'IAR P&P Hours',
			'EA Hours',
			'EA Program ID',
			'IAR Program ID',
			'IWI General Financial Planning',
			'IWI Taxes & Regulations',
			'IWI Ethics',
			'Date Individual Completed',
			'Last 4 Digits Attendee SSN',
			'Attendee E-mail Address',
			'Attendee Full Name',
			'Attendee Last Name',
			'Attendee First Name',
			'Attendee Middle Name',
			'Attendee CFP Board ID',
			'Attendee IMCA ID',
			'Attendee CPA Board ID',
			'Attendee PTIN ID',
			'Attendee ACC ID',
			'Attendee IAR ID',
			'Category',
		);

		foreach ( $results as $r ) {
			$created_by = ! empty( $r['created_by'] ) ? $r['created_by'] : '';
			$user       = get_user_by( 'id', $created_by ) ? get_user_by( 'id', $created_by ) : '';

			if ( empty( $user ) ) {
				continue;
			}

			$user_email          = $user ? $user->user_email : '';
			$user_first_name     = $user ? $user->first_name : '';
			$user_last_name      = $user ? $user->last_name : '';
			$user_name           = $user_first_name . ' ' . $user_last_name;
			$form_id             = ! empty( $r['form_id'] ) ? $r['form_id'] : '';
			$form                = $form_id ? GFAPI::get_form( $form_id ) : '';
			$form_title          = $form && ! empty( $form['title'] ) ? $form['title'] : '';
			$date                = kitces_timezone( $r['date_created'] );
			$program_id          = rgar( $form, 'cfp_program_id' );
			$imca_program_id     = rgar( $form, 'imca_program_id' );
			$category            = rgar( $form, 'quiz_category' );
			$cfp_hours           = rgar( $form, 'hours' );
			$nasba_hours         = rgar( $form, 'nasba_hours' );
			$ea_hours            = rgar( $form, 'ea_hours' );
			$ea_program_id       = rgar( $form, 'ea_program_id' );
			$iar_epr             = rgar( $form, 'iar_epr' );
			$iar_pp              = rgar( $form, 'iar_pp' );
			$iwi_gfp             = rgar( $form, 'iwi_gfp' );
			$iwi_tr              = rgar( $form, 'iwi_tr' );
			$iwi_ethics          = rgar( $form, 'iwi_ethics' );
			$cfp_ce_number       = kitces_get_ce_credit( 'CFP_CE_NUMBER', $user->ID );
			$imca_ce_number      = kitces_get_ce_credit( 'IMCA_CE_NUMBER', $user->ID );
			$cpa_ce_number       = kitces_get_ce_credit( 'CPA_CE_NUMBER', $user->ID );
			$ptin_ce_number      = kitces_get_ce_credit( 'PTIN_CE_NUMBER', $user->ID );
			$american_college_id = kitces_get_ce_credit( 'AMERICAN_COLLEGE_ID', $user->ID );
			$iar_ce_number       = kitces_get_ce_credit( 'IAR_CE_NUMBER', $user->ID );

			$output[] = array(
				'', // CE Sponsor Program ID Number.
				$form_title, // Program Name.
				$program_id, // Program ID.
				$imca_program_id, // IMCA Program ID.
				$cfp_hours, // CFP Hours.
				$nasba_hours, // NASBA Hours.
				$iar_epr, // IAR EPR Hours.
				$iar_pp, // IAR PP Hours.
				$ea_hours, // EA Hours.
				$ea_program_id, // EA Program ID.
				$iar_program_id, // IAR Program ID.
				$iwi_gfp, // IWI General Financial Planning.
				$iwi_tr, // IWI Taxes & Regulations.
				$iwi_ethics, // IWI Ethics.
				$date, // Date Individual Completed.
				'', // Last 4 Digits Attendee SSN.
				$user_email, // Attendee E-mail Address.
				$user_name, // Attendee Full Name.
				$user_last_name, // Attendee Last Name.
				$user_first_name, // Attendee First Name.
				'', // Attendee Middle Name.
				$cfp_ce_number, // Attendee CFP Board ID.
				$imca_ce_number, // Attendee IMCA ID.
				$cpa_ce_number, // Attendee CPA Board ID.
				$ptin_ce_number, // Attendee PTIN ID.
				$american_college_id, // ACC ID.
				$iar_ce_number, // IAR ID.
				$category, // Category.
			);
		}

		$this->array_to_csv_download( $output, $start_date . '-ce-export.csv' );
		die;
	}

	function detect_quiz_submission( $form ) {
		if ( $this->form_is_quiz( $form ) ) { // Do we have a quiz?
			$this->current_form = $form;

			$this->increment_quiz_attempts( $form['id'] );
		}
	}

	function after_quiz_submit( $entry, $form ) {
		global $post, $wpdb;

		if ( $this->form_is_quiz( $form ) && is_user_logged_in() ) { // Do we have a quiz?

			// Get quiz results
			$results           = $this->get_quiz_results( $form, $entry );
			$current_wp_user   = wp_get_current_user();
			$failed_first_quiz = false;

			if ( $results['is_pass'] ) {
				$this->set_quiz_passed( $form['id'] );
			} else {
				if ( $this->get_user_form_attempts( $form['id'] ) === $this->max_attempts ) {
					// Detect first time quiz fail
					$individual_quizzes = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( DISTINCT l.form_id ) FROM {$wpdb->prefix}gf_entry l LEFT JOIN {$wpdb->prefix}gf_entry_meta m ON m.entry_id = l.id AND m.meta_key = 'gquiz_is_pass' WHERE created_by = %d", $current_wp_user->ID ) );

					if ( intval( $individual_quizzes ) == 1 ) {
						$failed_first_quiz = true;
					}
				}
			}

			// Add Took A Quiz Tag
			if ( $current_wp_user instanceof WP_User ) {
				$this->ac_api->api(
					'contact/tag_add',
					array(
						'email' => $current_wp_user->user_email,
						'tags'  => array( 'MemberEngage-Took-a-Quiz' ),
					)
				);
			}

			// Detect first time quiz fail and email members@kitces.com
			if ( true === $failed_first_quiz ) {
				$ce_manager_email = function_exists( 'get_field' ) ? get_field( 'kitces_ce_manager_email', 'option' ) : 'Michael@Kitces.com';

				if ( ! empty( $ce_manager_email ) ) {
					wp_mail( $ce_manager_email, 'First Time Quiz Taker Failed', "{$current_wp_user->user_email} failed quiz {$form['title']} ({$form['id']}) twice and it seems to be their first quiz." );
				}
			}
		}
	}

	function get_ce_results( $start_date = false, $end_date = false ) {

		$search_criteria = array(
			'field_filters' => array(
				array(
					'key'   => 'gquiz_is_pass',
					'value' => '1',
				),
			),
		);

		$start_date = DateTime::createFromFormat( 'Y-m-d H:i:s', "$start_date 00:00:00" );
		$start_date = $start_date->format( 'Y-m-d H:i:s' );
		$end_date   = DateTime::createFromFormat( 'Y-m-d H:i:s', "$end_date 23:59:59" );
		$end_date   = $end_date->format( 'Y-m-d H:i:s' );

		if ( $start_date !== false && $end_date !== false ) {
			$search_criteria['start_date'] = $start_date;
			$search_criteria['end_date']   = $end_date;
		}

		$entries = GFAPI::get_entries(
			null,
			$search_criteria,
			array(
				'key'       => 'date_created',
				'direction' => 'ASC',
			),
			array(
				'offset'    => 0,
				'page_size' => 100000,
			)
		);

		return $entries;
	}

	function form_is_quiz( $form ) {
		if ( isset( $form->id ) ) {
			$form_id = $form->id;
		} elseif ( is_array( $form ) && isset( $form['id'] ) ) {
			$form_id = $form['id'];
		} else {
			return false;
		}

		$form  = GFAPI::get_form( $form_id );
		$label = ! empty( $form['fields'][0] ) && stripos( $form['fields'][0]->label, 'quiz' ) !== false;
		$title = ! empty( $form['title'] ) && stripos( $form['title'], 'quiz' ) !== false;

		return $label || $title;
	}

	function get_current_user_start_date() {
		$result = false;

		if ( is_user_logged_in() ) {
			$result = do_shortcode( '[kitces_members_contact field=START_DATE]' );

			if ( ! empty( $result ) ) {
				$result = strtotime( $result );
			}
		}

		return $result;
	}

	function verify_date_access( $form_string, $form ) {
		return $form_string;

		if ( ! $this->form_is_quiz( $form ) ) {
			return $form_string;
		}

		if ( kitces_is_valid_premier_member() ) { // If user is a full member, they get access to all quizzes
			return $form_string;
		} elseif ( kitces_is_valid_basic_member() ) { // If they are a basic member, validate quiz date
			// Take user start date, and back date it one month
			$start_date = strtotime( '-1 month', $this->get_current_user_start_date() );
			$day        = date( 'd', $start_date );

			// If first 5 days of the month, effective start date should be first day of prior month
			if ( $day <= 15 ) {
				$start_date = strtotime( '-2 months', $this->get_current_user_start_date() );
			}

			$start_date = strtotime( date( 'm/01/Y', $start_date ) );
			$quiz_date  = get_post_meta( get_the_id(), '_cgd_quiz_date', true );

			if ( $start_date != $quiz_date && $quiz_date < $start_date ) {
				return stripslashes( $this->get_setting( 'no_access_message' ) );
			}

			// else they have access
			return $form_string;
		} elseif ( kitces_is_valid_trial_member() ) {
			return $form_string;
		} else {
			return '<p>Sorry, you do not have access to this quiz.</p>';
		}
	}

	function get_user_form_attempts( $form_id, $user_id = false ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$meta_key = 'quiz_' . $form_id . '_attempts';

		return (int) get_user_meta( $user_id, $meta_key, true );
	}

	function get_quiz_passed( $form_id, $user_id = false ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$meta_key = 'quiz_' . $form_id . '_passed';

		return get_user_meta( $user_id, $meta_key, true ) == true;
	}

	function set_quiz_passed( $form_id, $user_id = false ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
			$meta_key = 'quiz_' . $form_id . '_passed';

		return update_user_meta( $user_id, $meta_key, true );
	}

	function set_user_form_attempts( $form_id, $attempts, $user_id = false ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$meta_key = 'quiz_' . $form_id . '_attempts';

		return update_user_meta( $user_id, $meta_key, $attempts, $this->get_user_form_attempts( $form_id, $user_id ) );
	}

	function set_form_context( $form_string, $form ) {
		$this->current_form = $form;

		return $form_string;
	}

	function enforce_max_attempts( $form_string, $form ) {
		if ( $this->form_is_quiz( $form ) && $this->get_user_form_attempts( $form['id'] ) >= $this->max_attempts ) {
			// Too many attempts on the dance floor
			return do_shortcode( $this->get_setting( 'locked_out_message' ) );
		}

		return $form_string;
	}

	function enforce_already_passed( $form_string, $form ) {
		if ( $this->form_is_quiz( $form ) && $this->get_quiz_passed( $form['id'] ) ) {
			// Too many attempts on the dance floor
			return do_shortcode( $this->get_setting( 'passed_message' ) );
		}

		return $form_string;
	}

	function increment_quiz_attempts( $form_id, $user_id = false ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$meta_key = 'quiz_' . $form_id . '_attempts';

		$attempts = (int) $this->get_user_form_attempts( $form_id );

		$this->set_user_form_attempts( $form_id, $attempts + 1, $user_id );
	}

	function add_quiz_fields( $user ) {
		$forms = RGFormsModel::get_forms( null, 'title' );
		?>
		<style>
			.reset-user-quizzes-tab input:checked + label {
				display: none;
			}
			.reset-user-quizzes-tab input:checked ~ .tab-content {
				display: unset;
			}
			.reset-user-quizzes-tab .tab-content {
				display: none;
			}
		</style>

		<hr>

		<h3>Reset Quiz Attempts</h3>

		<div class="reset-user-quizzes-tab">
			<input type="checkbox" id="reset-user-quizzes" style="display:none;">
			<label class="button" for="reset-user-quizzes">View Quizzes</label>
			<div class="tab-content">
				<p>Max quiz attempts is <?php echo $this->max_attempts; ?>.</p>
				<table class="form-table">
					<?php
					foreach ( $forms as $form ) :
						if ( ! $this->form_is_quiz( $form ) ) {
							continue;}
						?>
					<tr>
						<th><label for="quiz_<?php echo $form->id; ?>_attempts">Quiz <?php echo $form->id; ?>: <?php echo $form->title; ?></label></th>
						<td><input type="text" name="quiz_<?php echo $form->id; ?>_attempts" value="<?php echo esc_attr( $this->get_user_form_attempts( $form->id, $user->ID ) ); ?>" class="regular-text" /> attempts.</td>
					</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>

		<hr style="margin-top: 24px;">

		<?php
	}

	function save_quiz_fields( $user_id ) {
		$forms = RGFormsModel::get_forms( null, 'title' );

		foreach ( $forms as $form ) {
			$form = GFAPI::get_form( $form->id );

			if ( ! $this->form_is_quiz( $form ) ) {
				continue;
			}
			$meta_key = 'quiz_' . $form['id'] . '_attempts';

			if ( isset( $_POST[ $meta_key ] ) ) {
				$this->set_user_form_attempts( $form['id'], $_POST[ $meta_key ], $user_id );
			}
		}
	}

	function array_to_csv_download( $array, $filename = 'export.csv', $delimiter = ',' ) {
		// open raw memory as file so no temp files needed, you might run out of memory though
		$f = fopen( 'php://memory', 'w' );
		// loop over the input array
		foreach ( $array as $line ) {
			// generate csv lines from the inner arrays
			fputcsv( $f, $line, $delimiter );
		}
		// rewrind the "file" with the csv lines
		fseek( $f, 0 );
		// tell the browser it's going to be a csv file
		header( 'Content-Type: application/csv' );
		// tell the browser we want to save it instead of displaying it
		header( 'Content-Disposition: attachement; filename="' . $filename . '"' );
		// make php send the generated csv lines to the browser
		fpassthru( $f );
	}

	function quiz_settings_page() {
		?>
		<div class="wrap">
			<h2>Quiz Settings</h2>

			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
				<?php $this->the_nonce(); ?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row" valign="top">Before Quiz</th>
						<td>
							<label>
<textarea name="<?php echo $this->get_field_name( 'beforeQuizHtml' ); ?>" cols="50" rows="8">
		<?php echo stripslashes( $this->get_setting( 'beforeQuizHtml' ) ); ?>
</textarea><br/>
								This will display above the quiz.
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">Fail Confirmation</th>
						<td>
							<label>
<textarea name="<?php echo $this->get_field_name( 'failConfirmationMessage' ); ?>" cols="50" rows="8">
		<?php echo stripslashes( $this->get_setting( 'failConfirmationMessage' ) ); ?>
</textarea><br/>
								The message that users will see when they fail a quiz.
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">Fail Confirmation w/ Remaining Attempts</th>
						<td>
							<label>
<textarea name="<?php echo $this->get_field_name( 'failConfirmationMessage_retake' ); ?>" cols="50" rows="8">
		<?php echo stripslashes( $this->get_setting( 'failConfirmationMessage_retake' ) ); ?>
</textarea><br/>
								The message that users will see when they fail a quiz and have remaining attempts.
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">Pass Confirmation</th>
						<td>
							<label>
<textarea name="<?php echo $this->get_field_name( 'passConfirmationMessage' ); ?>" cols="50" rows="8">
		<?php echo stripslashes( $this->get_setting( 'passConfirmationMessage' ) ); ?>
</textarea><br/>
								The message that users will see when they pass a quiz.
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">Locked Out Message</th>
						<td>
							<label>
<textarea name="<?php echo $this->get_field_name( 'locked_out_message' ); ?>" cols="50" rows="8">
		<?php echo stripslashes( $this->get_setting( 'locked_out_message' ) ); ?>
</textarea><br/>
								The message that users will see when they open a quiz after they are out of attempts.
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">Passed Message</th>
						<td>
							<label>
<textarea name="<?php echo $this->get_field_name( 'passed_message' ); ?>" cols="50" rows="8">
		<?php echo stripslashes( $this->get_setting( 'passed_message' ) ); ?>
</textarea><br/>
								The message that users will see when they open a quiz after they have passed it.
							</label>
						</td>
					</tr>

					<tr>
						<th scope="row" valign="top">No Access Message</th>
						<td>
							<label>
<textarea name="<?php echo $this->get_field_name( 'no_access_message' ); ?>" cols="50" rows="8">
		<?php echo stripslashes( $this->get_setting( 'no_access_message' ) ); ?>
</textarea><br/>
								Users will see this message if they have a basic membership but their start date does not provide access to the quiz.
							</label>
						</td>
					</tr>

					<tr>
						<th scope="row" valign="top">Expired Quiz Message</th>
						<td>
							<label>
<textarea name="<?php echo $this->get_field_name( 'expired_quiz_message' ); ?>" cols="50" rows="8">
		<?php echo stripslashes( $this->get_setting( 'expired_quiz_message' ) ); ?>
</textarea><br/>
								Users will see this message if the quiz has expired.
							</label>
						</td>
					</tr>

					</tbody>
				</table>
				<p>Shortcodes: [remaining_attempts] [max_attempts] </p>

				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row" valign="top">Universal Pass Notification Email Template</th>
							<td>
								<label>
<textarea name="<?php echo $this->get_field_name( 'passNotificationEmailTemplate' ); ?>" cols="50" rows="8">
		<?php echo stripslashes( $this->get_setting( 'passNotificationEmailTemplate' ) ); ?>
</textarea><br/>
									The email that users receive when they pass a quiz.
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				<?php submit_button( 'Save Settings' ); ?>
			</form>
		</div>
		<?php
	}

	function admin_menu() {
		add_options_page( 'Quiz Settings', 'Quiz Settings', 'manage_options', 'quiz-settings', array( $this, 'quiz_settings_page' ) );
	}

	function remaining_attempts() {
		return $this->max_attempts - $this->get_user_form_attempts( $this->current_form['id'] );
	}

	function max_attempts() {
		return $this->max_attempts;
	}

	public function quiz_confirmation( $confirmation, $form, $lead, $ajax ) {

		$grading = $this->get_form_setting( $form, 'grading' );
		if ( $grading != 'none' ) {

			// make sure there are quiz fields on the form
			$quiz_fields = GFCommon::get_fields_by_type( $form, array( 'quiz' ) );
			if ( empty( $quiz_fields ) ) {
				return $confirmation;
			}

			switch ( $grading ) {
				case 'passfail':
					$display_confirmation = $this->get_form_setting( $form, 'passfailDisplayConfirmation' );
					if ( false === $display_confirmation ) {
						return $confirmation;
					}
					break;
				case 'letter':
					$display_confirmation = $this->get_form_setting( $form, 'letterDisplayConfirmation' );
					if ( false === $display_confirmation ) {
						return $confirmation;
					}
					break;
				default;
					return $confirmation;
			}

			$confirmation = '';

			$form_id = $form['id'];

			// override confirmation in the case of page redirect
			if ( is_array( $confirmation ) && array_key_exists( 'redirect', $confirmation ) ) {
				$confirmation = '';
			}

			// override confirmation in the case of a url redirect
			$str_pos = strpos( $confirmation, 'gformRedirect' );
			if ( false !== $str_pos ) {
				$confirmation = '';
			}

			$has_confirmation_wrapper = false !== strpos( $confirmation, 'gform_confirmation_wrapper' ) ? true : false;

			if ( $has_confirmation_wrapper ) {
				$confirmation = substr( $confirmation, 0, strlen( $confirmation ) - 6 );
			} //remove the closing div of the wrapper

			$has_confirmation_message = false !== strpos( $confirmation, 'gforms_confirmation_message' ) ? true : false;

			if ( $has_confirmation_message ) {
				$confirmation = substr( $confirmation, 0, strlen( $confirmation ) - 6 );
			} //remove the closing div of the message
			else {
				$confirmation .= "<div id='gforms_confirmation_message' class='gform_confirmation_message_{$form_id}'>";
			}

			$results           = $this->get_quiz_results( $form, $lead );
			$quiz_confirmation = '<div id="gquiz_confirmation_message">';
			$nl2br             = true;
			if ( $grading == 'letter' ) {
				$quiz_confirmation .= $this->get_form_setting( $form, 'letterConfirmationMessage' );
				if ( $this->get_form_setting( $form, 'letterConfirmationDisableAutoformat' ) === true ) {
					$nl2br = false;
				}
			} else {
				if ( $results['is_pass'] ) {
					$quiz_confirmation .= $this->get_setting( 'passConfirmationMessage' );
					if ( $this->get_form_setting( $form, 'passConfirmationDisableAutoformat' ) === true ) {
						$nl2br = false;
					}
				} else {

					if ( $this->form_is_quiz( $form ) && $this->get_user_form_attempts( $form['id'] ) >= $this->max_attempts ) {
						$quiz_confirmation .= $this->get_setting( 'failConfirmationMessage' );
					} else {
						$quiz_confirmation .= $this->get_setting( 'failConfirmationMessage_retake' );
					}

					if ( $this->get_form_setting( $form, 'failConfirmationDisableAutoformat' ) === true ) {
						$nl2br = false;
					}
				}
			}

			$quiz_confirmation .= '</div>';
			$quiz_confirmation  = stripslashes( $quiz_confirmation );

			$quiz_confirmation .= '<script type="text/javascript">jQuery(document).ready(function() { jQuery(document).scrollTop(jQuery("#gforms_confirmation_message").offset().top - 175); });</script>';

			$confirmation .= GFCommon::replace_variables( $quiz_confirmation, $form, $lead, $url_encode = false, $esc_html = true, $nl2br, $format = 'html' ) . '</div>';
			if ( $has_confirmation_wrapper ) {
				$confirmation .= '</div>';
			}
		}

		return $confirmation;
	}

	public function get_form_setting( $form, $setting_key ) {
		if ( false === empty( $form ) ) {
			$settings = $this->get_form_settings( $form );

			if ( isset( $settings[ $setting_key ] ) ) {
				$setting_value = $settings[ $setting_key ];
				if ( $setting_value == '1' ) {
					$setting_value = true;
				} elseif ( $setting_value == '0' ) {
					$setting_value = false;
				}
				if ( 'grades' == $setting_key && ! is_array( $setting_value ) ) {
					$setting_value = json_decode( $setting_value, true );
				}

				return $setting_value;
			}
		}

		// default values
		$value = '';
		switch ( $setting_key ) {
			case 'grading':
				$value = 'none';
				break;
			case 'passPercent':
				$value = 50;
				break;
			case 'failConfirmationMessage':
				$value = __( '<strong>Quiz Results:</strong> You Failed!\n<strong>Score:</strong> {quiz_score}\n<strong>Percentage:</strong> {quiz_percent}%', 'gravityformsquiz' );
				break;
			case 'passConfirmationMessage':
				$value = __( '<strong>Quiz Results:</strong> You Passed!\n<strong>Score:</strong> {quiz_score}\n<strong>Percentage:</strong> {quiz_percent}%', 'gravityformsquiz' );
				break;
			case 'letterConfirmationMessage':
				$value = __( '<strong>Quiz Grade:</strong> {quiz_grade}\n<strong>Score:</strong> {quiz_score}\n<strong>Percentage:</strong> {quiz_percent}%', 'gravityformsquiz' );
				break;
			case 'grades':
				$value = array(
					array(
						'text'  => 'A',
						'value' => 90,
					),
					array(
						'text'  => 'B',
						'value' => 80,
					),
					array(
						'text'  => 'C',
						'value' => 70,
					),
					array(
						'text'  => 'D',
						'value' => 60,
					),
					array(
						'text'  => 'E',
						'value' => 0,
					),
				);
				break;
			case 'passConfirmationDisableAutoformat':
			case 'failConfirmationDisableAutoformat':
			case 'letterConfirmationDisableAutoformat':
			case 'instantFeedback':
			case 'shuffleFields':
				$value = false;
				break;
			case 'passfailDisplayConfirmation':
			case 'letterDisplayConfirmation':
				$value = true;
				break;
		}

		return $value;

	}

	function get_form_settings( $form ) {
		return rgar( $form, 'gravityformsquiz' );
	}

	public function get_quiz_results( $form, $lead = array(), $show_question = true ) {
		$total_score = 0;

		$output['fields']  = array();
		$output['summary'] = '<div class="gquiz-container">';
		$fields            = GFCommon::get_fields_by_type( $form, array( 'quiz' ) );
		$pass_percent      = $this->get_form_setting( $form, 'passPercent' );
		$grades            = $this->get_form_setting( $form, 'grades' );
		$max_score         = $this->get_max_score( $form );

		foreach ( $fields as $field ) {
			$weighted_score_enabled = rgar( $field, 'gquizWeightedScoreEnabled' );
			$value                  = RGFormsModel::get_lead_field_value( $lead, $field );

			$field_score = 0;

			$field_markup = '<div class="gquiz-field">';
			if ( $show_question ) {
				$field_markup .= '    <div class="gquiz-field-label">';
				$field_markup .= GFCommon::get_label( $field );
				$field_markup .= '    </div>';
			}

			$field_markup .= '    <div class="gquiz-field-choice">';
			$field_markup .= '    <ul>';

			// for checkbox inputs with multiple correct choices
			$completely_correct = true;

			$choices = $field['choices'];

			foreach ( $choices as $choice ) {
				$is_choice_correct = isset( $choice['gquizIsCorrect'] ) && $choice['gquizIsCorrect'] == '1' ? true : false;

				$choice_weight           = isset( $choice['gquizWeight'] ) ? (float) $choice['gquizWeight'] : 1;
				$choice_class            = $is_choice_correct ? 'gquiz-correct-choice ' : '';
				$response_matches_choice = false;
				$user_responded          = true;
				if ( is_array( $value ) ) {
					foreach ( $value as $item ) {
						if ( RGFormsModel::choice_value_match( $field, $choice, $item ) ) {
							$response_matches_choice = true;
							break;
						}
					}
				} elseif ( empty( $value ) ) {
					$response_matches_choice = false;
					$user_responded          = false;
				} else {
					$response_matches_choice = RGFormsModel::choice_value_match( $field, $choice, $value ) ? true : false;

				}
				$is_response_correct = $is_choice_correct && $response_matches_choice;
				if ( $response_matches_choice && $weighted_score_enabled ) {
					$field_score += $choice_weight;
				}

				if ( $field['inputType'] == 'checkbox' ) {
					$is_response_wrong = ( ( ! $is_choice_correct ) && $response_matches_choice ) || ( $is_choice_correct && ( ! $response_matches_choice ) ) || $is_choice_correct && ! $user_responded;
				} else {
					$is_response_wrong = ( ( ! $is_choice_correct ) && $response_matches_choice ) || $is_choice_correct && ! $user_responded;
				}

				$indicator_markup = '';
				if ( $is_response_correct ) {
					$indicator_markup = '<img src="' . $this->_correct_indicator_url . '" />';
					$choice_class    .= 'gquiz-correct-response ';
				} elseif ( $is_response_wrong ) {
					$indicator_markup   = '<img src="' . $this->_incorrect_indicator_url . '" />';
					$completely_correct = false;
					$choice_class      .= 'gquiz-incorrect-response ';
				}

				$indicator_markup = apply_filters( 'gquiz_answer_indicator', $indicator_markup, $form, $field, $choice, $lead, $is_response_correct, $is_response_wrong );

				$choice_class_markup = empty( $choice_class ) ? '' : 'class="' . $choice_class . '"';
				$field_markup       .= "<li {$choice_class_markup}>";

				$field_markup .= $choice['text'] . $indicator_markup;
				$field_markup .= '</li>';

			} // end foreach choice

			$field_markup .= '    </ul>';
			$field_markup .= '    </div>';

			if ( rgar( $field, 'gquizShowAnswerExplanation' ) ) {
				$field_markup .= '<div class="gquiz-answer-explanation">';
				$field_markup .= $field['gquizAnswerExplanation'];
				$field_markup .= '</div>';
			}

			$field_markup .= '</div>';
			if ( ! $weighted_score_enabled && $completely_correct ) {
				$field_score += 1;
			}
			$output['summary'] .= $field_markup;
			array_push(
				$output['fields'],
				array(
					'id'         => $field['id'],
					'markup'     => $field_markup,
					'is_correct' => $completely_correct,
					'score'      => $field_score,
				)
			);
			$total_score += $field_score;

		} // end foreach field
		$total_score        = max( $total_score, 0 );
		$output['summary'] .= '</div>';
		$output['score']    = $total_score;
		$total_percent      = $max_score > 0 ? $total_score / $max_score * 100 : 0;
		$output['percent']  = round( $total_percent );
		$total_grade        = $this->get_grade( $grades, $total_percent );

		$output['grade']   = $total_grade;
		$is_pass           = $total_percent >= $pass_percent ? true : false;
		$output['is_pass'] = $is_pass;

		return $output;
	}

	public function get_grade( $grades, $percent ) {
		$the_grade = '';
		usort( $grades, array( $this, 'sort_grades' ) );
		foreach ( $grades as $grade ) {
			if ( $grade['value'] <= (float) $percent ) {
				$the_grade = $grade['text'];
				break;
			}
		}

		return $the_grade;
	}

	public function sort_grades( $a, $b ) {
		return $a['value'] < $b['value'];
	}

	public function get_max_score( $form ) {
		$max_score = 0;
		$fields    = GFCommon::get_fields_by_type( $form, array( 'quiz' ) );

		foreach ( $fields as $field ) {
			if ( rgar( $field, 'gquizWeightedScoreEnabled' ) ) {
				if ( GFFormsModel::get_input_type( $field ) == 'checkbox' ) {
					foreach ( $field['choices'] as $choice ) {
						$weight     = (float) rgar( $choice, 'gquizWeight' );
						$max_score += max( $weight, 0 ); // don't allow negative scores to impact the max score
					}
				} else {
					$max_score_for_field = 0;
					foreach ( $field['choices'] as $choice ) {
						$max_score_for_choice = (float) rgar( $choice, 'gquizWeight' );
						$max_score_for_field  = max( $max_score_for_choice, $max_score_for_field );
					}
					$max_score += $max_score_for_field;
				}
			} else {
				$max_score += 1;
			}
		}

		return $max_score;
	}

	function quiz_metabox() {
		$prefix = '_cgd_';

		$quiz_mb = new_cmb2_box(
			array(
				'id'           => $prefix . 'quiz_settings',
				'title'        => 'Quiz Settings',
				'object_types' => array( 'page' ),
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,
			)
		);

		$quiz_mb->add_field(
			array(
				'id'   => $prefix . 'quiz_date',
				'name' => 'Quiz Date',
				'type' => 'text_date_timestamp',
			)
		);

	}

	function page_is_quiz( $cmb ) {
		$page = get_post( $cmb->object_id );

		return ( stripos( $page->post_title, 'quiz' ) !== false );
	}

	function can_member_access_quiz( $post_id ) {

		if ( kitces_is_valid_premier_member() ) { // If user is a full member, they get access to all quizzes
			return true;
		} elseif ( kitces_is_valid_basic_member() ) { // If they are a basic member, validate quiz date
			// Take user start date, and back date it one month
			$start_date = strtotime( '-1 month', $this->get_current_user_start_date() );
			$day        = date( 'd', $start_date );

			// If first 5 days of the month, effective start date should be first day of prior month
			if ( $day <= 15 ) {
				$start_date = strtotime( '-2 months', $this->get_current_user_start_date() );
			}

			$start_date = strtotime( date( 'm/01/Y', $start_date ) );
			$quiz_date  = get_post_meta( $post_id, '_cgd_quiz_date', true );

			if ( $start_date != $quiz_date && $quiz_date < $start_date ) {
				return false;
			}

			// else they have access
			return true;
		} elseif ( kitces_is_valid_trial_member() ) {
			return true;
		} else {
			return false;
		}

	}

	function custom_gf_form_settings( $settings, $form ) {
		$settings['Form Basics']['program_title'] = '
        <tr>
            <th><label for="program_title">Program Title</label></th>
            <td><input value="' . rgar( $form, 'program_title' ) . '" name="program_title"></td>
        </tr>';

		$settings['Form Basics']['cfp_program_id'] = '
		<tr>
			<th><label for="cfp_program_id">CFP Program ID</label></th>
			<td><input value="' . rgar( $form, 'cfp_program_id' ) . '" name="cfp_program_id"></td>
		</tr>';

		$settings['Form Basics']['imca_program_id'] = '
		<tr>
			<th><label for="imca_program_id">IMCA Program ID</label></th>
			<td><input value="' . rgar( $form, 'imca_program_id' ) . '" name="imca_program_id"></td>
		</tr>';

		$settings['Form Basics']['ea_program_id'] = '
		<tr>
			<th><label for="ea_program_id">EA Program ID</label></th>
			<td><input value="' . rgar( $form, 'ea_program_id' ) . '" name="ea_program_id"></td>
		</tr>';

		$settings['Form Basics']['iar_program_id'] = '
		<tr>
			<th><label for="iar_program_id">IAR Program ID</label></th>
			<td><input value="' . rgar( $form, 'iar_program_id' ) . '" name="iar_program_id"></td>
		</tr>';

		$settings['Form Basics']['iar_epr'] = '
		<tr>
			<th><label for="iar_hours">Hours of IAR (EP&R) Credit </label></th>
			<td><input value="' . rgar( $form, 'iar_epr' ) . '" name="iar_epr"></td>
		</tr>';

		$settings['Form Basics']['iar_pp'] = '
		<tr>
			<th><label for="iar_pp">Hours of IAR (P&P) Credit </label></th>
			<td><input value="' . rgar( $form, 'iar_pp' ) . '" name="iar_pp"></td>
		</tr>';

		$settings['Form Basics']['hours'] = '
		<tr>
			<th><label for="hours">Hours of CE Credit (CFP)</label></th>
			<td><input value="' . rgar( $form, 'hours' ) . '" name="hours"></td>
		</tr>';

		$settings['Form Basics']['nasba_hours'] = '
		<tr>
			<th><label for="nasba_hours">Hours of CE Credit (NASBA)</label></th>
			<td><input value="' . rgar( $form, 'nasba_hours' ) . '" name="nasba_hours"></td>
		</tr>';

		$settings['Form Basics']['ea_hours'] = '
		<tr>
			<th><label for="ea_hours">Hours of CE Credit (EA)</label></th>
			<td><input value="' . rgar( $form, 'ea_hours' ) . '" name="ea_hours"></td>
		</tr>';

		$settings['Form Basics']['iwi_gfp'] = '
		<tr>
			<th><label for="iwi_gfp">IWI General Financial Planning</label></th>
			<td><input value="' . rgar( $form, 'iwi_gfp' ) . '" name="iwi_gfp"></td>
		</tr>';

		$settings['Form Basics']['iwi_tr'] = '
		<tr>
			<th><label for="iwi_tr">IWI Taxes & Regulations</label></th>
			<td><input value="' . rgar( $form, 'iwi_tr' ) . '" name="iwi_tr"></td>
		</tr>';

		$settings['Form Basics']['iwi_ethics'] = '
		<tr>
			<th><label for="iwi_ethics">IWI Ethics</label></th>
			<td><input value="' . rgar( $form, 'iwi_ethics' ) . '" name="iwi_ethics"></td>
		</tr>';

		$settings['Form Basics']['course_reviewer'] = '
		<tr>
			<th><label for="course_reviewer">Course Reviewer</label></th>
			<td><input value="' . rgar( $form, 'course_reviewer' ) . '" name="course_reviewer"></td>
		</tr>';
		$settings['Form Basics']['quiz_category']   = '
		<tr>
			<th><label for="quiz_category">Quiz Category</label></th>
			<td><input value="' . rgar( $form, 'quiz_category' ) . '" name="quiz_category"></td>
		</tr>';

		$settings['Form Basics']['quiz_category'] = '
		<tr>
			<th><label for="quiz_category">Quiz Category</label></th>
			<td><input value="' . rgar( $form, 'quiz_category' ) . '" name="quiz_category"></td>
		</tr>';

		return $settings;
	}

	function save_custom_gf_form_settings( $form ) {
		$form['program_title']   = rgpost( 'program_title' );
		$form['cfp_program_id']  = rgpost( 'cfp_program_id' );
		$form['imca_program_id'] = rgpost( 'imca_program_id' );
		$form['ea_program_id']   = rgpost( 'ea_program_id' );
		$form['hours']           = rgpost( 'hours' );
		$form['nasba_hours']     = rgpost( 'nasba_hours' );
		$form['ea_hours']        = rgpost( 'ea_hours' );
		$form['iar_program_id']  = rgpost( 'iar_program_id' );
		$form['iar_epr']         = rgpost( 'iar_epr' );
		$form['iar_pp']          = rgpost( 'iar_pp' );
		$form['iwi_gfp']         = rgpost( 'iwi_gfp' );
		$form['iwi_tr']          = rgpost( 'iwi_tr' );
		$form['iwi_ethics']      = rgpost( 'iwi_ethics' );
		$form['course_reviewer'] = rgpost( 'course_reviewer' );
		$form['quiz_category']   = rgpost( 'quiz_category' );

		return $form;
	}

	function filter_merge_tags( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {
		$custom_merge_tags = array(
			'program_title'   => '{program_title}',
			'cfp_program_id'  => '{cfp_program_id}',
			'imca_program_id' => '{imca_program_id}',
			'ea_program_id'   => '{ea_program_id}',
			'iar_program_id'  => '{iar_program_id}',
			'hours'           => '{hours}',
			'nasba_hours'     => '{nasba_hours}',
			'ea_hours'        => '{ea_hours}',
			'iar_epr'         => '{iar_epr}',
			'iar_pp'          => '{iar_pp}',
			'iwi_gfp'         => '{iwi_gfp}',
			'iwi_tr'          => '{iwi_tr}',
			'iwi_ethics'      => '{iwi_ethics}',
			'course_reviewer' => '{course_reviewer}',
			'quiz_category'   => '{quiz_category}',
		);

		foreach ( $custom_merge_tags as $custom_field_id => $merge_tag ) {
			if ( strpos( $text, $merge_tag ) === false ) {
				continue;
			}

			$custom_value = rgar( $form, $custom_field_id );
			$text         = str_replace( $merge_tag, $custom_value, $text );
		}

		return $text;
	}

	function override_pass_notification_email() {
		global $wpdb;

		$new_template = $this->get_setting( 'passNotificationEmailTemplate' );

		$all_quiz_meta = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}gf_form_meta` WHERE notifications LIKE '%User Pass Notification%'" );

		foreach ( $all_quiz_meta as $quiz_meta_row ) {
			$display_meta       = json_decode( $quiz_meta_row->display_meta );
			$notifications_meta = json_decode( $quiz_meta_row->notifications );

			if ( is_object( $display_meta ) && is_object( $notifications_meta ) ) {
				if ( ! empty( $display_meta->notifications ) ) {
					foreach ( get_object_vars( $display_meta->notifications ) as $key => $notification ) {
						if ( 'User Pass Notification' == $notification->name ) {
							$display_meta->notifications->{$key}->message           = stripslashes( $new_template );
							$display_meta->notifications->{$key}->disableAutoformat = false;
						}
					}
				}

				foreach ( get_object_vars( $notifications_meta ) as $key => $notification ) {
					if ( 'User Pass Notification' == $notification->name ) {
						$notifications_meta->{$key}->message           = stripslashes( $new_template );
						$notifications_meta->{$key}->disableAutoformat = false;
					}
				}

				$display_meta       = json_encode( $display_meta );
				$notifications_meta = json_encode( $notifications_meta );

				$result = $wpdb->query( $query = $wpdb->prepare( "UPDATE `{$wpdb->prefix}gf_form_meta` SET display_meta = %s, notifications = %s WHERE form_id = %d", $display_meta, $notifications_meta, $quiz_meta_row->form_id ) );
			}
		}
	}

	function add_before_quiz( $form_string, $form ) {
		if ( $this->form_is_quiz( $form ) ) {
			$form_string = wpautop( stripslashes( $this->get_setting( 'beforeQuizHtml' ) ) ) . $form_string;
		}

		return $form_string;
	}

	function cfp_hours_shortcode() {
		global $course_catalog_post;

		$associated_form = get_post_meta( $course_catalog_post->ID, 'associated_quiz_form', true );
		$form            = GFAPI::get_form( $associated_form );

		return rgar( $form, 'hours' );
	}

	function nasba_hours_shortcode() {
		global $course_catalog_post;

		$associated_form = get_post_meta( $course_catalog_post->ID, 'associated_quiz_form', true );
		$form            = GFAPI::get_form( $associated_form );

		return rgar( $form, 'nasba_hours' );
	}

	function ea_hours_shortcode() {
		global $course_catalog_post;

		$associated_form = get_post_meta( $course_catalog_post->ID, 'associated_quiz_form', true );
		$form            = GFAPI::get_form( $associated_form );

		return rgar( $form, 'ea_hours' );
	}

	function iar_hours_shortcode() {
		global $course_catalog_post;

		$associated_form = get_post_meta( $course_catalog_post->ID, 'associated_quiz_form', true );
		$form            = GFAPI::get_form( $associated_form );

		return rgar( $form, 'iar_hours' );
	}

	function ea_program_id_shortcode() {
		global $course_catalog_post;

		$associated_form = get_post_meta( $course_catalog_post->ID, 'associated_quiz_form', true );
		$form            = GFAPI::get_form( $associated_form );

		return ! empty( rgar( $form, 'ea_program_id' ) ) ? rgar( $form, 'ea_program_id' ) : 'N/A';
	}

	function iar_program_id_shortcode() {
		global $course_catalog_post;

		$associated_form = get_post_meta( $course_catalog_post->ID, 'associated_quiz_form', true );
		$form            = GFAPI::get_form( $associated_form );

		return ! empty( rgar( $form, 'iar_program_id' ) ) ? rgar( $form, 'iar_program_id' ) : 'N/A';
	}

	function iwi_gfp_hours_shortcode() {
		global $course_catalog_post;

		$associated_form = get_post_meta( $course_catalog_post->ID, 'associated_quiz_form', true );
		$form            = GFAPI::get_form( $associated_form );

		return rgar( $form, 'iwi_gfp' );
	}

	function iwi_tr_hours_shortcode() {
		global $course_catalog_post;

		$associated_form = get_post_meta( $course_catalog_post->ID, 'associated_quiz_form', true );
		$form            = GFAPI::get_form( $associated_form );

		return rgar( $form, 'iwi_tr' );
	}

	function iwi_ethics_hours_shortcode() {
		global $course_catalog_post;

		$associated_form = get_post_meta( $course_catalog_post->ID, 'associated_quiz_form', true );
		$form            = GFAPI::get_form( $associated_form );

		return rgar( $form, 'iwi_ethics' );
	}

}

$CGD_CECredits = new CGD_CECredits();
