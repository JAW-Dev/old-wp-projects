<?php

return (object) array(
	'acf_name' => 'live_demos',
	'options'  => (object) array(
		'func'        => function( $padding_classes = '' ) {
			$p_loc           = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc        = "$p_loc/live-demos";
			$item            = "$fcta_loc/item.php";
			$header          = get_sub_field( 'live_demo_header' );
			$header_blurb    = get_sub_field( 'live_demo_header_blurb' );

			$learn_title     = get_sub_field( 'live_demo_you_will_learn_title' );
			$learn_list      = get_sub_field( 'live_demo_you_will_learn_list' );

			$team_header    = get_sub_field( 'live_demo_right_hearder' );
			$team_images     = get_sub_field( 'live_demo_team_images' );

			$dates_header    = get_sub_field( 'live_demo_dates_header' );
			$dates_subheader = get_sub_field( 'live_demo_dates_subheader' );
			$dates           = get_sub_field( 'live_demo_demo_dates' );

			$bottom_blurb    = get_sub_field( 'live_demo_bottom_blurb' );

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
