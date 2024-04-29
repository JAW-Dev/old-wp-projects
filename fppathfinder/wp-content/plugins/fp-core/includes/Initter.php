<?php

namespace FP_Core;

class Initter {
	private $inittables = array();

	public function __construct( InittableInterface ...$inittables ) {
		if ( empty( $inittables ) ) {
			return;
		}

		$this->inittables = $inittables;
	}

	public function init() {
		foreach ( $this->inittables as $inittable ) {
			$inittable->init();
		}
	}
}
