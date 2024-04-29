<?php
/**
 * Login Error Message
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
 * Login Error Message
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class LoginErrorMessage {

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
		add_shortcode( 'kitces_login_error_message', array( $this, 'show_message' ) );
	}

	/**
	 * Show Message
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $atts {
	 *     The shortcode attributes.
	 *
	 *     @type string $message The error message.
	 *     @type string $class   Additional class to add.
	 * }
	 *
	 * @return mixed
	 */
	public function show_message( $atts ) {
		$failed = sanitize_post( wp_unslash( $_GET['login'] ?? '' ) );

		if ( empty( $failed ) ) {
			return;
		}

		$atts = shortcode_atts(
			array(
				'message' => 'ERROR: Incorrect Username or Password',
				'class'   => '',
			),
			$atts,
			'kitces_login_error_message'
		);

		ob_start();
		?>
		<div class="login_error_message<?php echo esc_attr( $atts['class'] ); ?>">
			<p><?php echo wp_kses_post( $atts['message'] ); ?></p>
		</div>
		<?php
		return ob_get_clean();
	}
}
