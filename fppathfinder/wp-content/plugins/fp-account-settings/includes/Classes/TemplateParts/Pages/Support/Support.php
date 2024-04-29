<?php
/**
 * Support.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Pages/Support
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Pages\Support;

use FpAccountSettings\Includes\Classes\TemplateParts\Settings\SettingsAbstract;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Support.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Support extends SettingsAbstract {

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
				'name'             => 'Support page',
				'membership_level' => 2,
			)
		);

		if ( ! $access ) {
			return;
		}

		$slug    = strtolower( str_replace( ' ', '-', $this->args['title'] ) );
		$section = function_exists( 'get_field' ) ? get_field( 'subsections_support', 'option' ) : array();

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
			<?php $this->faq_section( $section ); ?>
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
	public function faq_section( $section ) {
		$accordions    = function_exists( 'get_field' ) ? get_field( 'your_membership_page_frequently_asked_questions', 'option' ) : [];
		$faqs          = ! empty( $accordions ) ? $accordions['accordion_repeater'] : [];
		$slug          = strtolower( str_replace( ' ', '-', $this->args['title'] ) );
		$section_title = $section[ 'sections_' . $slug . '_faqs' ][ 'faqs_header_title_' . $slug . '_faqs' ] ?? '';
		$section_blurb = $section[ 'sections_' . $slug . '_faqs' ][ 'faqs_header_blurb_' . $slug . '_faqs' ] ?? '';

		?>
		<section class="faqs">
			<section class="section-style">
				<?php
				if ( ! empty( $section_title ) ) : ?>
					<h3><?php echo esc_html( $section_title ); ?></h3>
					<?php
				endif;
				if ( ! empty( $section_blurb ) ) :
					echo wp_kses_post( $section_blurb );
				endif;
				?>
			</section>
			<?php
			if ( ! empty( $faqs ) ) :
				foreach ( $faqs as $faq ) :
					?>
					<div class="white-content-box accordian-wrap">
						<div class="faqs__header accordian-header">
							<h4 class="faqs__heading"><?php echo esc_html( $faq['ac_row_title'] ); ?></h4>
							<div class="faqs__chevron toggle-chevron"></div>
						</div>
						<div class="faqs__body accordian-body">
							<?php echo wp_kses_post( $faq['ac_row_content'] ); ?>
						</div>
					</div>
					<?php
				endforeach;
			endif;
			?>
		</section>
		<?php
	}
}
