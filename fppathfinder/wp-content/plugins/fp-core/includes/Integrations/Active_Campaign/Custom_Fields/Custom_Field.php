<?php

namespace FP_Core\Integrations\Active_Campaign\Custom_Fields;

abstract class Custom_Field {
	public function __construct() {
		$this->add_hooks();
	}

	public function get_field_key(): string {
		$tag = $this->get_tag();

		return "field[%$tag%,0]";
	}

	public function add_update( string $email, string $value ) {
		$add_update = \FP_Core\Integrations\Active_Campaign\Cron_Contact_Update_Queue::enqueue_update( $email, $this->get_field_key(), $value );

		if ( empty( $add_update ) ) {
			error_log( 'Custom_Field in add_update: Update Failed' ); // phpcs:ignore
		}
	}

	public function handle_error( \Throwable $th ) {
		$message = array(
			'Custom Field Error: ' . $th->getMessage(),
		);

		error_log( join( "\n", $message ) );
	}

	public function build_safe_method( $function ) {
		return function ( ...$args ) use ( $function ) {
			try {
				return call_user_func( $function, ...$args );
			} catch ( \Throwable $th ) {
				$this->handle_error( $th, $function, $args );
				return $args[0] ?? null;
			}
		};
	}

	/**
	 * Get the unique tag for this custom field in AC.
	 *
	 * Can be found here: https://fppathfinder.activehosted.com/app/fields/contacts
	 */
	abstract public function get_tag(): string;
	abstract public function add_hooks(): void;
}
