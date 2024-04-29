<?php

return (object) array(
	'acf_name' => 'email_cta_section',
	'options'  => (object) array(
		'func'    => function( $padding_classes = '') {
			$p_loc    = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc = "$p_loc/email-cta";
			$item     = "$fcta_loc/item.php";

			$title               = get_sub_field( 'title' );
			$blurb               = get_sub_field( 'blurb' );
			$bg_img              = get_sub_field( 'background_image' );
			$form_source         = get_sub_field( 'form_source' );
			$custom_form         = get_sub_field( 'custom_mailchimp_form' );
			$form                = get_field( 'default_mc_form', 'option' );
			$columns             = get_sub_field( 'columns' );
			$second_column_image = get_sub_field( 'second_column_image' );
			$overlay_color       = get_sub_field( 'overlay_color' ) ? 'background-color:' . get_sub_field( 'overlay_color' ) . ';' : '';
			$overlay_opacity     = get_sub_field( 'overlay_opacity' ) ? 'opacity:' . get_sub_field( 'overlay_opacity' ) . ';' : '';

			$overlay_styles = ! empty( $overlay_color ) || ! empty( $overlay_opacity ) ? ' style="' . $overlay_color . $overlay_opacity . '"' : '';

			if ( $form_source === 'custom' ) {
				$form = $custom_form;
			}

			require $item;
		},
		'has_padding' => false,
	)
);
