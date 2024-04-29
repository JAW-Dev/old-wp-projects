<?php

return (object) array(
	'acf_name' => 'white_label_cta',
	'options'  => (object) array(
		'func'        => function ( $padding_classes = '' ) {
			$p_loc = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc = "$p_loc/white-label-cta";
			$item = "$fcta_loc/item.php";

			$title = get_sub_field( 'section_title' );
			$icons = get_sub_field( 'icons' );
			$section_blurb = get_sub_field( 'section_blurb' );
			$section_button = get_sub_field( 'section_button' );

			require $item;
		},
		'has_padding' => false,
	),
);
