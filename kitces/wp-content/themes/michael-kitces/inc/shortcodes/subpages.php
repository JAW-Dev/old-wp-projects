<?php
/**
 * Subpages Shortcode
 *
 * @package    Kitces
 * @subpackage Kitces/lasses
 * @author     Objectiv
 * @copyright  Copyright (c) Date, Objectiv
 * @license    GPL-2.0
 */

/**
 * Subpage Shortcode
 *
 * @param boolean $atts If has attrs.
 */
function kitces_subpages_shortcode( $atts = false ) {
	$pid               = get_the_id();
	$subpages          = get_posts( 'numberposts=-1&orderby=date&order=DESC&post_type=page&post_status=private,publish&post_parent=' . $pid );
	$show_all          = isset( $_GET['show_all'] ) && 'true' === $_GET['show_all'] && current_user_can( 'manage_options' );
	$return            = '';
	$sticky            = mk_key_value( $atts, 'sticky' );
	$filter_categories = array();
	$cc_filter         = false;
	$cc_filter_type    = false;
	$display_cats      = false;
	$display_dates     = false;

	if ( ! empty( $atts && array_key_exists( 'filter', $atts ) ) ) {
		$cc_filter = boolval( $atts['filter'] );
	}

	if ( ! empty( $atts && array_key_exists( 'filter_type', $atts ) ) ) {
		$cc_filter_type = boolval( $atts['filter_type'] );
	}

	if ( ! empty( $atts && array_key_exists( 'displaycats', $atts ) ) ) {
		$display_cats = boolval( $atts['displaycats'] );
	}

	if ( ! empty( $atts && array_key_exists( 'displaydates', $atts ) ) ) {
		$display_dates = boolval( $atts['displaydates'] );
	}

	if ( $subpages ) {
		$return .= '<table class="kitces-quiz-list" style="overflow: auto; max-height: 1000px;display: block;">';

		$return .= '<thead style="top:0;">';

		$return .= "<td class='quiz-table' colspan='9' style='text-align:center' >Hours of CE (By Type)";

		$return .= '<tr style="position: sticky;top:0;height: 60px;z-index:1;  overflow-x: hidden;		">';
		$return .= '<td>Quiz Name</td>';
		if ( $display_dates ) {
			$return .= "<td>Date</td>";
		}
		if ( $display_cats ) {
			$return .= "<td>Categories</td>";
		}
		$return .= "<td style='text-align: center;'>CFP&reg; & IWI</td>";
		$return .= "<td style='text-align: center;'>CPA</td>";
		$return .= "<td style='text-align: center;'>EA</td>";
		$return .= "<td style='text-align: center;'>IAR (E&PR)</td>";
		$return .= "<td style='text-align: center;'>IAR (P&P)</td>";
		$return .= "<td style='text-align: center;'>Status</td>";
		$return .= '</tr>';
		$return .= '</thead>';

		if ( $sticky ) {
			$sticky      = (int) $sticky;
			$sticky_post = get_post( $sticky );
			$return     .= mk_subpage_row_row( $show_all, $sticky_post, 'sticky', $display_cats, $display_dates );
		}

		foreach ( $subpages as $subpage ) {
			if ( $subpage->ID !== $sticky ) {
				$return .= mk_subpage_row_row( $show_all, $subpage, null, $display_cats, $display_dates );
			}

			// Add Categories to Filter
			$post_cats = get_the_terms( $subpage->ID, 'category' );

			if ( ! empty( $post_cats ) && is_array( $post_cats ) ) {
				foreach ( $post_cats as $cat ) {
					if ( ! in_array( $cat, $filter_categories, true ) ) {
						$filter_categories[ $cat->name ] = $cat;
					}
				}
			}
		}
		$return .= '</table>';
		$return .= '<div class="subpages-no-results">No results...</div>';
	}

	ksort( $filter_categories );

	$filter = cgd_cc_filter_list( $cc_filter, true, $filter_categories, $cc_filter_type );

	return $filter . $return;
}
add_shortcode( 'subpages', 'kitces_subpages_shortcode' );

