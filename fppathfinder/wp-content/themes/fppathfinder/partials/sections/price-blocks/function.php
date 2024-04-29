<?php

return (object) array(
	'acf_name' => 'price_blocks',
	'options'  => (object) array(
		'func'        => function ( $padding_classes = '' ) {
			$p_loc         = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc      = "$p_loc/price-blocks";
			$item          = "$fcta_loc/item.php";
			$price_blocks  = get_sub_field( 'price_blocks' );
			$remove_margin = get_sub_field( 'remove_margin' );
			$after_blocks_text = get_sub_field( 'after_blocks_text' );

			$custom_classes = implode( ' ', array(
				$remove_margin ? 'remove-margin' : '',
			) );

			require $item;
		},
		'has_padding' => true,
	),
);
