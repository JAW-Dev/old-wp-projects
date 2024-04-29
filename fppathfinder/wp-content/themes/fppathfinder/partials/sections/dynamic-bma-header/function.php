<?php

return (object) array(
	'acf_name' => 'dynamic_bam_header',
	'options'  => (object) array(
		'func'        => function ( $padding_classes = '' ) {
			$p_loc    = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc = "$p_loc/dynamic-bma-header";
			$item     = "$fcta_loc/item.php";

			$type                          = 'full-width';
			$light_text_on_dark_background = get_sub_field( 'light_text_on_dark_background' );
			$remove_margin                 = get_sub_field( 'remove_margin' );
			$centered_title                = get_sub_field( 'centered_title' );
			$bottom_margin                 = get_sub_field( 'add_bottom_margin' );
			$section_id                    = get_sub_field( 'section_id' );
			$section_title                 = get_sub_field( 'logged_out_section_title' );
			$section_blurb                 = get_sub_field( 'logged_out_section_blurb' );
			$button                        = get_sub_field( 'logged_out_button' );

			$custom_classes = implode( ' ', array(
				$type,
				$remove_margin ? 'no-margin' : '',
				$light_text_on_dark_background ? 'light-on-dark' : '',
				$centered_title ? 'centered-title' : '',
				$bottom_margin ? 'has-bot-padding' : '',
				'dynamic-bma-header',
			) );

			$section_title = get_sub_field( 'logged_out_section_title' );
			$section_blurb = get_sub_field( 'logged_out_section_blurb' );
			$button        = get_sub_field( 'logged_out_button' );

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
					} elseif ( count( $deluxe_memberships ) >= 1 ) {
						$section_title = get_sub_field( 'deluxe_section_title' );
						$section_blurb = get_sub_field( 'deluxe_section_blurb' );
						$button        = get_sub_field( 'deluxe_button' );
					} elseif ( count( $essentail_memberships ) >= 1 ) {
						$section_title = get_sub_field( 'essential_section_title' );
						$section_blurb = get_sub_field( 'essential_section_blurb' );
						$button        = get_sub_field( 'essential_button' );
					}
				}
			}

			$button_text = $button['title'] ?? '';
			$button_url  = $button['url'] ?? '';

			require( $item );
		},
		'has_padding' => true,
	),
);
