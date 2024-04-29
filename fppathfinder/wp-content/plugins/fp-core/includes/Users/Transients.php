<?php
/**
 * Transients
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Users
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Users;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Transients
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Transients {

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
		add_action( 'edit_user_profile', [ $this, 'view' ] );
		add_action( 'show_user_profile', [ $this, 'view' ], 999 );
	}

	/**
	 * View
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function view( $user ) {
		global $wpdb;
		echo '<h3 class="heading">User Transients</h3>';

		// $transients = [
		// 	'Whitelabel Resource'                 => '_transient_' . $user->ID . '_whitelabel_resource_transient',
		// 	'Whitelabel Back Page'                => '_transient_' . $user->ID . '_whitelabel_back_page_transient',
		// 	'Whitelabel Back Page Advanced'       => '_transient_' . $user->ID . '_whitelabel_back_page_advanced_transient',
		// 	'Group Whitelabel Resource'           => '_transient_' . $user->ID . '_group_whitelabel_resource_transient',
		// 	'Group Whitelabel Back Page'          => '_transient_' . $user->ID . '_group_whitelabel_back_page_transient',
		// 	'Group Whitelabel Back Page Advanced' => '_transient_' . $user->ID . '_group_whitelabel_back_page_advanced_transient',
		// ];

		$transients = [
			'Whitelabel Resource'                 => $user->ID . '_whitelabel_resource_transient',
			'Whitelabel Back Page'                => $user->ID . '_whitelabel_back_page_transient',
			'Whitelabel Back Page Advanced'       => $user->ID . '_whitelabel_back_page_advanced_transient',
			'Group Whitelabel Resource'           => $user->ID . '_group_whitelabel_resource_transient',
			'Group Whitelabel Back Page'          => $user->ID . '_group_whitelabel_back_page_transient',
			'Group Whitelabel Back Page Advanced' => $user->ID . '_group_whitelabel_back_page_advanced_transient',
		];

		?>
		<table class="form-table">
			<?php
			foreach ( $transients as $key => $value ) {
				// $entry = $wpdb->get_col(
				// 	$wpdb->prepare(
				// 		"SELECT option_value
				// 		FROM `wp_options`
				// 		WHERE option_name = %s",
				// 		$value
				// 	)
				// );
				// $transient = isset( $entry[0] ) ? $entry[0] : '';

				$transient = get_transient( $value )
				?>
				<tr>
					<th><label for="contact"><?php echo esc_html( $key ); ?></label></th>
					<td>
						<input type="text" class="regular-text" value="<?php echo esc_attr( $transient ); ?>" readonly />
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
	}
}
