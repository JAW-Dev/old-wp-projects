<?php
/**
 * ACF.
 *
 * @package    Kitces_Star_Rating
 * @subpackage Kitces_Star_Rating/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) Plugin_Boilerplate_Date, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace KitcesStarRating\Includes\Classes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( __NAMESPACE__ . '\\ACF' ) ) {

	/**
	 * ACF.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class ACF {

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
			$this->load_acf_json();
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
			add_action( 'acf/init', array( $this, 'options_page' ) );
			add_filter( 'acf/load_field/name=kitces_start_ratings_post_version', array( $this, 'populate_select' ) );
		}

		/**
		 * Load ACF JSON
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function load_acf_json() {
			if ( in_array( 'advanced-custom-fields-pro/acf.php', get_option( 'active_plugins' ), true ) ) {
				add_filter(
					'acf/settings/load_json',
					function( $paths ) {
						$paths[] = KITCES_STAR_RAITING_DIR_PATH . '/acf-json';
						return $paths;
					}
				);
			}
		}

		/**
		 * Options Page
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function options_page() {

			if ( function_exists( 'acf_add_options_page' ) ) {
				acf_add_options_sub_page(
					array(
						'page_title'  => __( 'Test Star Ratings Settings', 'kitces_star_rating' ),
						'menu_title'  => 'Settings',
						'parent_slug' => 'star-ratings-test',
					)
				);
			}
		}

		/**
		 * Populate Select
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array $field The ACF field array.
		 *
		 * @return array
		 */
		public function populate_select( $field ) {
			$field['choices'] = array();
			$settings_options = get_field( 'kitces_star_ratings', 'option' );

			if ( $settings_options ) {
				foreach ( $settings_options as $settings_option ) {
					$label = $settings_option['version_name'];
					$value = $label ? strtolower( str_replace( ' ', '-', $label ) ) : '';

					$field['choices'][ $value ] = $label;
				}
			}

			return $field;
		}
	}
}
