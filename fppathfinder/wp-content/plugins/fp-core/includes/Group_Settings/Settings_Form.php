<?php

namespace FP_Core\Group_Settings;

abstract class Settings_Form {
	protected $fields;

	abstract public function add_hooks();
	abstract public function get_name();
	abstract public function set_fields();

	/**
	 * Get Actions
	 *
	 * Return an array of action name string and callable handlers.
	 */
	abstract function get_actions(): array;

	/** Init
	 *
	 * General Setup
	 */
	public function init() {
		$this->set_fields();
		$this->add_hooks();
		add_action( 'wp_loaded', array( $this, 'check_for_actions' ) );
	}

	/**
	 * Check for actions
	 *
	 * Check for any actions specified by the children of this class.
	 */
	public function check_for_actions() {
		foreach ( $this->get_actions() as $action => $handler ) {
			if ( $this->verify_nonce( $action ) ) {
				call_user_func( $handler );
			}
		}
	}

	public function verify_nonce( string $action ) {
		return isset( $_REQUEST[ $this->get_name() ] ) && wp_verify_nonce( $_REQUEST[ $this->get_name() ], $action );
	}

	public function get_nonce_action_field( string $action ) {
		wp_nonce_field( $action, $this->get_name() );
	}
}
