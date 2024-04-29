<?php
/**
 * Error Messages
 *
 * @package    FP_Core/
 * @subpackage FP_Core/InteractiveLists/Templates/ShareLink
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Templates\ShareLink;

use FP_Core\InteractiveLists\Utilities\Page;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Error Messages
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Messages {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Error
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function error() {
		if ( fp_is_share_link() && ! isset( $_POST['email_note_button'] ) ) :
			$valid_share_key = Page::is_share_key_valid();

			if ( ! $valid_share_key ) :
				?>
				<div class="interactive-resource__share-message">
					<p>Your shared link is not working. Please contact your advisor!</p>
				</div>
				<?php
				return true;
			endif;

			if ( fp_is_feature_active( 'checklists_v_two' ) ) {
				return;
			}

			$is_completed = Page::is_resourece_share_link_completed();
			$is_expired   = Page::is_share_link_expired();

			if ( $is_completed ) :
				?>
				<div class="interactive-resource__share-message">
					<p>Your shared link has been completed. Please contact your advisor if this is incorrect!</p>
				</div>
				<?php
				return true;
			endif;

			if ( $is_expired ) :
				?>
				<div class="interactive-resource__share-message">
					<p>Your shared link has expired. Please contact your advisor!</p>
				</div>
				<?php
				return true;
			endif;
		endif;

		return false;
	}

	/**
	 * Add error class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function add_error_class( $classes ) {
		$classes[] = 'share-link-error';
				return $classes;
	}
}
