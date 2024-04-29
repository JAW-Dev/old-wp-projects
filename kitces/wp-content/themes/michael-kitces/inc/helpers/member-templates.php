<?php

function mk_get_current_user_membership_level_details() {
	$level = '';
	$label = 'Membership Level:';

	if ( kitces_is_valid_premier_member() ) {
		$level = 'Premier';
	} elseif ( kitces_is_valid_basic_member() ) {
		$level = 'Basic';
	} elseif ( kitces_is_valid_reader_member() ) {
		$level = 'Reader';
		$label = 'Level:';
	}

	return array(
		'label' => $label,
		'level' => $level,
	);
}
