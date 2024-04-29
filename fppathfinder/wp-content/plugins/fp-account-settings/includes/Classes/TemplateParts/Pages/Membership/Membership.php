<?php
/**
 * Membership.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Pages/Membership
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Pages\Membership;

use FpAccountSettings\Includes\Classes\TemplateParts\Settings\SettingsAbstract;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Membership.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Membership extends SettingsAbstract {

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
			array( 'membership_level' => 2 )
		);

		if ( ! $access ) {
			return;
		}

		$slug = strtolower( str_replace( ' ', '-', $this->args['title'] ) );
		?>
		<section class="body-section body-section__<?php echo esc_attr( $slug ); ?>">
			<?php echo do_shortcode( '[subscription_details]' ); ?>
		</section>
		<?php
	}
}
