<?php

return (object) array(
	'acf_name' => 'video_modal_plus_content',
	'options'  => (object) array(
		'func'        => function ( $padding_classes = '' ) {
			$p_loc    = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc = "$p_loc/dynamic-video-content";
			$item     = "$fcta_loc/item.php";

			$bg_color             = get_sub_field( 'bg_color' );
			$hide_section_top     = get_sub_field( 'hide_section_top' );
			$section_title        = get_sub_field( 'section_title' );
			$section_blurb        = get_sub_field( 'section_blurb' );
			$video_content_blocks = get_sub_field( 'video_content_blocks' );
			$mt_class             = 'basemt3';

			if ( $hide_section_top || ( empty( $section_title ) && empty( $section_blurb ) ) ) {
				$mt_class = '';
			}

			$bg_color_class = '';
			$light_bg = true;
			if ( 'blue' === $bg_color ) {
				$light_bg = false;
				$bg_color_class = 'bg-action';
			}

			$essential_m_count = 0;
			$deluxe_m_count    = 0;
			$premier_m_count   = 0;

			if ( is_user_logged_in() && function_exists( 'rcp_get_customer_by_user_id' ) ) {

				$user_id = get_current_user_id();
				$customer = rcp_get_customer_by_user_id( $user_id );

				if ( ! empty( $customer ) ) {

					$essentials_ids = array( 1, 5, 9 );
					$deluxe_ids = array( 2 );
					$premier_ids = array( 6 );

					$essential_memberships = $customer->get_memberships(
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

					$essential_m_count = count( $essential_memberships );
					$deluxe_m_count    = count( $deluxe_memberships );
					$premier_m_count   = count( $premier_memberships );

					if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
						$essential_m_count = 1;
						$deluxe_m_count    = 1;
						$premier_m_count   = 1;
					}
				}
			}

			// Sort out who gets to see what
			$display_vc_blocks = array();
			if ( ! empty( $video_content_blocks ) && is_array( $video_content_blocks ) ) {
				foreach ( $video_content_blocks as $block ) {
					$visibility = obj_key_value( $block, 'visibility' );

					if ( 'all' === $visibility ) {
						array_push( $display_vc_blocks, $block );
					} elseif ( 'essentials' === $visibility && $essential_m_count >= 1 ) {
						array_push( $display_vc_blocks, $block );
					} elseif ( 'deluxe' === $visibility && $deluxe_m_count >= 1 ) {
						array_push( $display_vc_blocks, $block );
					} elseif ( 'premier' === $visibility && $premier_m_count >= 1 ) {
						array_push( $display_vc_blocks, $block );
					}
				}
			}

			require $item;
		},
		'has_padding' => false,
	),
);
