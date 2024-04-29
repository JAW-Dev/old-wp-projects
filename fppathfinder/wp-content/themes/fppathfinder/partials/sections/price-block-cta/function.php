<?php

return (object) array(
	'acf_name' => 'price_block_cta',
	'options'  => (object) array(
		'func'        => function ( $padding_classes = '' ) {
			$p_loc = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc = "$p_loc/price-block-cta";
			$item = "$fcta_loc/item.php";

			$sub_title = get_sub_field( 'sub_title' );
			$footer_content = get_sub_field( 'footer_content' );
			$title = get_sub_field( 'title' );
			$price_text = get_sub_field( 'price_text' );
			$content = get_sub_field( 'content' );
			$button = get_sub_field( 'button' );
			$bg = get_sub_field( 'section_bg' );
			$bg_class = 'bg-blocks';

			if ( $bg ) {
				$bg_class = 'colored-bg';
			}

			require $item;
		},
		'has_padding' => false,
	),
);
