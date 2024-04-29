<?php

return (object) array(
	'acf_name' => 'resource_sliders',
	'options'  => (object) array(
		'func'        => function ( $padding_classes = '' ) {
			$p_loc    = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc = "$p_loc/resource-sliders";
			$item     = "$fcta_loc/item.php";

			$remove_margin = get_sub_field( 'remove_margin' );

			$custom_classes = implode( ' ', array(
				$remove_margin ? 'remove-margin' : '',
			) );

			$sliders = get_sub_field( 'member_section_sliders' );

			require( $item );
		},
		'has_padding' => true,
	),
);
