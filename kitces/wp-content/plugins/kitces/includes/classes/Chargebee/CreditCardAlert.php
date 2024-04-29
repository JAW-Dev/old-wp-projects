<?php
/**
 * Credit Card Alert.
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/Chargebee
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\Chargebee;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Credit Card Alert.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class CreditCardAlert {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
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
		add_action( 'wp_footer', array( $this, 'render' ) );
	}

	/**
	 * Get Page ID
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return int
	 */
	public function get_page_id() {
		$scheme  = is_ssl() ? 'http' : 'https';
		$request = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : ''; // phpcs:ignore
		$url     = "$scheme://$_SERVER[HTTP_HOST]$request"; // phpcs:ignore

		return url_to_postid( $url );
	}

	/**
	 * Is Members Page
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public function is_member_page() {
		$current_page_id = $this->get_page_id();
		$page            = get_post( $current_page_id );
		$page_id         = ! empty( $page ) ? $page->ID : '';
		$page_parent     = ! empty( $page ) ? $page->post_parent : '';
		$is_member_page  = ! empty( $page ) ? $page->post_name === 'member' : false;
		$is_ancestor     = ! empty( $page_parent ) ? in_array( $page_parent, get_post_ancestors( $page_id ), true ) : false;

		if ( $is_member_page || $is_ancestor ) {
			return true;
		}

		return false;
	}

	/**
	 * Render
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function render() {

		$card                   = new CreditCard();
		$is_member_page         = $this->is_member_page();
		$has_card_info          = $card->has_card_info();
		$is_card_to_expire      = $card->is_card_to_expire();
		$is_chargebee_member    = $card->is_chargebee_member();
		$is_active_subscription = $card->is_active_subscription();

		if ( $is_chargebee_member && $is_member_page && $has_card_info && $is_active_subscription && $is_card_to_expire ) {
			$expiry_date    = strtotime( $card->get_card_expiry_date() );
			$formatted_date = date( 'm-Y' );
			$portal_url     = $card->get_portal_access_url();
			?>
			<div id="kitces-cc-alert-wrap" class="kitces-cc-alert__wrap">
				<div id="kitces-cc-alert" class="kitces-cc-alert" style="display: none">
					<p>The credit card we have on file for your subscription is about to expire on <?php echo esc_html( $formatted_date ); ?>. Please update your credit card information if you wish to continue your Kitces subscription.</p>
					<div class="kitces-cc-alert__buttons">
						<a href="<?php echo esc_url( $portal_url ); ?>" class="kitces-cc-alert__update button" target="_blank">Update Now</a>
						<a id="kitces-cc-alert-dismiss" href="#" class="kitces-cc-alert__dismiss">Dismiss</a>
					</div>
				</div>
			</div>
			<?php
		}

		?>
		<?php
	}
}
