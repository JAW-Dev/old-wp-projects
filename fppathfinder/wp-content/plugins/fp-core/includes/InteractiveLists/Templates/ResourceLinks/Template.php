<?php
/**
 * Template
 *
 * @package    FP_Core/
 * @subpackage FP_Core/InteractiveLists/Templates/ResourceLinks
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Templates\ResourceLinks;

use FP_Core\InteractiveLists\Utilities\Page;
use FP_Core\InteractiveLists\Utilities\CRM;
use FP_Core\Member;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Template
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Template {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
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
		add_action( 'interactive_resource_after_client_name_field', array( $this, 'render' ), 10, 4 );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * Render
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string  $client_name    The client name.
	 * @param int     $crm_contact_id The CRM client ID.
	 * @param boolean $is_hidden      If hidden.
	 * @param string  $account_id     The XLR8 account ID.
	 *
	 * @return void
	 */
	public function render( $client_name, $crm_contact_id, $is_hidden, $account_id ) {
		// Don't run on flowcharts
		if ( Page::is_interactive_resource( 'flowchart' ) ) {
			return;
		}

		$member = new Member( get_current_user_id() );

		if ( rcp_get_subscription( get_current_user_id() ) || ( ! empty( $member ) && $member->is_group_member() ) ) {
			?>
			<div class="interactive-resource__client-links" style="margin-left: auto">
				<?php do_action( 'interactive_resource_client_links', $client_name, $crm_contact_id, $account_id ); ?>
			</div>
			<?php
		} else {
			$this->demo_share_link();
			return;
		}
	}

	/**
	 * Demo Share Link
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function demo_share_link() {
		?>
		<div class="interactive-resource__client-links">
			<button id="resource-share-link-button-demo" class="resource-share-link__button" >Share Link</button>

			<div id="resource-share-link-modal-demo" style="display: none">
				<div class="resource-share-link-modal-demo__wrap">
					<div class="resource-share-link-modal-demo__body">
						<p><strong>With “Share A Link” Premier members can:</strong></p>
						<ul>
							<li>Ask clients to complete a checklist in advance of any meeting</li>
							<li>Identify topics you need to research before a client meeting</li>
							<li>Have more focused meetings and reduce meeting frequency</li>
							<li>Streamline your meeting preparation</li>
						</ul>
					</div>
					<div class="resource-share-link-modal-demo__icon">
						<img src="<?php echo FP_CORE_DIR_URL . 'assets/images/share-icon.png'; ?>"/>
					</div>
				</div>
				<p class="button red-button resource-share-link-modal-demo__button">
					<a href="<?php echo home_url( 'become-a-member' ); ?>">
						Learn More
					</a>
				</p>
			</div>
		</div>
		<?php
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
		if ( ! Page::is_interactive_resource() ) {
			return;
		}

		$file    = FP_CORE_DIR_PATH . 'src/css/resource-links.css';
		$version = file_exists( $file ) ? filemtime( $file ) : '1.0.0';

		wp_register_style( 'fp-resource-links', FP_CORE_DIR_URL . 'src/css/resource-links.css', array(), $version, 'all' );
		wp_enqueue_style( 'fp-resource-links' );
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
		if ( ! Page::is_interactive_resource() ) {
			return;
		}

		$file    = FP_CORE_DIR_PATH . 'src/js/resource-links.js';
		$version = file_exists( $file ) ? filemtime( $file ) : '1.1.0';

		wp_register_script( 'fp-resource-links', FP_CORE_DIR_URL . 'src/js/resource-links.js', array(), $version, true );
		wp_enqueue_script( 'fp-resource-links' );
		$pdf_settings = null;
		$has_crm      = null;

		if ( fp_is_share_link() ) {
			$advisor_id   = isset( $_GET['a'] ) ? sanitize_text_field( wp_unslash( $_GET['a'] ) ) : '';
			$pdf_settings = get_user_meta( $advisor_id, 'pdf-generator-settings', true );
		}

		if ( CRM::has_active_crm( get_current_user_id() ) ) {
			$has_crm = true;
		}

		wp_localize_script(
			'fp-resource-links',
			'fpResourceLinksData',
			array(
				'pdfSettings' => $pdf_settings,
				'hasCrm'      => $has_crm,
				'postId'      => get_the_ID(),
			)
		);
	}
}
