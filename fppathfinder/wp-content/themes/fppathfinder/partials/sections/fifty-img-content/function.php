<?php

return (object) array(
	'acf_name' => '5050_image_content',
	'options'  => (object) array(
		'func'        => function ( $padding_classes = '' ) {
			$p_loc    = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc = "$p_loc/fifty-img-content";
			$item     = "$fcta_loc/item.php";

			$rows       = get_sub_field( '5050_rows' );
			$background = get_sub_field( '5050_background' );
			$bg_class   = 'bg-' . $background;

			require( $item );
		},
		'has_padding' => false,
	),
);
