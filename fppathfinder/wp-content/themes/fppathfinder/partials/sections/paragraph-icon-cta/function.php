<?php

return (object) array(
	'acf_name' => 'paragraph_icon_cta',
	'options'  => (object) array(
		'func'        => function( $padding_classes = '' ) {
			$p_loc    = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc = "$p_loc/paragraph-icon-cta";
			$item     = "$fcta_loc/item.php";

			$title      = get_sub_field( 'pic_section_title' );
			$entries    = get_sub_field( 'pic_blocks' );
			$items   = array();

			$path  = 'assets/images/icons';
			$files = get_the_icons( $path );

			foreach ( $entries as $entry ) {
				foreach ( $files as $file ) {
					$name = preg_replace( '/.[^.]*$/', '', $file );

					if ( $entry['pic_icon'] === $name ) {
						$entry['pic_icon'] = $file;
						$items[] = $entry;
					}
				}
			}

			require $item;
		},
		'has_padding' => false,
	),
);
