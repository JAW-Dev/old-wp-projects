<?php

require '../../wp-load.php';


// TODO: REMOVE!
// error_log( ': ' . print_r( new \FP_Core\Member( 1994 ), true ) ); // phpcs:ignore

// die;

$mem_level = new \FP_Core\Integrations\Active_Campaign\Custom_Fields\Group_Membership_Level();
$mem_level->update_user( 1571 );

$group_name = new \FP_Core\Integrations\Active_Campaign\Custom_Fields\Group_Name();
$group_name->update_user( 1571 );

$ind_level = new \FP_Core\Integrations\Active_Campaign\Custom_Fields\Individual_Membership_Level();
$ind_level->update_user( 1571 );

$ind_level = new \FP_Core\Integrations\Active_Campaign\Custom_Fields\MasterList();
$ind_level->update_user( 1571 );

$access_level = new \FP_Core\Integrations\Active_Campaign\Custom_Fields\Membership_Access_Level();
$access_level->update_user( 1571 );

$expr_date = new \FP_Core\Integrations\Active_Campaign\Custom_Fields\Membership_Expiration_Date();
$expr_date->update_user( 1571 );

$start_date = new \FP_Core\Integrations\Active_Campaign\Custom_Fields\Membership_Start_Date();
$start_date->update_user( 1571 );

$start_date = new \FP_Core\Integrations\Active_Campaign\Custom_Fields\Membership_Status();
$start_date->update_user( 1571 );

\FP_Core\Integrations\Active_Campaign\Cron_Contact_Updater::process_updates();
