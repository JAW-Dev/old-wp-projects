<?php
/**
 * Enqueue Styles.
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

if ( ! class_exists( __NAMESPACE__ . '\\EnqueueStyles' ) ) {

	/**
	 * Enqueue Styles.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class EnqueueStyles {

		/**
		 * Arguments.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var array
		 */
		protected $args = array();

		/**
		 * Initialize the class
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array $args {
		 *     The arguments for adding Stylesheets.
		 *
		 *     @type string $dir_path (Required) The directory path.
		 *     @type string $dir_url  (Required) The directory url.
		 *      @type array $styles {
		 *         Arguments for enqueuing the stylesheets.
		 *
		 *         @type string $handle      (Required) The handle or name of the script.
		 *         @type string $file        (Required) The source of the file to enqueue.
		 *         @type string $depends     (Optional) The dependencies of the enqueued file. Default: array()
		 *         @type string $media       (Optional) If Stylesheet, The media for which this stylesheet has been defined. Default: 'all'.
		 *         @type mixed  $conditional (Optional) Conditional check to enqueue the script. Default: true
		 *     }
		 *     @type array $admin-styles {
		 *         Arguments for enqueuing the admin stylesheets.
		 *
		 *         @type string $handle      (Required) The handle or name of the script.
		 *         @type string $file        (Required) The source of the file to enqueue.
		 *         @type string $depends     (Optional) The dependencies of the enqueued file. Default: array()
		 *         @type string $media       (Optional) If Stylesheet, The media for which this stylesheet has been defined. Default: 'all'.
		 *         @type mixed  $hook        (Optional) The admin page hook to conditionaly load the script.
		 *         @type mixed  $conditional (Optional) Conditional check to enqueue the script. Default: true
		 *     }
		 * }
		 *
		 * @return void
		 */
		public function __construct( $args = array() ) {
			$this->args = Parse::array_to_object( $args, $this->set_defaults() );
			$this->hooks();
		}

		/**
		 * Set Defaults
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function set_defaults() {
			return array(
				'dir_path'     => '',
				'dir_url'      => '',
				'styles'       => array(
					'handle'      => '',
					'file'        => '',
					'depends'     => array(),
					'media'       => 'all',
					'conditional' => '',
				),
				'admin_styles' => array(
					'handle'      => '',
					'file'        => '',
					'depends'     => array(),
					'media'       => 'all',
					'hook'        => '',
					'conditional' => '',
				),
			);
		}

		/**
		 * Hooks.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function hooks() {
			add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		}

		/**
		 * Enqueue Styles.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function styles() {
			// Bail if styles is empty.
			if ( empty( $this->args->styles ) ) {
				return;
			}

			foreach ( $this->args->styles as $style ) {
				$conditional = ! empty( $script['conditional'] ) ? call_user_func( $script['conditional'] ) : true;

				if ( $conditional ) {
					$this->enqueue( $style );
				} else {
					continue;
				}
			}
		}

		/**
		 * Enqueue Admin Styles.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param string $hook The admin page hook.
		 *
		 * @return void
		 */
		public function admin_styles( $hook ) {
			// Bail if admin_styles is empty.
			if ( empty( $this->args->admin_styles ) ) {
				return;
			}

			foreach ( $this->args->admin_styles as $style ) {
				$conditional = ! empty( $style['conditional'] ) ? call_user_func( $style['conditional'] ) : true;

				if ( ! empty( $style['hook'] ) ) {
					if ( $hook !== $style['hook'] ) {
						return;
					}
				}

				if ( $conditional ) {
					$this->enqueue( $style );
				} else {
					continue;
				}
			}
		}

		/**
		 * Enqueue
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array $style The style to enqueue.
		 *
		 * @return void
		 */
		public function enqueue( $style ) {

			// Bail if script is empty.
			if ( empty( $style ) ) {
				return;
			}

			$dir_path  = ! empty( $this->args->dir_path ) ? $this->args->dir_path : '';
			$dir_url   = ! empty( $this->args->dir_url ) ? $this->args->dir_url : '';
			$handle    = ! empty( $style['handle'] ) ? $style['handle'] : '';
			$file      = ! empty( $style['file'] ) ? $style['file'] : '';
			$file_path = trailingslashit( $dir_path ) . $file;
			$depends   = ! empty( $style['depends'] ) ? $style['depends'] : array();
			$media     = ! empty( $style['media'] ) ? $style['media'] : 'all';
			$file_time = ! empty( $style['file'] ) && file_exists( $file_path ) ? filemtime( $file_path ) : '1.0.0';
			$check     = ! empty( $handle ) && ! empty( $file ) && file_exists( $file_path );

			if ( $check ) {
				wp_register_style( $handle, trailingslashit( $dir_url ) . $file, $depends, $file_time, $media );
				wp_enqueue_style( $handle );
			}
		}
	}
}
