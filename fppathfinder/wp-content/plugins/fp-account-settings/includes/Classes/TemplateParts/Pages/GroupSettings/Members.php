<?php
/**
 * Members.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/GroupSettings
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Pages\GroupSettings;

use FpAccountSettings\Includes\Classes\TemplateParts\Tabs\TabsAbstract;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Members.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Members extends TabsAbstract {

	/**
	 * Args
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct( $slug, $args = [] ) {
		parent::__construct( $slug, $args );
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
		$slug = strtolower( str_replace( ' ', '-', $this->args['title'] ) );
		?>
		<section class="body-section body-section__<?php echo esc_attr( $slug ); ?>">
			<?php echo do_shortcode( '[rcp_group_dashboard]' ); ?>
		</section>
		<?php
	}
}
