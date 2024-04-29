<?php
/**
 * Report Abstract
 *
 * @package    Custom_Reports
 * @subpackage Custom_Reports/Includes/Favorites
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace CustomReports;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Report Abstract
 *
 * @author Jason Witt
 * @since  1.0.0
 */
abstract class ReportAbstract {

	/**
	 * Slug
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Description
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * Name
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Title
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * ID
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Nonce
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $nonce;

	/**
	 * Table
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $table;

	/**
	 * Demo
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $demo;

	/**
	 * Has Table
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var boolean
	 */
	protected $has_table;

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $slug        The report slug.
	 * @param string $description The report description.
	 *
	 * @return void
	 */
	public function __construct( $slug, $description = '', $has_table = false ) {
		$this->slug        = $slug;
		$this->description = $description;
		$this->has_table   = $has_table;
		$this->name        = strtolower( str_replace( array( '_', ' ' ), '-', $slug ) );
		$this->title       = ucwords( str_replace( array( '_', '-' ), ' ', $slug ) );
		$this->id          = str_replace( array( '_', ' ' ), '-', $slug );
		$this->nonce       = str_replace( array( '_', ' ' ), '-', $slug );

		$this->hooks();
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
		add_action( "wp_ajax_{$this->slug}", array( $this, 'report' ) );
		add_action( "wp_ajax_nopriv_{$this->slug}", array( $this, 'report' ) );

		if ( $this->has_table ) {
			add_action( 'admin_menu', array( $this, 'full_report' ), 999 );
			add_action(
				'admin_head',
				function() {
					remove_submenu_page( 'index.php', $this->slug );
				}
			);
			add_filter( 'set-screen-option', array( $this, 'table_set_option' ), 10, 3 );
		}
	}

	/**
	 * Full Report
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function full_report() {
		$hook = add_dashboard_page(
			$this->title . ' Report',
			$this->title . ' Report',
			'manage_options',
			$this->slug,
			array( $this, 'sub_page' )
		);

		add_action( "load-$hook", array( $this, 'add_options' ) );
	}

	/**
	 * Table set option
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param boolean $status Whether to save or skip saving the screen option value. Default false.
	 * @param string  $option The option name.
	 * @param int     $value  The number of rows to use.
	 *
	 * @return int
	 */
	public function table_set_option( $status, $option, $value ) {
		return $value;
	}

	/**
	 * Add Options
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function add_options() {
		$class      = get_class( $this );
		$namespace  = substr( $class, 0, strrpos( $class, '\\' ) );
		$class_name = $namespace . '\Table';
		$option     = 'per_page';
		$args       = array(
			'label'   => 'Posts Per Page',
			'default' => 25,
			'option'  => 'posts_per_page',
		);
		add_screen_option( $option, $args );
		$this->table = new $class_name();
	}

	/**
	 * Render
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function render() {}

	/**
	 * Report
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function report() {
	}

	/**
	 * Sub Page
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function sub_page() {}
}
