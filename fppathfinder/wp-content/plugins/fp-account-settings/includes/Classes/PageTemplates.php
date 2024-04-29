<?php
/**
 * Page Templates.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Page Templates.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class PageTemplates {

	/**
	 * Templates
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $templates = array();

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $templates The array of templates.
	 *
	 * @return void
	 */
	public function __construct( $templates ) {
		add_filter( 'theme_page_templates', [ $this, 'add_templates' ] );
		add_filter( 'wp_insert_post_data', [ $this, 'register_templates' ] );
		add_filter( 'template_include', [ $this, 'view_templates' ] );
		add_filter( 'rcp_template_stack', [ $this, 'rcp_template_path' ] );
		$this->templates = $templates;
	}

	/**
	 * Add Templates
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $posts_templates An array of the post templates.
	 *
	 * @return array
	 */
	public function add_templates( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}

	/**
	 * Register Templates
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $atts An array of sanitized (and slashed) but otherwise unmodified post data.
	 *
	 * @return array
	 */
	public function register_templates( $atts ) {
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
		$templates = wp_get_theme()->get_page_templates();

		if ( empty( $templates ) ) {
			$templates = array();
		}

		wp_cache_delete( $cache_key, 'themes' );

		$templates = array_merge( $templates, $this->templates );

		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;
	}

	/**
	 * View Templates
	 *
	 * Checks if the template is assigned to the page
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $template The path of the template to include.
	 *
	 * @return string
	 */
	public function view_templates( $template ) {
		global $post;

		if ( ! $post ) {
			return $template;
		}

		if ( ! isset( $this->templates[ get_post_meta( $post->ID, '_wp_page_template', true ) ] ) ) {
			return $template;
		}

		$file = get_post_meta( $post->ID, '_wp_page_template', true );

		if ( file_exists( $file ) ) {
			return $file;
		}

		return $template;
	}

	/**
	 * RCP Templat Path
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $template_stack Paths to template override directories.
	 *
	 * @return array
	 */
	public function rcp_template_path( $template_stack ) {
		array_unshift( $template_stack, FP_ACCOUNT_SETTINGS_DIR_PATH . 'includes/Templates/rcp' );

		return $template_stack;
	}
}
