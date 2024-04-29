<?php
/**
 * Shortcode.
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/GroupPriceQuote
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\GroupPriceQuote;

use Kitces\Includes\Classes\Utils;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Shortcode.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Shortcode {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
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
		add_shortcode( 'group_price_quote', array( $this, 'calculator' ) );
		add_shortcode( 'group_price_table', array( $this, 'table' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
	}

	/**
	 * Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function scripts() {
		if ( ! has_shortcode( Utils\Page::get_content(), 'group_price_table' ) ) {
			return;
		}

		$filename = 'src/js/groupmemberscalc.js';
		$file     = KITCES_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';

		wp_register_script( 'kitces_group_pricing', KITCES_DIR_URL . $filename, array(), $version, true );
		wp_enqueue_script( 'kitces_group_pricing' );

		$prices_data = mk_acf_get_field( 'kictes_group_calc_pricing', 'options', true, true );
		$prices      = array_map( function( $person ) {
			return $person['price'];
		}, $prices_data );

		wp_localize_script(
			'kitces_group_pricing',
			'kitcesGroupPricing',
			array(
				'prices' => $prices,
			)
		);
	}

	/**
	 * Styles
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function styles() {
		if ( ! has_shortcode( Utils\Page::get_content(), 'group_price_table' ) ) {
			return;
		}

		$filename = 'src/css/groupmemberscalc.css';
		$file     = KITCES_DIR_PATH . $filename;
		$version  = file_exists( $file ) ? filemtime( $file ) : '1.0.0';

		wp_register_style( 'kitces_group_pricing', KITCES_DIR_URL . $filename, array(), $version, 'all' );
		wp_enqueue_style( 'kitces_group_pricing' );
	}

	/**
	 * Calculator
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function calculator() {
		$title         = mk_acf_get_field( 'kictes_group_calc_title', 'options' );
		$select_title  = mk_acf_get_field( 'kictes_group_calc_select_title', 'options' );
		$price_title   = mk_acf_get_field( 'kictes_group_calc_price_title', 'options' );
		$price_postfix = mk_acf_get_field( 'kictes_group_calc_price_postfix', 'options' );
		$fifty_plus    = mk_acf_get_field( 'kictes_group_calc_50_plus_text', 'options' );
		$disclaimer    = mk_acf_get_field( 'kictes_group_calc_disclaimer_text', 'options' );

		ob_start();
		?>
		<div class="group-price-quote">
			<h3 class="group-price-quote__heading"><?php echo wp_kses_post( $title ); ?></h3>
			<div class="group-price-quote__body">
				<div class="group-price-quote__body-left">
					<h4 class="group-price-quote__body-left-heading"><?php echo wp_kses_post( $select_title ); ?></h4>
					<select id="group-price-quote-select" class="group-price-quote__select">
						<?php
						for ( $i = 1; $i < 49; $i++ ) {
							?>
							<option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
							<?php
						}
						?>
						<option value="50+">50+</option>
					</select>
				</div>
				<div class="group-price-quote__body-right">
					<h4 class="group-price-quote__body-right-heading"><?php echo wp_kses_post( $price_title ); ?></h4>
					<div id="group-price-quote-cost" class="group-price-quote__cost">
						<span id="group-price-quote-total" class="group-price-quote__total"></span>
						<span class="group-price-quote__price-postfix"> <?php echo wp_kses_post( $price_postfix ); ?></span>
					</div>
					<div id="group-price-quote-limit" class="group-price-quote__limit">
						<?php echo wp_kses_post( $fifty_plus ); ?>
					</div>
				</div>
			</div>
			<div class="group-price-quote__disclaimer">
				<?php echo wp_kses_post( $disclaimer ); ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Table
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function table() {
		$prices = mk_acf_get_field( 'kictes_group_calc_pricing', 'options', true, true );

		ob_start();
		?>
		<table class="group-price-quote-table" style="border-collapse: collapse;>
			<tbody>
				<tr style="height: 24px;">
					<td style="width: 33.3333%; height: 24px;"></td>
					<td style="width: 33.3333%; height: 24px;">Annual Price</td>
					<td style="width: 33.3333%; height: 24px;">Discount</td>
				</tr>
				<?php
				foreach ( $prices as $price ) :
					$label    = $price['label_persons'] ?? '';
					$cost     = ! empty( $price['price'] ) ? '$' . $price['price'] . ' per person' : '';
					$discount = ! empty( $price['discount'] ) ? $price['discount'] . '% discount' : '';
				?>
					<tr style="height: 24px;">
						<td style="width: 33.3333%; height: 24px;"><?php echo wp_kses_post( $label ); ?></td>
						<td style="width: 33.3333%; height: 24px;"><?php echo wp_kses_post( $cost ); ?></td>
						<td style="width: 33.3333%; height: 24px;"><?php echo wp_kses_post( $discount ); ?></td>
					</tr>
				<?php endforeach; ?>

			</tbody>
		</table>
		<?php

		return ob_get_clean();
	}
}
