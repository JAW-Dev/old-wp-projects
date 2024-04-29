<?php

return (object) array(
	'acf_name' => 'dynamic_welcomeupsell',
	'options'  => (object) array(
		'func'        => function ( $padding_classes = '' ) {
			$p_loc    = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc = "$p_loc/dynamic-welcome-upsell";
			$item     = "$fcta_loc/item.php";

			$initial_image       = get_sub_field( 'initial_image' );
			$pop_up_image       = get_sub_field( 'pop_up_image' );

			$section_title = get_sub_field( 'logged_out_section_title' );
			$section_blurb = get_sub_field( 'logged_out_section_blurb' );
			$button        = get_sub_field( 'logged_out_button' );
			$testimonial   = get_sub_field( 'logged_out_testimonial' );

			if ( is_user_logged_in() && function_exists( 'rcp_get_customer_by_user_id' ) ) {

				$user_id = get_current_user_id();
				$customer = rcp_get_customer_by_user_id( $user_id );

				if ( ! empty( $customer ) ) {

					$essentials_ids = array( 1, 5, 9 );
					$deluxe_ids = array( 2 );
					$premier_ids = array( 6 );

					$essentail_memberships = $customer->get_memberships(
						array(
							'object_id__in' => $essentials_ids,
							'status'        => 'active',
						)
					);

					$deluxe_memberships = $customer->get_memberships(
						array(
							'object_id__in' => $deluxe_ids,
							'status'        => 'active',
						)
					);

					$premier_memberships = $customer->get_memberships(
						array(
							'object_id__in' => $premier_ids,
							'status'        => 'active',
						)
					);

					// Set Details Based on Highest Level Matched First
					if ( count( $premier_memberships ) >= 1 ) {
						$section_title = get_sub_field( 'premier_section_title' );
						$section_blurb = get_sub_field( 'premier_section_blurb' );
						$button        = get_sub_field( 'premier_button' );
						$testimonial   = get_sub_field( 'premier_testimonial' );
					} elseif ( count( $deluxe_memberships ) >= 1 ) {
						$section_title = get_sub_field( 'deluxe_section_title' );
						$section_blurb = get_sub_field( 'deluxe_section_blurb' );
						$button        = get_sub_field( 'deluxe_button' );
						$testimonial   = get_sub_field( 'deluxe_testimonial' );
					} elseif ( count( $essentail_memberships ) >= 1 ) {
						$section_title = get_sub_field( 'essential_section_title' );
						$section_blurb = get_sub_field( 'essential_section_blurb' );
						$button        = get_sub_field( 'essential_button' );
						$testimonial   = get_sub_field( 'essential_testimonial' );
					}
				}
			}

			$section_details = array(
				'5050_initial_image' => $initial_image,
				'5050_pop_up_image'  => $pop_up_image,
				'5050_title'         => do_shortcode( $section_title ),
				'5050_blurb'         => $section_blurb,
				'5050_button'        => $button,
				'link_to'            => null,
				'5050_orientation'   => '',
				'5050_after_text'    => obj_simple_testimonial_html( $testimonial ),
			);

			require $item;
		},
		'has_padding' => false,
	),
);
