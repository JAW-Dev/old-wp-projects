<?php
/**
 * Password Link
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/Login
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\Login;

use Carbon\Carbon;
use Kitces_Members\Includes\Classes\SingletonAbstract;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Password Link
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class PasswordLink extends SingletonAbstract {

	/**
	 * New Member ID
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var int
	 */
	protected $user_id = 0;

	/**
	 * New Member Link Key
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $security_key = '';

	/**
	 * Password Reset Page ID
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var int
	 */
	protected $password_reset_page_id = 0;


	public function init() {
        $this->load_state();
		$this->hooks();
	}

    public function load_state() {
        $this->security_key = isset( $_GET['nmb_key'] ) ? sanitize_text_field( wp_unslash( $_GET['nmb_key'] ) ) : '';
        $this->password_reset_page_id = (int) get_option( 'options_kitces_new_member_reset_link_page', 0 );
        $this->user_id = isset( $_GET['nmb'] ) ? (int) sanitize_text_field( wp_unslash( $_GET['nmb'] ) ) : 0;
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
		add_action( 'pre_get_posts', array( $this, 'maybe_login_member' ) );
	}

	/**
	 * Maybe Login Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_login_member() {
        if ( ! $this->user_id ) {
            return;
        }

        if ( ! $this->is_new_member_reset_page() ) {
            return;
        }

        if ( $this->key_exists_and_is_valid() ) {
            return;
        }

        if ( is_user_logged_in() ) {
            return;
        }

        $user = get_user_by( 'ID', $this->user_id );

        if ( empty( $user ) ) {
            return;
        }

        wp_clear_auth_cookie();
        wp_set_current_user( $this->user_id );
        wp_set_auth_cookie( $this->user_id );

        $url = add_query_arg(
            array(
                'nmb_key' => $this->security_key,
                'nmb'     => $this->user_id,
            ),
            get_the_permalink( $this->password_reset_page_id )
        );

        wp_safe_redirect( $url );
        exit();
	}

	/**
	 * Is New Member Key Expired
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return bool
	 */
	public function key_exists_and_is_valid(): bool {
        if ( ! is_user_logged_in() ) {
            return false;
        }

        if ( empty( $this->security_key ) ) {
            return false;
        }

        $expiration_date = get_user_meta( $this->user_id, 'member_activation_key_expiry', true );

        $expiry       = Carbon::createFromTimestamp( $expiration_date );
        $current_time = Carbon::now();

        $expired = $expiry->lt( $current_time );

        $maybe_saved_key = get_user_meta( $this->user_id, 'member_activation_key', true );
        $key_matches =  $maybe_saved_key === $this->security_key;

        return ! $expired && $key_matches;
	}

	/**
	 * Is New Member Password Reset Page
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return bool
	 */
	public function is_new_member_reset_page(): bool {
		if ( ! $this->password_reset_page_id ) {
			return false;
		}

		if ( is_page( $this->password_reset_page_id ) ) {
			return true;
		}

		return false;
	}
}
