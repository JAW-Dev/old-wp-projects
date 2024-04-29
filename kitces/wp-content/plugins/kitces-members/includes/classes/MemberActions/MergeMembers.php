<?php
/**
 * Merge Members
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/MemberActions
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\MemberActions;

use Kitces_Members\Includes\Classes\MemberActions\Merge;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Merge Members
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class MergeMembers {
	/**
	 * Merge Modules
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $merge_modules = [];
	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'show_user_profile', array( $this, 'merge_members_view' ) );
		add_action( 'edit_user_profile', array( $this, 'merge_members_view' ) );
	}

	/**
	 * Merge Members View
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param WP_User $user The user object.
	 *
	 * @return void
	 */
	public function merge_members_view( $user ) {
		$nonce = wp_create_nonce( 'merge-member' );

		?>
		<h3>Merge Member</h3>

		<table class="form-table merge_member">
			<tr>
				<div id="member-merge-message" class="notice notice-error inline" style="display: none"></div>
			</tr>
			<tr>
				<th scope="row">Member Email</th>
				<td>
					<input type="text" id="merge-member-email" class="regular-text" value="">
					<br/><span class="description">Add the email you want to be merged into this current account.</span>
				</td>
			</tr>
			<tr>
				<th>
					<a
						href=""
						id="merge-member-button"
						class="button"
						data-nonce="<?php echo esc_attr( $nonce ); ?>"
						data-userid="<?php echo esc_attr( $user->ID ); ?>"
						data-userEmail="<?php echo esc_attr( $user->user_email ); ?>"
						>
						<?php esc_html_e( 'Merge Member', 'fp-core' ); ?>
					</a>
					<span id="merge-member-indicator" style="display: inline-block; line-height: 2; height: 30px; margin-left: 16px;"></span>
				</th>
			</tr>
		</table>
		<?php
	}

	/**
	 * Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $hook The admin page hook.
	 *
	 * @return void
	 */
	public function scripts( $hook ) {
		if ( $hook !== 'user-edit.php' ) {
			return;
		}

		$filename = 'src/js/merge-member.js';
		$file     = KICTES_MEMBERS_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';
		$handle   = 'kitces-merge-member';

		wp_register_script( $handle, KICTES_MEMBERS_DIR_URL . $filename, array(), $version, true );
		wp_enqueue_script( $handle );
	}

	/**
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $args The arguments. Destinaltion and Source User IDs.
	 *
	 * @return void
	 */
	public function init( $args ) {
		$source_user      = get_userdata( $args[0] );
		$destination_user = get_userdata( $args[1] );

		$this->merge_modules['posts']        = ( new Merge\Posts() )->set_source_wp_user( $source_user )->set_destination_wp_user( $destination_user );
		$this->merge_modules['usermeta']     = ( new Merge\UserMeta() )->set_source_wp_user( $source_user )->set_destination_wp_user( $destination_user );
		$this->merge_modules['gravityforms'] = ( new Merge\GravityForms() )->set_source_wp_user( $source_user )->set_destination_wp_user( $destination_user );

		foreach ( $this->merge_modules as $merge_module ) {
			$merge_module->merge();
		}

		wp_delete_user( $source_user->ID );
	}
}
