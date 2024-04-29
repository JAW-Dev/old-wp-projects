<?php
/**
 * Main
 *
 * @package    FP_Core/
 * @subpackage FP_Core/InteractiveLists/Utilities
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Main
 *
 * @author John Geesey|Jason Witt
 * @since  1.0.0
 */
class Main {
	/**
	 * Interactive Lists Post Types
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $interactive_lists_post_types = [
		'checklist',
		'flowchart',
	];

	/**
	 * Inteactive Lists CRMs
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $interactive_lists_crms = [
		'redtail',
		'wealthbox',
		'salesforce',
		'xlr8',
	];

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
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init() {
		$inits = [
			'Admin_AJAX\Main',
			'Essentials_Trial_Membership\Main',
			'Constants',
		];

		foreach ( $inits as $init ) {
			$this->init_class( 'FP_Core\\' . str_replace( '\\', '\\', $init ) );
		}

		add_action( 'plugins_loaded', [ $this, 'after_plugins_init' ], 999 );

		// Load CRM integrations.
		require FP_CORE_DIR_PATH . '/includes/Crms/Init.php';
	}

	/**
	 * After plugins init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id    The user ID;
	 *
	 * @return void
	 */
	public function after_plugins_init() {
		$inits = [
			'EnqueueStyles',
			'EnqueueScripts',
			'ChecklistController',
			'LoginController',
			'MenuController',
			'EnterpriseEssentialsSettings',
			'RegistrationController',
			'FacetWP\Search',
			'Admin\Main',
			'Utilities\Main',
			'RCP\Main',
			'Users\Main',
			'Integrations\Active_Campaign\ActiveCampaignIntegration',
			'Reports\GroupMembersReport',
			'Group_Settings\Main',
			'GroupController',
			'ProrationController',
			'Downloads\Main',
			'InteractiveLists\Main',
			'Crms\Main',
			'Shortcodes\Main',
		];

		foreach ( $inits as $init ) {
			$this->init_class( 'FP_Core\\' . str_replace( '\\', '\\', $init ) );
		}

		do_action( 'after_fp_core_plugins_loaded_init' );
	}

	/**
	 * Init Class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $class The full namespaced class to init.
	 *
	 * @return void
	 */
	public function init_class( string $class ) {
		if ( class_exists( $class ) ) {
			new $class();
		} else {
			error_log( 'Cannot Find the ' . $class . ' class in Main.php' );
		}
	}

	/**
	 * Set Interactive Lists Post Types
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function set_interactive_post_types() {
		return apply_filters( FP_CORE_PREFIX . '_fp_set_interactive_post_types', $this->interactive_lists_post_types );
	}

	/**
	 * Get Interactive Lists Post Types
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_interactive_lists_post_types() {
		return apply_filters( FP_CORE_PREFIX . '_fp_get_interactive_post_types', $this->set_interactive_post_types() );
	}

	/**
	 * Set Interactive Lists CRMs
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function set_interactive_lists_crms() {
		return apply_filters( FP_CORE_PREFIX . '_fp_set_interactive_lists_crms', $this->interactive_lists_crms );
	}

	/**
	 * Get Interactive Lists Crms
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_interactive_lists_crms() {
		return apply_filters( FP_CORE_PREFIX . '_fp_get_interactive_lists_crms', $this->set_interactive_lists_crms() );
	}
}
