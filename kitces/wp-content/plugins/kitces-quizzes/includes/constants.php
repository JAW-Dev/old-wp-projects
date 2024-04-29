<?php
/**
 * Plugin Constants.
 *
 * @package    Kitces_Quizzes
 * @subpackage Kitces_Quizzes/Inlcudes
 * @author     Objectiv
 * @copyright  Copyright (c) 2022, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

if ( ! defined( 'MK_QUIZZES_VERSION' ) ) {
	define( 'MK_QUIZZES_VERSION', '1.0.0.' );
}

if ( ! defined( 'MK_QUIZZES_DIR_URL' ) ) {
	define( 'MK_QUIZZES_DIR_URL', trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) );
}

if ( ! defined( 'MK_QUIZZES_DIR_PATH' ) ) {
	define( 'MK_QUIZZES_DIR_PATH', trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) );
}

if ( ! defined( 'MK_QUIZZES_PRFIX' ) ) {
	define( 'MK_QUIZZES_PRFIX', 'mk_quizzes' );
}

if ( ! defined( 'MK_QUIZ_TIMES_TABLE_VERSION' ) ) {
	define( 'MK_QUIZ_TIMES_TABLE_VERSION', '1' );
}

if ( ! defined( 'MK_QUIZ_TIMES_TABLE_NAME' ) ) {
	define( 'MK_QUIZ_TIMES_TABLE_NAME', 'mk_quiz_times' );
}

if ( ! defined( 'MK_QUIZ_TIMES_TABLE_OPTION_NAME' ) ) {
	define( 'MK_QUIZ_TIMES_TABLE_OPTION_NAME', 'mk_quiz_times_table_version' );
}