function mk_subpage_row_row( $show_all, $subpage, $row_class = null, $display_cats = false, $display_date = false, $html_returned = true ) {
	global $CGD_CECredits; // phpcs:ignore

	$cecredits = $CGD_CECredits; // phpcs:ignore
	$can_access_quiz = $cecredits->can_member_access_quiz( $subpage->ID );
	$is_expired      = get_post_meta( $subpage->ID, 'kitces_expired_quiz', true );
	$return          = null;

	$display_row = false;

	if ( $show_all || $can_access_quiz ) {
		$display_row = true;
	}

	if ( $is_expired ) {
		$display_row = false;
	}

	if ( $display_row ) {
		$matches = false;

		preg_match( '@id="(\d+)"@', $subpage->post_content, $matches );

		if ( ! empty( $matches ) && $cecredits->form_is_quiz( array( 'id' => $matches[1] ) ) ) {
			$form        = GFAPI::get_form( $matches[1] );
			$cfp_hours   = rgar( $form, 'hours' ) !== '' ? rgar( $form, 'hours' ) : 0;
			$nasba_hours = rgar( $form, 'nasba_hours' ) !== '' ? rgar( $form, 'nasba_hours' ) : 0;
			$ea_hours    = rgar( $form, 'ea_hours' ) !== '' ? rgar( $form, 'ea_hours' ) : 0;
			$iar_epr  	 = rgar( $form, 'iar_epr' ) !== '' ? rgar( $form, 'iar_epr' ) : 0;
			$iar_pp	   	 = rgar( $form, 'iar_pp' ) !== '' ? rgar( $form, 'iar_pp' ) : 0;
			$results           = $CGD_CECredits->get_quiz_results( $form );
			$quiz_attemps      = get_user_meta( get_current_user_id(), 'quiz_' . $matches[1] . '_attempts', true );
			$passed            = $CGD_CECredits->get_quiz_passed( $matches[1] );
			$failed            = ! $CGD_CECredits->get_quiz_passed( $matches[1] ) && $quiz_attemps >= 2;
			$categories        = get_the_terms( $subpage->ID, 'category' );
			$categories_string = null;
			$date              = null;

			if ( $display_date ) {
				$date = get_the_date( 'n/d/Y', $subpage );
			}

			if ( ! empty( $categories ) ) {
				$cats_array = array();
				foreach ( $categories as $cat ) {
					array_push( $cats_array, $cat->name );
					$row_class .= ' qc-' . $cat->term_id;
				}
				$categories_string = implode( ', ', $cats_array );
			}

			$quiz_type_classes = cgd_get_cci_quiz_type_string( $subpage->ID );
			if ( ! empty( $quiz_type_classes ) ) {
				$row_class .= ' ' . $quiz_type_classes;
			}

			$cfp_hrs_class = '';
			if ( strtolower( $cfp_hours ) === 'n/a' ) {
				$cfp_hrs_class = 'text-red';
			}

			$nasba_hrs_class = '';
			if ( strtolower( $nasba_hours ) === 'n/a' ) {
				$nasba_hrs_class = 'text-red';
			}

			$iar_epr_class = '';
			if ( strtolower( $iar_epr ) === 'n/a' ) {
				$iar_epr_class = 'text-red';
			}

			$iar_pp_class = '';
			if ( strtolower( $iar_pp ) === 'n/a' ) {
				$iar_pp_class = 'text-red';
			}

			$ea_hrs_class = '';
			if ( strtolower( $ea_hours ) === 'n/a' ) {
				$ea_hrs_class = 'text-red';
			}




			$passed = $cecredits->get_quiz_passed( $matches[1] );

			if ( ! $html_returned ) {

				$availability       = 'Available';
				$availability_class = 'available';
				if ( $passed ) {
					$availability       = 'Passed';
					$availability_class = 'completed';
				} elseif ( $failed ) {
					$availability       = 'Did Not Pass';
					$availability_class = 'completed';
				}

				$return = array(
					'title'              => $subpage->post_title,
					'link'               => get_permalink( $subpage ),
					'cfp_hours'          => $cfp_hours,
					'nasba_hours'        => $nasba_hours,
					'ea_hours'           => $ea_hours,
					'iar_epr'         	 => $iar_epr,
					'iar_pp'         	 => $iar_pp,
					'availability'       => $availability,
					'availability_class' => $availability_class,
					'post_id'            => $subpage->ID,
				);
			} else {
				$return .= '<tr class="' . $row_class . '"><td>';
				if ( $passed ) {
					$return .= '<s><a href="' . get_permalink( $subpage->ID ) . '">' . $subpage->post_title . '</a></s>';
				} elseif ( $failed ) {
					$return .= '<s><a href="' . get_permalink( $subpage->ID ) . '">' . $subpage->post_title . '</a></s>';
				} else {
					$return .= '<a href="' . get_permalink( $subpage->ID ) . '">' . $subpage->post_title . '</a>';
				}
				$return .= '</td>';
				if ( $display_date && ! empty( $date ) ) {
					$return .= "<td style='text-align: center;'>";
					$return .= $date;
					$return .= '</td>';
				}

				if ( $display_cats ) {
					$return .= "<td style='text-align: center;' class='categories'>";
					$return .= $categories_string;
					$return .= '</td>';
				}

				$return .= "<td style='text-align: center;' class=" . $cfp_hrs_class . '>';
				$return .= $cfp_hours;
				$return .= '</td>';
				$return .= "<td style='text-align: center;' class=" . $nasba_hrs_class . '>';
				$return .= $nasba_hours;
				$return .= '</td>';
				$return .= "<td style='text-align: center;' class=" . $ea_hrs_class . '>';
				$return .= $ea_hours;
				$return .= '</td>';
				$return .= "<td style='text-align: center;' class=" . $iar_epr_class . '>';
				$return .= $iar_epr;
				$return .= '</td>';
				$return .= "<td style='text-align: center;' class=" . $iar_pp_class . '>';
				$return .= $iar_pp;
				$return .= '</td>';
				$return .= "<td style='text-align: center;'>";


				if ( $passed ) {
					$return .= "<span class='completed'>Passed</span>";
				} elseif ( $failed ) {
					$return .= "<span class='completed'>Did Not Pass</span>";
				} else {
					$return .= "<span class='available'>Available</span>";
				}

				$return .= '</td></tr>';
			}
		}
	}

	return $return;
}
