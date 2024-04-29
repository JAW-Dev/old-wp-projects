<?php
/**
 * Main
 *
 * @package    FP_Core/
 * @subpackage FP_Core/InteractiveLists/Tables
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists;

use FP_Core\InteractiveLists\Templates\Main as Templates;
use FP_Core\InteractiveLists\Tables\Main as Tables;
use FP_Core\InteractiveLists\Utilities\Main as Utilities;
use FP_Core\InteractiveLists\PostTypes\Main as PostTypes;
use FP_Core\InteractiveLists\Cron\Main as Cron;
use FP_Core\InteractiveLists\Actions\Main as Actions;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Main
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Main {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init() {
		new Templates();
		new Utilities();
		new PostTypes();
		new Cron();
		new Tables();
		new Actions();
	}
}
