<?php
/**
 * Wealthbox User Integration
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms\Wealthbox;

use FP_Core\Crms\UserIntegrationAbstract;
use FP_Core\Crms\Wealthbox\OAuthController;

class WealthboxUserIntegration extends UserIntegrationAbstract {
	public function __construct() {
		parent::__construct( 'wealthbox', 'Wealthbox CRM' );
	}

	public function init() {
		OAuthController::init();

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

		$token = OAuthController::get_user_token( $user_id );

		ob_start();
		?>
		<div style="display: flex; flex-direction: column">
			<h3 style="margin-bottom: 2rem;">Wealthbox CRM Settings</h3>
			<?php if ( $token ) : ?>
				<p>You're connected with Wealthbox.</p>
			<?php else : ?>
				<p style="margin-bottom: 2.5rem;">The Wealthbox CRM integration requires two steps. First, click the blue "Connect and Authorize" button below. Second, visit Wealthbox to connect fpPathfinder by <a href="https://app.crmworkspace.com/settings/fp_pathfinder" target="_blank">clicking here</a> or by going to Applications in Wealthbox.</p>
				<span class="button">
					<a href="<?php echo OAuthController::get_authorize_url(); ?>">Connect and Authorize</a>
				</span>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
