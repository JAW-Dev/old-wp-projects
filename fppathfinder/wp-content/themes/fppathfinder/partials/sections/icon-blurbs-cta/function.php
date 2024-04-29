<?php

return (object) array(
	'acf_name' => 'icon_blurbs_cta',
	'options'  => (object) array(
		'func'        => function ( $padding_classes = '' ) {
			$p_loc = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc = "$p_loc/icon-blurbs-cta";
			$item = "$fcta_loc/item.php";

			$sec_title = get_sub_field( 'ib_section_title' );
			$sec_blurb = get_sub_field( 'ib_section_blurb' );
			$button = get_sub_field( 'ib_button' );
			$icon_items = get_sub_field( 'ib_icon_items' );

			require $item;
		},
		'has_padding' => false,
	),
);
