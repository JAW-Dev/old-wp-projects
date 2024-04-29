<?php
require '../../wp-load.php';

global $wpdb;

$wpdb->query(
	$wpdb->prepare(
		"
		DELETE FROM wp_options
		WHERE option_name
		LIKE %s
		OR option_name LIKE %s
		OR option_name LIKE %s
		OR option_name LIKE %s
		OR option_name LIKE %s
		",
		'%_group_whitelabel_resource_transient%',
		'%_group_whitelabel_back_page_transient%',
		'%_whitelabel_resource_transient%',
		'%_whitelabel_back_page_transient%',
		'%_whitelabel_back_page_advanced_transient%'
	)
);
