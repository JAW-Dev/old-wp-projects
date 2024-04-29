<?php
/**
 * Redtail User Integration
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms\Redtail;

use FP_Core\Crms\UserIntegrationAbstract;
use FP_Core\Crms;

class RedtailUserIntegration extends UserIntegrationAbstract {
	public function __construct() {
		parent::__construct( 'redtail', 'Redtail' );

		$user_id = get_current_user_id();

		if ( ! $user_id || ! $this->is_active( $user_id ) ) {
			return;
		}
	}

	public function init() {}

	public function get_settings_page( int $user_id ): string {
		ob_start();
		?>
		<div style="display: flex; flex-direction: column">
			<h3 style="margin-bottom: 2rem;">Redtail Settings</h3>

			<?php if ( Crms\Utilities::get_crm_token( $user_id ) ) : ?>
				<p>You're connected with Redtail.</p>
			<?php else : ?>
				<p>Go to Redtail to connect with fpPathfinder.</p>
			<?php endif; ?>

			<p>To setup or change your integration with Redtail please go to Redtail's integration menu.</p>
			<p><a href="https://help.redtailtechnology.com/hc/en-us/articles/360044438534">Detailed Instructions can be found here</a></p>
		</div>
		<?php
		return  ob_get_clean();
	}

	static public function is_dev_mode(): bool {
		$is_dev_mode_option = 'development' === get_field( 'redtail_integration_mode', 'options' );

		return $is_dev_mode_option;
	}
}
