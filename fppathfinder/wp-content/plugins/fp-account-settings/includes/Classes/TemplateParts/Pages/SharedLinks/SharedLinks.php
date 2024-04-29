<?php
/**
 * Shared Links.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Pages/SharedLinks
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Pages\SharedLinks;

use FpAccountSettings\Includes\Classes\TemplateParts\Settings\SettingsAbstract;
use FpAccountSettings\Includes\Classes\Conditionals;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Shared Links.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class SharedLinks extends SettingsAbstract {

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
		$access = fp_get_member_access(
			array(
				'name'             => 'Shared Links page',
				'membership_level' => 2,
			)
		);

		if ( ! $access ) {
			return;
		}

		$slug = strtolower( str_replace( ' ', '-', $this->args['title'] ) );

		?>
		<section class="body-section body-section__<?php echo esc_attr( $slug ); ?>">
			<header class="body-section__header">
				<h2 id="body-section__title"><?php echo esc_html( $this->args['title'] ); ?></h2>
				<?php
				if ( ! empty( $this->args['blurb'] ) ) :
					?>
					<div class="body-section__header-blurb">
						<?php echo wp_kses_post( $this->args['blurb'] ); ?>
					</div>
					<?php
				endif;
				?>
			</header>
			<?php $this->shared_links_section(); ?>
		</section>
		<?php
	}

	/**
	 * FAQ Section
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function shared_links_section() {
		$advisor_links = get_user_meta( get_current_user_id(), 'fp_advisor_share_links', true );
		$nonce         = wp_create_nonce( 'remove-shared-links' );

		if ( empty( $advisor_links ) ) {
			return;
		}

		foreach ( $advisor_links as $advisor_link ) {
			$date  = ! empty( $advisor_link['created'] ) ? $advisor_link['created'] : '';
			$title = get_the_title( $advisor_link['resource_id'] );

			$url_query_parmas = [
				'sh' => $advisor_link['share_key'],
				'r'  => $advisor_link['resource_id'],
				'a'  => $advisor_link['advisor_id'],
			];

			$url = add_query_arg( $url_query_parmas, get_the_permalink( $advisor_link['resource_id'] ) );

			?>
			<div class="white-content-box app-itegration">
				<div class="white-content-box__inner">
					<div class="white-content-box__left">
						<h4><?php echo esc_html( $title ); ?></h4>
						<div><?php echo esc_html( date( 'm-d-Y', strtotime( $date ) ) ); ?></div>
						<div><?php echo esc_html( $url ); ?></div>
					</div>
					<div class="white-content-box__right">
						<div
							class="share_links_remove_button white-content-box__icon"
							data-nonce="<?php echo esc_attr( $nonce ); ?>"
							data-sharekey="<?php echo esc_attr( $advisor_link['share_key'] ); ?>"
						>
						<?php echo fp_get_svg( FP_ACCOUNT_SETTINGS_DIR_PATH . 'assets/images/icons/', 'trash' ); // phpcs:ignore ?>
					</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
}
