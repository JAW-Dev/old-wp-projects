<?php

return (object) array(
	'acf_name' => 'simple_cta',
	'options'  => (object) array(
		'func'        => function( $padding_classes = '' ) {
			$p_loc                         = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc                      = "$p_loc/simple-cta";
			$item                          = "$fcta_loc/item.php";
			$heading                       = get_sub_field( 'heading' );
			$button                        = get_sub_field( 'button' );
			$remove_margin                 = get_sub_field( 'remove_margin' );
			$remove_top_margin             = get_sub_field( 'remove_top_margin' );
			$remove_bottom_margin          = get_sub_field( 'remove_bottom_margin' );
			$light_text_on_dark_background = get_sub_field( 'light_text_on_dark_background' );

			$custom_classes = implode( ' ', array(
				$remove_margin ? 'remove-margin' : '',
				$remove_top_margin ? 'remove-top-margin' : '',
				$remove_bottom_margin ? 'remove-bottom-margin' : '',
				$light_text_on_dark_background ? 'light-on-dark' : '',
			) );

			require $item;
		},
		'has_padding' => true,
	),
);
