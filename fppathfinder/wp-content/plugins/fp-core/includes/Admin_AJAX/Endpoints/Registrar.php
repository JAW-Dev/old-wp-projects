<?php

namespace FP_Core\Admin_AJAX\Endpoints;

/**
 * Registrar
 *
 * The regisgtrar class is responsible for initting and validating.
 */
class Registrar {
	static protected $unique_action_tags = array();

	static public function get_endpoints(): array {
		return array(
			new PDFBundleGenerator(),
			new PDFBundleGeneratorProgressReporter(),
			new PDFBundleProcessCreator(),
		);
	}


	static public function register_all() {
		foreach ( self::get_endpoints() as $endpoint ) {
			self::register( $endpoint );
		}
	}

	static public function register( EndpointInterface $endpoint ) {
		if ( in_array( $endpoint->get_name(), self::$unique_action_tags ) ) {
			throw new \Exception( 'duplicate admin ajax action tag detected' );
			return;
		}

		self::$unique_action_tags[] = $endpoint->get_name();

		$name           = $endpoint->get_name();
		$handler        = $endpoint->get_handler();
		$nopriv_handler = $endpoint->get_nopriv_handler();

		add_action( "wp_ajax_$name", $handler );
		add_action( "wp_ajax_nopriv_$name", $nopriv_handler );
	}
}
