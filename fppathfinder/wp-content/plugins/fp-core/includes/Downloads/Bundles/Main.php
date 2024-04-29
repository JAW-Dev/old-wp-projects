<?php

namespace FP_Core\Downloads\Bundles;

use FP_Core\Downloads\Bundles\Generator\Main as GeneratorMain;

class Main {
	private function __construct() {}

	static public function init() {
		User_Meta_Adder::init();
		Customer_Note_Adder::init();
		Progress_Viewer_JS_Loader::init();
	}
}
