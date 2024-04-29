<?php
/**
 * Feature
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Utilities/Features
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Utilities\Features;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Feature
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Feature {

	/**
	 * Features
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $features = [
		'redtail_crm',
		'wealthbox_crm',
		'salesforce_crm',
		'xlr8_crm',
		'premier_features_deluxe',
		'flowcharts',
		'link_share',
		'activities',
		'share_link_options',
		'premier_features_deluxe',
		'individual_advanced_back_page',
		'group_advanced_back_page',
		'checklists_v_two',
	];

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		if ( function_exists( 'fp_enqueue_scripts' ) ) {
			fp_enqueue_scripts(
				[
					'file_path'        => 'src/js/index.js',
					'handle'           => 'main',
					'localized'        => $this->features_active_status(),
					'localized_handle' => 'fpSettings',
				]
			);
		}
	}

	/**
	 * Features Active Status
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function features_active_status() {
		$status = [];

		foreach ( $this->features as $feature ) {
			$status[ $feature ] = $this->is_active( $feature );
		}

		$status['isShareLink'] = fp_is_share_link();

		return $status;
	}

	/**
	 * Is Active
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $feature The feature to check if is active.
	 *
	 * @return bool
	 */
	public function is_active( string $feature ): bool {
		if ( empty( $feature ) ) {
			return false;
		}

		$feature     = 'feature_activated_' . $feature;
		$get_feature = get_field( $feature, 'option' );

		if ( ! $get_feature ) {
			return false;
		}

		$groups = function_exists( 'acf_get_field_groups' ) ? acf_get_field_groups() : [];

		if ( empty( $groups ) ) {
			return false;
		}

		$theme_settings = [];

		foreach ( $groups as $group ) {
			if ( $group['title'] === 'Theme Settings' ) {
				$theme_settings = $group;
			}
		}

		$theme_settings_id = isset( $theme_settings['ID'] ) ? $theme_settings['ID'] : '';

		if ( empty( $theme_settings_id ) ) {
			return false;
		}

		$fields        = function_exists( 'acf_get_fields' ) ? acf_get_fields( $theme_settings_id ) : [];
		$default_value = '';

		if ( empty( $fields ) ) {
			return false;
		}

		foreach ( $fields as $field ) {
			if ( $field['name'] === $feature ) {
				$default_value = ! empty( $field['default_value'] ) ? $field['default_value'] : false;
			}
		}

		$is_activated = function_exists( 'get_field' ) ? get_field( $feature, 'option' ) : false;
		$activated    = $is_activated ? $is_activated : $default_value;

		return (bool) $activated;
	}
}
