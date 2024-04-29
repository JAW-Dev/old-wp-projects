<?php
/**
 * Member Credits Form
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Shortcodes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Shortcodes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Member Credits Form
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class MemberCreditsForm {

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
		add_shortcode( 'kitces_members_credits_form', array( $this, 'form' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
	}

	/**
	 * Form
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function form() {
		ob_start();
		?>
		<div id="ac-profile-field-notification" class="ac-profile-field__notification">
			<div class="ac-profile-field__notification-overlay"></div>
			<div class="ac-profile-field__notification-message">Updated!</div>
		</div>
		<form method="post" action='' id="ac-credits-form">
			<?php wp_nonce_field( 'form_submit', 'ac_credits_form_submit' ); ?>
			<div class="ac-profile-field">
				<label for="ac_custom_CFPCEnumber">CFP CE number</label>
				<input id="ac_custom_CFPCEnumber" class="ac-profile-field__input-container" name="cfp_ce_number" type="number" placeholder="Type a number" onKeyPress="if(this.value.length==8) return false;" value="<?php echo esc_attr( do_shortcode( '[kitces_members_contact field=CFP_CE_NUMBER]' ) ); ?>" />
			</div>

			<div class="ac-profile-field">
				<label for="ac_custom_IMCACENumber">IWI CE number</label>
				<input id="ac_custom_IMCACENumber" class="ac-profile-field__input-container" name="imca_ce_number" type="number" placeholder="Type a number" onKeyPress="if(this.value.length==8) return false;" value="<?php echo esc_attr( do_shortcode( '[kitces_members_contact field=IMCA_CE_NUMBER]' ) ); ?>" />
			</div>

			<div class="ac-profile-field">
				<label for="ac_custom_CPACENumber">CPA CE number</label>
				<input id="ac_custom_CPACENumber" class="ac-profile-field__input-container" name="cpa_ce_number" type="number" placeholder="Type a number" value="<?php echo esc_attr( do_shortcode( '[kitces_members_contact field=CPA_CE_NUMBER]' ) ); ?>" />
			</div>

			
			<div class="ac-profile-field">
			
				<label for="ac_custom_PTINCENumber">PTIN (Enrolled Agent)</label>
				<input id="ac_custom_PTINCENumber" class="ac-profile-field__input-container" name="ptin_ce_number" type="number" placeholder="Type a number" onKeyPress="if(this.value.length==7) return false;" value="<?php echo esc_attr( do_shortcode( '[kitces_members_contact field=PTIN_CE_NUMBER]' ) ); ?>" />
			</div>
	

			<div class="ac-profile-field">
				<label for="ac_custom_ACCNumber">American College Student ID</label>
				<input id="ac_custom_ACCNumber" class="ac-profile-field__input-container" name="american_college_id" type="number" placeholder="Type a number" onKeyPress="if(this.value.length==8) return false;" value="<?php echo esc_attr( do_shortcode( '[kitces_members_contact field=AMERICAN_COLLEGE_ID]' ) ); ?>" />
			</div>


			<div class="ac-profile-field">
				<label for="ac_custom_IARNumber">Individual CRD Number for IAR Licensure</label>
				<input id="ac_custom_IARNumber" class="ac-profile-field__input-container" name="iar_ce_number" type="number" placeholder="Type a number" value="<?php echo esc_attr( do_shortcode( '[kitces_members_contact field=IAR_CE_NUMBER]' ) ); ?>" />
			</div>


			<input name="imca_ce_submitted" type="hidden" value="SUBMITTED" />

			<div style="margin-top: 2rem;">
				<input type="submit" id="ac-credits-form-submit" class="ac-profile-field__submit" value="Submit" />
			</div>
		</form>
		<div class="mt1 italic">*CE is reported to the CFP Board and IWI on the 1st and 16th of each month. You may use the Completion Certificates to self-report at your convenience.</div>
		<div id="ce-form-overlay" class="overlay"></div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function scripts() {
		if ( ! is_page_template( 'template-account.php' ) ) {
			return;
		}

		$filename = 'src/js/credits-form.js';
		$file     = KICTES_MEMBERS_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';
		$handle   = 'kitces-credits-form';

		wp_register_script( $handle, KICTES_MEMBERS_DIR_URL . $filename, array(), $version, true );
		wp_enqueue_script( $handle );
	}

	/**
	 * Styles
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function styles() {
		if ( ! is_page_template( 'template-account.php' ) ) {
			return;
		}

		$filename = 'src/css/credits-form.css';
		$file     = KICTES_MEMBERS_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';
		$handle   = 'kitces-credits-form';

		wp_register_style( $handle, KICTES_MEMBERS_DIR_URL . $filename, array(), $version, 'all' );
		wp_enqueue_style( $handle );
	}
}
