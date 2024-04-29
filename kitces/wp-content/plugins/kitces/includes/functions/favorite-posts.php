<?php
/**
 * Favorite Posts.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Functions/Members
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

use Kitces\Includes\Classes\FavoritePosts\Markup;
use Kitces\Includes\Classes\FavoritePosts\Logger;

if ( ! function_exists( 'mk_favorite_posts' ) ) {
	/**
	 * Favorite Posts.
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return string
	 */
	function mk_favorite_posts() {
		return ( new Markup() )->render();
	}
}

if ( ! function_exists( 'mk_favorite_posts_mobile' ) ) {
	/**
	 * Favorite Posts Mobile.
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return string
	 */
	function mk_favorite_posts_mobile() {
		return ( new Markup() )->render_mobile();
	}
}

if ( ! function_exists( 'mk_log_access' ) ) {
	/**
	 * Log favorite posts access
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return string
	 */
	function mk_log_access() {
		return ( new Logger() )->log_access();
	}
}
