<?php
/**
 * Integrations.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Pages/Integrations
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Pages\Integrations;

use FpAccountSettings\Includes\Classes\TemplateParts\Settings\SettingsAbstract;
use FP_Core\Crms\Utilities as FPCoreCrmsUtillites;
use FP_Core\Utilities as FPCoreUtilities;
use FpAccountSettings\Includes\Classes\Conditionals;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Integrations.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Integrations extends SettingsAbstract {

	/**
	 * Icons Path
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $icons_path = '';

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @param array $args The settings sections args.
	 *
	 * @return void
	 */
	public function __construct( $args = [] ) {
		parent::__construct( $args );
	}

	/**
	 * Render Content
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function render_content() {
		$title = $this->args['title'] ?? '';
		$blurb = $this->args['blurb'] ?? '';
		$slug  = strtolower( str_replace( ' ', '-', $title ) );

		?>
		<section class="body-section body-section__<?php echo esc_attr( $slug ); ?>">
			<header class="body-section__header">
				<h2 id="body-section__title"><?php echo esc_html( $title ); ?></h2>
				<?php
				if ( ! empty( $blurb ) ) :
					?>
					<div class="body-section__header-blurb">
						<?php
						if ( Conditionals::is_premier_member() || Conditionals::is_enterprise_premier() || current_user_can( 'administrator' ) ) {
							echo wp_kses_post( $blurb['heading_premier_blurb_integrations'] );
						} else {
							echo wp_kses_post( $blurb['heading_essentials_deluxe_blurb_integrations'] );
						}
						?>
					</div>
					<?php
				endif;
				?>
			</header>
			<?php $this->get_integrations(); ?>
		</section>
		<?php
	}

	/**
	 * Get Itegrations
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function get_integrations() {
		$crms        = FPCoreCrmsUtillites::get_crms();
		$subsections = function_exists( 'get_field' ) ? get_field( 'subsections_integrations', 'option' ) : array();
		$blurbs      = ! is_null( $subsections ) ? $subsections['sections_itegrations_itegrations']['itegrations_blurbs_support_itegrations'] : '';
		$blurb_count = 0;

		foreach ( $crms as $crm ) {
			$slug = $crm['slug'];

			if ( ! fp_is_feature_active( $slug . '_crm' ) ) {
				continue;
			}

			$can_save = Conditionals::is_premier_member() || Conditionals::is_premier_group_member() || current_user_can( 'administrator' );

			$name          = $crm['name'];
			$is_active     = $crm['is_active'];
			$tokens        = $crm['tokens'];
			$status        = $this->get_status_label( $is_active, $tokens );
			$button_text   = $is_active ? 'Active' : 'Inactive';
			$message_class = $is_active ? 'active' : 'inactive';
			$active_class  = $is_active ? 'pill-button__red' : 'pill-button__grey';
			$icon          = fp_get_svg( FP_ACCOUNT_SETTINGS_DIR_PATH . 'assets/images/icons/', 'gear' );

			$settings_url     = ! $can_save ? 'href=javascript:void(0)' : 'href=' . add_query_arg( 'settings', $slug, home_url( 'your-membership/integrations' ) );
			$activation_url   = $this->get_activation_url( true, $slug );
			$deactivation_url = $this->get_activation_url( false, $slug );
			$disabled         = ! $can_save ? ' disabled="disabled"' : '';

			?>
			<div class="white-content-box app-itegration">
				<div class="white-content-box__inner">
					<div class="white-content-box__left">
						<h4 class="app-itegration__card-heading"><?php echo esc_html( $name ); ?></h4>
						<div id="<?php echo esc_attr( $slug ) . '_status'; ?>" class="app-itegration__status <?php echo esc_attr( $message_class ); ?>"><?php echo wp_kses_post( $status ); ?></div>
						<?php if ( ! empty( $blurbs[ $blurb_count ]['blurb'] ) ) : ?>
							<div class="app-itegration__blurb"><?php echo wp_kses_post( $blurbs[ $blurb_count ]['blurb'] ); ?></div>
						<?php endif; ?>
					</div>
					<div class="white-content-box__right">
						<input
							type="submit"
							class="integration-activation-toggle pill-button <?php echo esc_attr( $active_class ); ?>"
							value="<?php echo esc_attr( $button_text ); ?>"
							data-action="activate_crm"
							data-slug="<?php echo esc_attr( $slug ); ?>"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'app-activate-nonce' ) ); ?>"
							data-active="<?php echo esc_attr( intval( $is_active ) ); ?>"
							data-activation-url="<?php echo esc_attr( $activation_url ); ?>"
							data-deactivation-url="<?php echo esc_attr( $deactivation_url ); ?>"
							<?php echo esc_html( $disabled ); ?>
						></input>
						<a class="integration-settings small-svg-icon"<?php echo esc_attr( $settings_url ); ?>><?php echo wp_kses( $icon, fp_svg_kses() ); ?></a>
					</div>
					<?php
					if ( ! Conditionals::is_premier_member_or_owner() && ! current_user_can( 'administrator' ) ) {
						?>
						<div class="white-content-box__disabled"></div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
			$blurb_count++;
		}
	}

	/**
	 * Get Status Label
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param boolean $active If is active.
	 * @param string  $tokens The crm tokens.
	 *
	 * @return string
	 */
	public static function get_status_label( $active, $tokens ) {
		if ( $active && empty( $tokens ) ) {
			return 'Active but not connected, see Settings';
		}

		return '';
	}

	private function get_activation_url( bool $active, string $slug ) {
		$params = array(
			'action' => 'activate_crm',
			'slug'   => $slug,
			'active' => intval( $active ),
			'nonce'  => wp_create_nonce( 'app-activate-nonce' ),
		);

		return add_query_arg( $params, admin_url( 'admin-ajax.php' ) );
	}
}
