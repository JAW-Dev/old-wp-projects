<?php
/**
 * Reader Form
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/MemberActions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\MemberActions;

use Kitces_Members\Includes\Classes\ActiveCampaign\Contact;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Main
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class ReaderForm {
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
		$form_id = get_option( 'options_kitces_reader_signup_form_id' );

		if ( ! empty( $form_id ) ) {
			add_filter( "gform_field_validation_{$form_id}", array( $this, 'wp_account_check' ), 10, 4 );
		}

		add_action( 'gform_admin_pre_render', array( $this, 'add_merge_tags' ) );
		add_filter( 'gform_replace_merge_tags', array( $this, 'survey_url_tag' ) );
	}

	/**
	 * Add Merge Tgs
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $form The current form object to be filtered.
	 *
	 * @return array
	 */
	public function add_merge_tags( $form ) {
		?>
		<script type = "text/javascript">
			gform.addFilter('gform_merge_tags', 'add_merge_tags');
			function add_merge_tags(mergeTags, elementId, hideAllFields, excludeFieldTypes, isPrepop, option) {
				mergeTags["custom"].tags.push({
					tag: '{Survey_URL}',
					label: 'Survey URL'
				});
				return mergeTags;
			}
		</script>
		<?php
		return $form;
	}

	/**
	 * Survey URL Tag
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $text The current text in which merge tags are being replaced.
	 *
	 * @return string
	 */
	public function survey_url_tag( $text ) {
		$custom_merge_tag = '{Survey_URL}';

		if ( strpos( $text, $custom_merge_tag ) === false ) {
			return $text;
		}

		$survey_url = function_exists( 'get_field' ) ? get_field( 'kitces_survey_redirect_url', 'option' ) : '';
		$text       = str_replace( $custom_merge_tag, esc_url( $survey_url ), $text );

		return $text;
	}

	/**
	 * WP Account Check
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array        $result The validation result to be filtered.
	 * @param string|array $value  The field value to be validated.
	 * @param object       $form   Current form object.
	 * @param object       $field  Current field object.
	 *
	 * @return array
	 */
	public function wp_account_check( $result, $value, $form, $field ) {
		if ( $field->type !== 'email' ) {
			return $result;
		}

		$email = $value;
		$user  = get_user_by( 'email', $email );

		if ( $result['is_valid'] && ! empty( $user ) ) {
			$message            = function_exists( 'get_field' ) ? $this->survey_url_tag( get_field( 'kitces_reader_account_exists_error_message', 'option' ) ) : '';
			$result['is_valid'] = false;
			$result['message']  = $message;
			return $result;
		}

		( new Contact() )->create_reader_account();

		return $result;
	}
}
