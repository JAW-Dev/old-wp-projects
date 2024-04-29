<?php
/**
 * Password Reset Form
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Shortcodes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Shortcodes;

use Kitces_Members\Includes\Classes\Utilities\Svg;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Password Reset Form
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class PaswwordResetForm {

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
		add_shortcode( 'kitces_password_reset_form', array( $this, 'password_reset_form' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
	}

	/**
	 * Password Reset Form
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function password_reset_form( $atts ) {
		$atts = shortcode_atts(
			array( 'redirect_url' => '' ),
			$atts,
			'kitces_password_reset_form'
		);

		ob_start();
		?>
		<div id="password-reset-form-notification" class="password-reset-form__notification">
			<div class="password-reset-form__notification-overlay"></div>
			<div class="password-reset-form__notification-message">Password Changed!</div>
		</div>
		<form method="post" action='' id="password-reset-form" class="password-reset-form">
			<?php wp_nonce_field( 'form_submit', 'password_reset_form_submit' ); ?>
			<?php if ( ! empty( $atts['redirect_url'] ) ) : ?>
				<input type="hidden" id="redirect-url" value="<?php echo esc_attr( $atts['redirect_url'] ); ?>" name="redirect-url" />
			<?php endif; ?>
			<div class="password-reset-form__form-controll">
				<div id="password-reset-form-meter" class="password-reset-form__meter">
					<div id="password-reset-form-meter-very-weak" class="password-reset-form__meter-very-weak">Very Weak</div>
					<div id="password-reset-form-meter-weak" class="password-reset-form__meter-weak">Weak</div>
					<div id="password-reset-form-meter-medium" class="password-reset-form__meter-medium">Medium</div>
					<div id="password-reset-form-meter-strong" class="password-reset-form__meter-strong">Strong</div>
				</div>
			</div>

			<div class="password-reset-form__form-controll password-reset-form__password-one">
				<label for="password-1" class="password-reset-form__label">New Password:</label>
				<div class="password-reset-form__text-input-wrap">
					<input type="password" id="password-1" class="password-reset-form__text-input" name="password-1" required/>
					<?php $this->tooltip( '1' ); ?>
				</div>
			</div>

			<div class="password-reset-form__form-controll password-reset-form__password-two">
				<label for="password-2" class="password-reset-form__label">Repeat Password:</label>
				<div class="password-reset-form__text-input-wrap">
					<input type="password" id="password-2" class="password-reset-form__text-input" name="password-2"  required>
					<?php $this->tooltip( '2', true ); ?>
				</div>
			</div>
			<div class="password-reset-form__form-controll">
				<input type="submit" id="password-reset-form-submit" class="password-reset-form__submit" value="Change Password" name="submit" disabled="disabled">
			</div>
		</form>
		<div id="password-reset-overlay" class="overlay"></div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Tooltip
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string  $field The password field.
	 * @param boolean $match If to add password match validation.
	 *
	 * @return void
	 */
	public function tooltip( $field, $match = false ) {
		?>
		<div id="password-reset-form-tooltip-pass-<?php echo esc_attr( $field ); ?>" class="password-reset-form__tooltip tooltip top">
			<div class="password-reset-form__tooltip-content tooltip__content">
				<?php
				$this->tooltip_inner( "password-reset-form-validation-char-length-$field", 'At least 12 characters', $field );
				$this->tooltip_inner( "password-reset-form-validation-upper-case-$field", 'At least 1 uppcase letter', $field );
				$this->tooltip_inner( "password-reset-form-validation-number-$field", 'At least 1 number', $field );
				$this->tooltip_inner( "password-reset-form-special-char-$field", 'At least 1 special character e.g !@#$%^&*()_+><?', $field );
				$this->tooltip_inner( "password-reset-form-pass-match-$field", 'Passwords match', $field );
				$this->tooltip_inner( "password-reset-form-forbidden-word-$field", 'Contains a forbidden word!', $field, false, false );
				$this->tooltip_inner( "password-reset-form-spaces-$field", 'Spaces not allowed!', $field, false, false );
				?>
			</div>
			<i></i>
		</div>
		<?php
	}

	/**
	 * Tooltip Inner
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $id           The ID to add to the main element.
	 * @param string $message      The text message for the lable.
	 * @param string $field        The focused field.
	 * @param bool   $show_icon    If to show the icon.
	 * @param bool   $show_message If to show the message.
	 *
	 * @return void
	 */
	public function tooltip_inner( $id, $message, $field, $show_icon = true, $show_message = true ) {
		$styles = ! $show_message ? 'display: none' : '';
		?>
		<div id="<?php echo esc_attr( $id ); ?>" class="password-reset-form__content-label">
			<div class="password-reset-form__content-text" style="<?php echo esc_attr( $styles ); ?>">
				<?php echo esc_html( $message ); ?>
			</div>

			<?php if ( $show_icon ) : ?>
				<div class="password-reset-form__icons">
					<div id="password-reset-form-icon-times-<?php echo esc_attr( $field ); ?>" class="password-reset-form__icon times">
						<?php echo wp_kses( Svg::get( 'times-circle' ), Svg::svg_kses() ); ?>
					</div>
					<div id="password-reset-form-icon-check-<?php echo esc_attr( $field ); ?>" class="password-reset-form__icon check">
						<?php echo wp_kses( Svg::get( 'check-circle' ), Svg::svg_kses() ); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
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
		if ( ! is_page_template( array( 'template-account.php', 'template-password-reset.php' ) ) ) {
			return;
		}

		$filename = 'src/js/password-reset.js';
		$file     = KICTES_MEMBERS_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';
		$handle   = 'kitces-password-reset';

		wp_register_script( $handle, KICTES_MEMBERS_DIR_URL . $filename, array(), $version, true );
		wp_enqueue_script( $handle );

		wp_enqueue_script( 'password-strength-meter' );
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
		if ( ! is_page_template( array( 'template-account.php', 'template-password-reset.php' ) ) ) {
			return;
		}

		$filename = 'src/css/password-reset.css';
		$file     = KICTES_MEMBERS_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';
		$handle   = 'kitces-password-reset';

		wp_register_style( $handle, KICTES_MEMBERS_DIR_URL . $filename, array(), $version, 'all' );
		wp_enqueue_style( $handle );
	}
}
