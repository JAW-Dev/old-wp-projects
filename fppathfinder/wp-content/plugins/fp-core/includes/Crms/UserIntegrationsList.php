<?php
/**
 * User Integration List
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms;

use FP_Core\InittableInterface;
use FP_Core\Crms\UserIntegrationAbstract;
use FP_Core\Utilities as CoreUtilities;

class UserIntegrationsList implements InittableInterface {
	/**
	 * @var UserIntegrationAbstract[]
	 */
	protected $integrations    = array();
	protected $ajax_action_tag = 'app_integration_activation';

	public function __construct( UserIntegrationAbstract ...$integrations ) {
		if ( empty( $integrations ) ) {
			return;
		}

		foreach ( $integrations as $integration ) {
			$this->integrations[ $integration->get_slug() ] = $integration;
		}
	}

	public function init() {
		add_shortcode( 'fppathfinder_user_integrations_list', array( $this, 'render' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
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
		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return '';
		}

		$settings_slug = $_REQUEST['settings'] ?? false;

		ob_start();
		?>
		<section class="user_itegrations">
			<section class="app-itegration__crms app-itegration__wrap" style="<?php echo $settings_slug ? '' : 'padding-top: 0'; ?>">
			<?php
			if ( $settings_slug ) {
				$this->show_settings_page( $settings_slug, $user_id );
			} else {
				$this->show_list( $user_id );
			}
			?>
			</section>
		</section>
		<?php
		if ( $settings_slug ) {
			$this->show_back_button();
		}

		return ob_get_clean();
	}

	private function show_back_button() {
		$integration_url = home_url( 'account-settings/#integrations' );
		?>
		<div style="display: flex; justify-content:center; margin-top: 7rem;"><span class="button"><a href="<?php echo esc_url( $integration_url ); ?>">Back to Integrations</a></span></div>
		<?php
	}

	private function show_settings_page( string $slug, int $user_id ) {
		$integration = $this->integrations[ $slug ] ?? false;

		if ( ! $integration ) {
			return;
		}

		echo $integration->get_settings_page( $user_id );
	}

	private function get_activation_url( bool $active, string $slug ) {
		$params = array(
			'action' => $this->ajax_action_tag,
			'slug'   => $slug,
			'active' => intval( $active ),
			'nonce'  => wp_create_nonce( 'app-activate-nonce' ),
		);

		return add_query_arg( $params, admin_url( 'admin-ajax.php' ) );
	}

	private function show_list( $user_id ) {
		$crms = Utilities::get_crms();

		foreach ( $crms as $crm ) {
			$slug = $crm['slug'];

			if ( ! fp_is_feature_active( $slug . '_crm' ) ) {
				continue;
			}

			$name             = $crm['name'];
			$is_active        = $crm['is_active'];
			$tokens           = $crm['tokens'];
			$status           = Utilities::get_status_label( $is_active, $tokens );
			$button_text      = $is_active ? 'Active' : 'Inactive';
			$settings_url     = add_query_arg( 'settings', $slug );
			$activation_url   = $this->get_activation_url( true, $slug );
			$deactivation_url = $this->get_activation_url( false, $slug );


			?>
			<div class="app-itegration__card">
				<h2 class="app-itegration__card-heading"><?php echo esc_html( $name ); ?></h2>
				<div class="app-itegration__card-status">
					Status: <strong id="<?php echo $slug . '_status'; ?>"><?php echo wp_kses_post( $status ); ?></strong>
				</div>
				<div class="app-itegration__card-body">
					<input
						type='submit'
						class="user-integration-activation-toggle button"
						value="<?php echo esc_attr( $button_text ); ?>"
						data-slug="<?php echo esc_attr( $slug ); ?>"
						data-is-active="<?php echo esc_attr( intval( $is_active ) ); ?>"
						data-activation-url="<?php echo esc_attr( $activation_url ); ?>"
						data-deactivation-url="<?php echo esc_attr( $deactivation_url ); ?>"
					></input>
					<span class="button">
						<a href="<?php echo esc_url( $settings_url ); ?>">Settings</a>
					</span>
				</div>
			</div>
			<?php
		}
	}

	public function enqueue() {
		global $post;

		if ( ! $post ) {
			return;
		}

		if ( ! has_shortcode( $post->post_content, 'fppathfinder_user_integrations_list' ) ) {
			return;
		}

		$this->scripts();
		$this->styles();
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
		$file    = FP_CORE_DIR_PATH . 'scr/js/integrations.js';
		$version = file_exists( $file ) ? filemtime( $file ) : '1.0.0';

		wp_register_script( 'fp_integrations', FP_CORE_DIR_URL . 'src/js/integrations.js', array(), $version, true );
		wp_enqueue_script( 'fp_integrations' );
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
		$file    = FP_CORE_DIR_PATH . 'src/css/integrations.css';
		$version = file_exists( $file ) ? filemtime( $file ) : '1.0.0';

		wp_register_style( 'fp_integrations', FP_CORE_DIR_URL . 'src/css/integrations.css', array(), $version, 'all' );
		wp_enqueue_style( 'fp_integrations' );
	}
}
