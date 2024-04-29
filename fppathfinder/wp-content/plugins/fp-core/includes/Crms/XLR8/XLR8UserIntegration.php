<?php
/**
 * XLR8 User Integration
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms/XLR8
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms\XLR8;

use FP_Core\Crms\UserIntegrationAbstract;
use FP_Core\Crms\XLR8\OAuthController;

class XLR8UserIntegration extends UserIntegrationAbstract {
	/**
	 * OAuthController
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $oauth_controller;

	public function __construct() {
		parent::__construct( 'xlr8', 'XLR8' );

		$this->oauth_controller = new OAuthController();
	}

	public function init() {
		$this->oauth_controller->init();

		$user_id = get_current_user_id();

		if ( ! $user_id || ! $this->is_active( $user_id ) ) {
			return;
		}
	}

	public function get_settings_page( int $user_id ): string {
		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return '';
		}

		$token          = get_user_meta( $user_id, 'xlr8_tokens', true );
		$authorized_url = $this->oauth_controller->get_authorize_url();

		ob_start();
		?>
		<div style="display: flex; flex-direction: column">
			<h3 style="margin-bottom: 2rem;">XLR8 Settings</h3>
			<?php if ( $token ) : ?>
				<p>You're connected with XLR8.</p>
			<?php else : ?>
				<p style="margin-bottom: 2.5rem;">The XLR8 Salesforce CRM integration requires two steps. Click the blue "Connect and Authorize" button below. Then if prompted, sign into your XLR8 Salesforce account. You will then be redirected back to fpPathfinder.</p>
				<span class="button">
					<a href="<?php echo esc_url( $authorized_url ); ?>">Connect and Authorize</a>
				</span>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	static public function is_dev_mode(): bool {
		$is_dev_mode_option = 'development' === get_field( 'salesforce_integration_mode', 'options' );

		return $is_dev_mode_option;
	}
}
