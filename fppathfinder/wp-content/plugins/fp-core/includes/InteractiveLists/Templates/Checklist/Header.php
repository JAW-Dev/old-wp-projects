<?php
/**
 * Header
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Templates/Checklist
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Templates\Checklist;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Header
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Header {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Render
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $heading_text The link share heading text.
	 *
	 * @return void
	 */
	public function render( string $heading_text = '' ) {
		if ( fp_is_share_link() ) {
			?>
			<div class="interactive-resource__share-message">
				<p><?php echo wp_kses_post( $heading_text ); ?></p>
			</div>
			<?php
		}
	}
}
