<?php
/*
Plugin Name: Kitces SSO
Plugin URI: http://cgd.io
Description:  Adds shortcodes that return links for logging into third-party portals.
Version: 1.0.0
Author: CGD Inc.
Author URI: http://cgd.io

------------------------------------------------------------------------
Copyright 2009-2011 Clif Griffin Development Inc.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

require 'vendor/autoload.php';

class KitcesSSO {
	var $site = "kitces"; // "kitces-test";
	var $api_key = "live_rFgkk8fXHznL4tvIWdavqz0HQROD6cdFF"; // "test_I0bJ2FtqnsZAF9XAZiRcuJcd9KRbcByOU1";
	var $ac_api_url;
	var $ac_api_key;
	var $ac_api;

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init') );
	}

	function init() {
		global $Kitces_ChargeBee_Connector;

		$this->ac_api_url = $Kitces_ChargeBee_Connector->ac_api_url;
		$this->ac_api_key = $Kitces_ChargeBee_Connector->ac_api_key;
		$this->ac_api     = new ActiveCampaign( $this->ac_api_url, $this->ac_api_key );

		add_shortcode( 'chargebee_sso', array($this,'return_link_to_chargebee_login') );
	}

	function return_link_to_chargebee_login( $atts ) {

		$atts = shortcode_atts(
			array(
				'before' => '',
				'after' => '',
				'text' => 'Manage Subscription',
				'class' => '',
				'no_access' => '',
			),
			$atts, 'chargebee_sso'
		);

		$current_wp_user = wp_get_current_user();

		$contact = $this->ac_api->api( 'contact/view?email=' . urlencode( $current_wp_user->user_email ) );

		$chargebee_id = false;

		if ( $contact->success !== 1 ) {
			return $atts['no_access'];
		}

		foreach ( $contact->fields as $field ) {
			if ( $field->perstag === 'CHARGEBEE_ID' ) {
				$chargebee_id = $field->val;
				break;
			}
		}

		if ( empty( $chargebee_id ) ) {
			$chargebee    = new Kitces\Includes\Classes\Chargebee\ChargebeeApi();
			$customer     = $chargebee->get_user_by_email( $current_wp_user->user_email );
			$chargebee_id = '';

			if ( ! empty( $customer ) ) {
				$values = $customer->getValues();
			}

			if ( ! empty( $values['id'] ) ) {
				$chargebee_id = $values['id'];

				( new Kitces_Members\Includes\Classes\ActiveCampaign\CustomFields() )->save_field( 'CHARGEBEE_ID', $chargebee_id );
			}
		}

		if ( empty( $chargebee_id ) ) {
			return $atts['no_access'];
		}

		ChargeBee_Environment::configure( $this->site, $this->api_key );

		try {
			$result = ChargeBee_PortalSession::create(
				array(
					'redirectUrl' => get_home_url(),   // URL to redirect when the user logs out from the portal.
					'customer'    => array(
						'id' => $chargebee_id  // "cbdemo_8avV5PKqkkY1H"
					)
				)
			);

			$portalSession = $result->portalSession();
		} catch ( Exception $e ) {
			var_dump($e);
			return $atts['no_access'];
		}

		$result = "<a class='" . $atts['class']. "' href={$portalSession->accessUrl}>" . $atts['text'] . "</a>";

		$result = $atts['before'] . $result;
		$result = $result . $atts['after'];

		return $result;
	}
}

$KitcesSSO = new KitcesSSO();
