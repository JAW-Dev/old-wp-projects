<?php

return (object) array(
	'acf_name' => 'feature_comparison_checklist_chart',
	'options'  => (object) array(
		'func'        => function( $padding_classes = '' ) {
			$p_loc    = FlexibleContentSectionUtility::getSectionsDirectory();
			$fcta_loc = "$p_loc/feature-comparison-checklist-chart";
			$item     = "$fcta_loc/item.php";

			$title    = get_sub_field( 'heading' );
			$features = get_sub_field( 'features' );
			$id       = get_sub_field( 'section_id' );

			require $item;
		},
		'has_padding' => true,
	),
);
