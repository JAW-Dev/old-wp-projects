<?php
require '../../wp/wp-load.php';

// [first_name] => Test [last_name] => 53

// Kitces\Includes\Classes\Chargebee\ChargebeeApi::get_user_by_email( 'rvzellera7+53@gmail.com' );


global $Kitces_ChargeBee_Connector;

d( $Kitces_ChargeBee_Connector->get_wp_role_from_ac( 'clif@objectiv.co' ) );
d( $Kitces_ChargeBee_Connector->get_wp_role_from_ac( '1000bc@gmail.com' ) );
d( $Kitces_ChargeBee_Connector->get_wp_role_from_ac( '4nadia@gmail.com' ) );
d( $Kitces_ChargeBee_Connector->get_wp_role_from_ac( 'abby.rose@keelernadler.com' ) );
