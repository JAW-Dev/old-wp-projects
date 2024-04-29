<?php
/**
 * Personalizations
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Pages/Personalizations
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Pages\Personalizations;

use FpAccountSettings\Includes\Classes\TemplateParts\Settings\SettingsAbstract;
use FpAccountSettings\Includes\Classes\TemplateParts\Tabs\TabsSection;
use FpAccountSettings\Includes\Classes\Conditionals;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Personalizations
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Personalizations extends SettingsAbstract {

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
	 * Tabs Settings
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function tabs_settings() {

		$scheme = [
			'slug' => 'personalizations', // Make sure the matches the namespace.
			'tabs' => [
				[
					'title'  => 'White Labeling',
					'access' => true,
				],
				[
					'title'  => 'Share Link',
					'access' => Conditionals::is_premier_group_member() || Conditionals::is_premier_group_owner() || Conditionals::is_premier_member() || current_user_can( 'administrator' ),
				],
			],
		];

		$subsections = function_exists( 'get_field' ) ? get_field( 'subsections_' . $scheme['slug'], 'option' ) : array();

		foreach ( $scheme['tabs'] as $key => $value ) {
			$title        = strtolower( str_replace( ' ', '_', $scheme['tabs'][ $key ]['title'] ) );
			$section_slug = $scheme['slug'] . '_' . $title;

			if ( empty( $subsections ) ) {
				continue;
			}

			foreach ( $subsections as $sub_key => $sub_value ) {
				if ( ! empty( $sub_key ) ) {
					$scheme['tabs'][ $key ][ $sub_key ] = $sub_value;
				}
			}

			$scheme['tabs'][ $key ]['section_slug'] = $section_slug;
		}

		return $scheme;
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
		$slug         = strtolower( str_replace( ' ', '-', $this->args['title'] ?? '' ) );
		$tab_settings = $this->tabs_settings();
		$tab_slug     = $tab_settings['slug'];
		?>
		<section class="body-section body-section__<?php echo esc_attr( $slug ); ?>">
			<header class="body-section__header">
				<h2 id="body-section__title"><?php echo esc_html( $this->args['title'] ); ?></h2>
				<?php
				if ( ! empty( $this->args['blurb'] ) ) :
					if ( Conditionals::is_deluxe_group_member() || Conditionals::is_premier_group_member() ) :
						?>
						<div class="body-section__header-blurb">
						<?php
					else :
						?>
						<div class="body-section__header-blurb">
							<?php
							$general    = ! empty( $this->args['blurb'][ 'heading_general_blurb_' . $tab_slug ] ) ? $this->args['blurb'][ 'heading_general_blurb_' . $tab_slug ] : '';
							$essentials = ! empty( $this->args['blurb'][ 'heading_essentials_blurb_' . $tab_slug ] ) ? $this->args['blurb'][ 'heading_essentials_blurb_' . $tab_slug ] : $general;
							$deluxe     = ! empty( $this->args['blurb'][ 'heading_deluxe_blurb_' . $tab_slug ] ) ? $this->args['blurb'][ 'heading_deluxe_blurb_' . $tab_slug ] : $general;
							$premier    = ! empty( $this->args['blurb'][ 'heading_premier_blurb_' . $tab_slug ] ) ? $this->args['blurb'][ 'heading_premier_blurb_' . $tab_slug ] : $general;

							if ( Conditionals::is_essentials_member() ) {
								echo wp_kses_post( $essentials );
							} elseif ( Conditionals::is_deluxe_member() ) {
								echo wp_kses_post( $deluxe );
							} elseif ( Conditionals::is_premier_member() ) {
								echo wp_kses_post( $premier );
							} else {
								echo wp_kses_post( $general );
							}
							?>
						</div>
						<?php
					endif;
				endif;
				?>
				<div id="form-notification-area-wrap"><ul></ul></div>
				<div id="pdf-generator-save-successful" class="success-notice"> Settings saved successfully.</div>
			</header>
			<?php new TabsSection( $this->tabs_settings() ); ?>
		</section>
		<?php
	}
}
