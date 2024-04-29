<?php

return (object) array(
	'acf_name' => 'testimonials_list',
	'options'  => (object) array(
		'func'        => function ( $padding_classes = '' ) {
			$p_loc = FlexibleContentSectionUtility::getSectionsDirectory();
			$sb_loc = "$p_loc/testimonials-list";
			$item = "$sb_loc/item.php";

			$title              = get_sub_field( 'section_title' );
			$intro_text         = get_sub_field( 'intro_text' );
			$testimonials       = get_sub_field( 'testimonials_repeater' );
			$two_columns        = get_sub_field( 'two_columns' );
			$style              = get_sub_field( 'style' );
			$images             = get_sub_field( 'images' );
			$display_as_slider             = get_sub_field( 'display_as_slider' );
			$midpoint           = ceil( count( $testimonials ) / 2 );
			$testimonials_left  = ! empty( array_chunk( $testimonials, $midpoint )[0] ) ?? array();
			$testimonials_right = ! empty( array_chunk( $testimonials, $midpoint )[1] ) ?? array();

			$class_string = null;
			if ( 'dark' === $style ) {
				$class_string = 'dark-style has-top-padding has-bot-padding';
			}

			if ( $images ) {
				$class_string .= ' has-images';
			}

			require $item;
		},
		'has_padding' => true,
	),
);
