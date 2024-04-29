<?php

return (object) array(
	'acf_name' => 'content_section',
	'options'  => (object) array(
		'func'        => function ( $padding_classes = '' ) {
			$p_loc    = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc = "$p_loc/content";
			$item     = "$fcta_loc/item.php";

			$title                         = get_sub_field( 'section_title' );
			$type                          = get_sub_field( 'content_type' );
			$content                       = get_sub_field( 'content' );
			$content_2                     = get_sub_field( 'second_content_block' );
			$content_3                     = get_sub_field( 'third_content_block' );
			$light_text_on_dark_background = get_sub_field( 'light_text_on_dark_background' );
			$remove_margin                 = get_sub_field( 'remove_margin' );
			$centered_title                = get_sub_field( 'centered_title' );
			$bottom_margin                 = get_sub_field( 'add_bottom_margin' );
			$section_id                    = get_sub_field( 'section_id' );

			$custom_classes = implode( ' ', array(
				$type,
				$remove_margin ? 'no-margin' : '',
				$light_text_on_dark_background ? 'light-on-dark' : '',
				$centered_title ? 'centered-title' : '',
				$bottom_margin ? 'has-bot-padding' : '',
			) );

			$content_blocks = 0;

			if ( in_array( $type, array( 'full-width', 'narrow-width' ) ) ) {
				$content_blocks = 1;
			} elseif ( in_array( $type, array( 'fifty-fifty', 'thirty-seventy', 'seventy-thirty' ) ) ) {
				$content_blocks = 2;
			} elseif ( 'thirty-three' === $type ) {
				$content_blocks = 3;
			}

			require( $item );
		},
		'has_padding' => true,
	),
);
