<?php
/*
Plugin Name: Kitces ChargeBee Connector
Plugin URI: http://cgd.io
Description:  Provides endpoint integration between ChargeBee, Memberium, and InfusionSoft.
Version: 1.1.0
Author: CGD Inc.
Author URI: http://cgd.io

------------------------------------------------------------------------
Copyright 2009-2015 Clif Griffin Development Inc.

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

use Carbon\Carbon;

class Kitces_ChargeBee_Connector {
	var $endpoints = array();
	var $countries = array(
		'AF' => 'Afghanistan',
		'AX' => 'Aland Islands',
		'AL' => 'Albania',
		'DZ' => 'Algeria',
		'AS' => 'American Samoa',
		'AD' => 'Andorra',
		'AO' => 'Angola',
		'AI' => 'Anguilla',
		'AQ' => 'Antarctica',
		'AG' => 'Antigua And Barbuda',
		'AR' => 'Argentina',
		'AM' => 'Armenia',
		'AW' => 'Aruba',
		'AU' => 'Australia',
		'AT' => 'Austria',
		'AZ' => 'Azerbaijan',
		'BS' => 'Bahamas',
		'BH' => 'Bahrain',
		'BD' => 'Bangladesh',
		'BB' => 'Barbados',
		'BY' => 'Belarus',
		'BE' => 'Belgium',
		'BZ' => 'Belize',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BT' => 'Bhutan',
		'BO' => 'Bolivia',
		'BA' => 'Bosnia And Herzegovina',
		'BW' => 'Botswana',
		'BV' => 'Bouvet Island',
		'BR' => 'Brazil',
		'IO' => 'British Indian Ocean Territory',
		'BN' => 'Brunei Darussalam',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'CV' => 'Cape Verde',
		'KY' => 'Cayman Islands',
		'CF' => 'Central African Republic',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CX' => 'Christmas Island',
		'CC' => 'Cocos (Keeling) Islands',
		'CO' => 'Colombia',
		'KM' => 'Comoros',
		'CG' => 'Congo',
		'CD' => 'Congo, Democratic Republic',
		'CK' => 'Cook Islands',
		'CR' => 'Costa Rica',
		'CI' => 'Cote D\'Ivoire',
		'HR' => 'Croatia',
		'CU' => 'Cuba',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DK' => 'Denmark',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'EC' => 'Ecuador',
		'EG' => 'Egypt',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'ER' => 'Eritrea',
		'EE' => 'Estonia',
		'ET' => 'Ethiopia',
		'FK' => 'Falkland Islands (Malvinas)',
		'FO' => 'Faroe Islands',
		'FJ' => 'Fiji',
		'FI' => 'Finland',
		'FR' => 'France',
		'GF' => 'French Guiana',
		'PF' => 'French Polynesia',
		'TF' => 'French Southern Territories',
		'GA' => 'Gabon',
		'GM' => 'Gambia',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GU' => 'Guam',
		'GT' => 'Guatemala',
		'GG' => 'Guernsey',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HM' => 'Heard Island & Mcdonald Islands',
		'VA' => 'Holy See (Vatican City State)',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IR' => 'Iran, Islamic Republic Of',
		'IQ' => 'Iraq',
		'IE' => 'Ireland',
		'IM' => 'Isle Of Man',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JE' => 'Jersey',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'KR' => 'Korea',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyzstan',
		'LA' => 'Lao People\'s Democratic Republic',
		'LV' => 'Latvia',
		'LB' => 'Lebanon',
		'LS' => 'Lesotho',
		'LR' => 'Liberia',
		'LY' => 'Libyan Arab Jamahiriya',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macao',
		'MK' => 'Macedonia',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MH' => 'Marshall Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'YT' => 'Mayotte',
		'MX' => 'Mexico',
		'FM' => 'Micronesia, Federated States Of',
		'MD' => 'Moldova',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'ME' => 'Montenegro',
		'MS' => 'Montserrat',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'MM' => 'Myanmar',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'NL' => 'Netherlands',
		'AN' => 'Netherlands Antilles',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger',
		'NG' => 'Nigeria',
		'NU' => 'Niue',
		'NF' => 'Norfolk Island',
		'MP' => 'Northern Mariana Islands',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PW' => 'Palau',
		'PS' => 'Palestinian Territory, Occupied',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines',
		'PN' => 'Pitcairn',
		'PL' => 'Poland',
		'PT' => 'Portugal',
		'PR' => 'Puerto Rico',
		'QA' => 'Qatar',
		'RE' => 'Reunion',
		'RO' => 'Romania',
		'RU' => 'Russian Federation',
		'RW' => 'Rwanda',
		'BL' => 'Saint Barthelemy',
		'SH' => 'Saint Helena',
		'KN' => 'Saint Kitts And Nevis',
		'LC' => 'Saint Lucia',
		'MF' => 'Saint Martin',
		'PM' => 'Saint Pierre And Miquelon',
		'VC' => 'Saint Vincent And Grenadines',
		'WS' => 'Samoa',
		'SM' => 'San Marino',
		'ST' => 'Sao Tome And Principe',
		'SA' => 'Saudi Arabia',
		'SN' => 'Senegal',
		'RS' => 'Serbia',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SK' => 'Slovakia',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'SO' => 'Somalia',
		'ZA' => 'South Africa',
		'GS' => 'South Georgia And Sandwich Isl.',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'SD' => 'Sudan',
		'SR' => 'Suriname',
		'SJ' => 'Svalbard And Jan Mayen',
		'SZ' => 'Swaziland',
		'SE' => 'Sweden',
		'CH' => 'Switzerland',
		'SY' => 'Syrian Arab Republic',
		'TW' => 'Taiwan',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania',
		'TH' => 'Thailand',
		'TL' => 'Timor-Leste',
		'TG' => 'Togo',
		'TK' => 'Tokelau',
		'TO' => 'Tonga',
		'TT' => 'Trinidad And Tobago',
		'TN' => 'Tunisia',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'TC' => 'Turks And Caicos Islands',
		'TV' => 'Tuvalu',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'GB' => 'United Kingdom',
		'US' => 'United States',
		'UM' => 'United States Outlying Islands',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VE' => 'Venezuela',
		'VN' => 'Viet Nam',
		'VG' => 'Virgin Islands, British',
		'VI' => 'Virgin Islands, U.S.',
		'WF' => 'Wallis And Futuna',
		'EH' => 'Western Sahara',
		'YE' => 'Yemen',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe',
	);
	var $site      = 'kitces'; //'kitces-test';
	var $api_key   = 'live_rFgkk8fXHznL4tvIWdavqz0HQROD6cdFF'; //'test_I0bJ2FtqnsZAF9XAZiRcuJcd9KRbcByOU1';
	var $plan_tags;
	var $debug      = true;
	var $ac_api_url = 'https://kitces.api-us1.com';
	var $ac_api_key = 'befc79f7a64aad8af849ad6f8d417c99d00b879807377d39ab36d98f45e05738161a83c3';
	var $ac_api     = false;

	public function __construct() {
		// Composer
		require_once 'vendor/autoload.php';

		$this->ac_api = new ActiveCampaign( $this->ac_api_url, $this->ac_api_key );

		if ( ! class_exists( 'ChargeBee' ) ) {
			return;
		}

        $this->endpoints['customer_created'] = array(
            'description' => 'Runs when customer is created. Creates ActiveCampaign Contact.',
        );

		$this->endpoints['customer_changed'] = array(
			'description' => 'Runs when customer data is updated. Updates ActiveCampaign Contact.',
		);

		$this->endpoints['subscription_created'] = array(
			'description' => 'Runs when a new customer subscription is created.',
		);

		$this->endpoints['subscription_cancelled'] = array(
			'description' => 'Runs when a customer subscription is cancelled.',
		);

		$this->endpoints['subscription_cancellation_scheduled'] = array(
			'description' => 'Runs when a customer schedules a subscription cancellation.',
		);

		$this->endpoints['subscription_renewed'] = array(
			'description' => 'Handles customer subscription renewal.',
		);

		$this->endpoints['payment_succeeded'] = array(
			'description' => 'Handles customer subscription renewal.',
		);

		$this->endpoints['subscription_changed'] = array(
			'description' => 'Runs when a customer subscription is changed.',
		);

		$this->endpoints = apply_filters( 'kitces_chargebee_connector_endpoints', $this->endpoints );

		// Plan ID Mapping
		$this->plan_tags = array(
			'kitces-report-autopilot' => array(
				'id'         => 'MemberAdmin-NewsletterMember',
				'payf'       => 'MemberAdmin-NewsletterMemberPAYF',
				'renewed'    => 'MemberAdmin-Kitces-Report-Renewed',
				'lapsed'     => 'MemberAdmin-Newsletter-Member-Lapsed',
				'paying'     => 'MemberType-Paying-Premier-Member',
				'selfcancel' => 'Reports-Self-Cancelled',
			),
			'kitces-basic-member'     => array(
				'id'      => 'MemberAdmin-Basic',
				'payf'    => 'MemberAdmin-Basic-PAYF',
				'renewed' => 'MemberAdmin-Basic-Membership-Renewed',
				'lapsed'  => 'MemberAdmin-Basic-Member-Lapsed',
				'paying'  => 'MemberType-Paying-Basic-Member',
			),
			'student'                 => array(
				'id'      => 'MemberAdmin-Student',
				'payf'    => 'MemberAdmin-StudentPAYF',
				'renewed' => 'MemberAdmin-Student-Renewed',
				'lapsed'  => 'MemberAdmin-Student-Lapsed',
				'paying'  => 'MemberType-Paying-Student-Member',
			),
            // This is not a ChargeBee Connected role and it really is bad form to have it here but it's the band-aid
            'reader'                 => array(
                'id'      => 'MemberAdmin-Reader',
                'payf'    => 'MemberAdmin-ReaderPAYF',
                'renewed' => 'MemberAdmin-Reader-Renewed',
                'lapsed'  => 'MemberAdmin-Reader-Lapsed',
                'paying'  => 'MemberType-Paying-Reader-Member',
            ),
		);

		// Endpoint Receiver
		add_action( 'wp_ajax_new_chargebee_event', array( $this, 'new_event' ) );
		add_action( 'wp_ajax_nopriv_new_chargebee_event', array( $this, 'new_event' ) );

		// Process Endpoints
		add_action( 'init', array( $this, 'process_endpoint_hooks' ) );
	}

	public function process_endpoint_hooks() {
		if ( count( $this->endpoints ) == 0 ) {
			return;
		}

		foreach ( $this->endpoints as $event => $ed ) {
			if ( is_callable( array( $this, $event ) ) ) {
				add_action( 'kitces_chargebee_event_' . $event, array( $this, $event ), 10, 2 );
			}
		}
	}

	public function new_event() {
		$request_body = file_get_contents( 'php://input' );

		if ( empty( $request_body ) ) {
			wp_die( 0 );
		}

		$request_body = json_decode( $request_body );

		$result = false;

		if ( $this->debug ) {
			error_log( 'Webhook Event Type: ' . $request_body->event_type );
			error_log( 'Webhook Event Object: ' . print_r( $request_body, true ) );
		}

		do_action_ref_array( 'kitces_chargebee_event_' . $request_body->event_type, array( $request_body, &$result ) );

		if ( $result == false ) {
			http_response_code( 200 ); // if we send 404 here, all unhandled webhooks get rescheduled and sent ad nauseum
		}

		var_dump( $result );
	}

	function customer_created( $event, &$result = false ) {

		// Add or Update Contact
		$contact_data = array(
			'email'                   => $event->content->customer->email,
			'first_name'              => $event->content->customer->first_name,
			'last_name'               => $event->content->customer->last_name,
			'field[%CHARGEBEE_ID%,0]' => $event->content->customer->id,
			'phone'                   => $event->content->customer->phone,
			'p[6]'                    => 6,
			'status[6]'               => 1,
            'p[1]'                    => 1,
            'status[1]'               => 1,
        );

		if ( ! empty( $event->content->customer->billing_address ) ) {
			$contact_data['field[%BILLING_ADDRESS_1%,0]']      = $event->content->customer->billing_address->line1;
			$contact_data['field[%BILLING_ADDRESS_2%,0]']      = isset( $event->content->customer->billing_address->line2 ) ? $event->content->customer->billing_address->line2 : '';
			$contact_data['field[%BILLING_CITY%,0]']           = $event->content->customer->billing_address->city;
			$contact_data['field[%BILLING_STATE_PROVINCE%,0]'] = $event->content->customer->billing_address->state_code;
			$contact_data['field[%BILLING_POSTAL_CODE%,0]']    = $event->content->customer->billing_address->zip;
			$contact_data['field[%BILLING_COUNTRY%,0]']        = $event->content->customer->billing_address->country;

			// Also put in address 3
			$contact_data['field[%OTHER_ADDRESS_1%,0]']   = $event->content->customer->billing_address->line1;
			$contact_data['field[%OTHER_ADDRESS_2%,0]']   = isset( $event->content->customer->billing_address->line2 ) ? $event->content->customer->billing_address->line2 : '';
			$contact_data['field[%OTHER_CITY%,0]']        = $event->content->customer->billing_address->city;
			$contact_data['field[%OTHER_STATE%,0]']       = $event->content->customer->billing_address->state_code;
			$contact_data['field[%OTHER_POSTAL_CODE%,0]'] = $event->content->customer->billing_address->zip;
			$contact_data['field[%OTHER_COUNTRY%,0]']     = $event->content->customer->billing_address->country;
		}

		$numbers_updated = false;

		// CFP
		if ( ! empty( $event->content->customer->cf_cfp_ce ) ) {
			$cfp_ce_number_key                             = urlencode( '%CFP_CE_NUMBER%' );
			$contact_data[ "field[$cfp_ce_number_key,0]" ] = $event->content->customer->cf_cfp_ce;
			$numbers_updated                               = true;
		}

		// IMCA
		if ( ! empty( $event->content->customer->cf_imca_ce ) ) {
			$contact_data['field[%IMCA_CE_NUMBER%,0]'] = $event->content->customer->cf_imca_ce;
			$numbers_updated                           = true;
		}

		// CPA
		if ( ! empty( $event->content->customer->cf_cpa_cpe ) ) {
			$contact_data['field[%CPA_CE_NUMBER%,0]'] = $event->content->customer->cf_cpa_cpe;
			$numbers_updated                          = true;
		}

		// PTIN
		if ( ! empty( $event->content->customer->cf_ptin_ce ) ) {
			$contact_data['field[%PTIN_CE_NUMBER%,0]'] = $event->content->customer->cf_ptin_ce;
			$numbers_updated                           = true;
		}

		// ACC
		if ( ! empty( $event->content->customer->cf_acc ) ) {
			$contact_data['field[%AMERICAN_COLLEGE_ID%,0]'] = $event->content->customer->cf_acc;
			$numbers_updated                                = true;
		}

		// IAR
		if ( ! empty( $event->content->customer->cf_iar_ce ) ) {
			$contact_data['field[%IAR_CE_NUMBER%,0]'] = $event->content->customer->cf_iar_ce;
			$numbers_updated                           = true;
		}

		// Employer
		if ( ! empty( $event->content->customer->cf_employer ) ) {
			$contact_data['field[%EMPLOYER%,0]'] = $event->content->customer->cf_employer;
		}

		if ( $numbers_updated ) {
			$contact_data['field[%IMCA_CE_SUBMITTED%,0]'] = 'SUBMITTED';
		}

		$api_result = $this->ac_api->api( 'contact/sync', $contact_data );
		error_log( 'ChargeBee Connector API Result: ' . var_export( $api_result, true ) );

		$result = $api_result;

		return $result;
	}


	function customer_changed( $event, &$result ) {

		// Setup ChargeBee
		ChargeBee_Environment::configure( $this->site, $this->api_key );

		// Find existing AC contact
		$contact_list_result = $this->ac_api->api( 'contact/list?filters[fields][%CHARGEBEE_ID%]=' . $event->content->customer->id );

		// Found a contact
		if ( $contact_list_result->success === 1 ) {
			$found_contact = current( $contact_list_result );

			// Retrieve ChargeBee customer based on customer id sent in webhook
			$retrieve          = ChargeBee_Customer::retrieve( $event->content->customer->id );
			$chargeBeeCustomer = $retrieve->customer();

			$contact_data = array(
				'id'         => $found_contact->id,
				'email'      => $chargeBeeCustomer->email,
				'first_name' => $chargeBeeCustomer->firstName,
				'last_name'  => $chargeBeeCustomer->lastName,
				'p[6]'       => 6,
				'status[6]'  => 1,
			);

			if ( ! empty( $event->content->customer->billing_address ) ) {
				$contact_data['field[%BILLING_ADDRESS_1%,0]']      = $event->content->customer->billing_address->line1;
				$contact_data['field[%BILLING_ADDRESS_2%,0]']      = isset( $event->content->customer->billing_address->line2 ) ? $event->content->customer->billing_address->line2 : '';
				$contact_data['field[%BILLING_CITY%,0]']           = $event->content->customer->billing_address->city;
				$contact_data['field[%BILLING_STATE_PROVINCE%,0]'] = $event->content->customer->billing_address->state_code;
				$contact_data['field[%BILLING_POSTAL_CODE%,0]']    = $event->content->customer->billing_address->zip;
				$contact_data['field[%BILLING_COUNTRY%,0]']        = $event->content->customer->billing_address->country;

				// Also put in address 3
				$contact_data['field[%OTHER_ADDRESS_1%,0]']   = $event->content->customer->billing_address->line1;
				$contact_data['field[%OTHER_ADDRESS_2%,0]']   = isset( $event->content->customer->billing_address->line2 ) ? $event->content->customer->billing_address->line2 : '';
				$contact_data['field[%OTHER_CITY%,0]']        = $event->content->customer->billing_address->city;
				$contact_data['field[%OTHER_STATE%,0]']       = $event->content->customer->billing_address->state_code;
				$contact_data['field[%OTHER_POSTAL_CODE%,0]'] = $event->content->customer->billing_address->zip;
				$contact_data['field[%OTHER_COUNTRY%,0]']     = $event->content->customer->billing_address->country;
			}

			$numbers_updated = false;

			// CFP
			if ( ! empty( $event->content->customer->cf_cfp_ce ) ) {
				$cfp_ce_number_key                             = urlencode( '%CFP_CE_NUMBER%' );
				$contact_data[ "field[$cfp_ce_number_key,0]" ] = $event->content->customer->cf_cfp_ce;
				$numbers_updated                               = true;
			}

			// IMCA
			if ( ! empty( $event->content->customer->cf_imca_ce ) ) {
				$contact_data['field[%IMCA_CE_NUMBER%,0]'] = $event->content->customer->cf_imca_ce;
				$numbers_updated                           = true;
			}

			// CPA
			if ( ! empty( $event->content->customer->cf_cpa_cpe ) ) {
				$contact_data['field[%CPA_CE_NUMBER%,0]'] = $event->content->customer->cf_cpa_cpe;
				$numbers_updated                          = true;
			}

			// PTIN
			if ( ! empty( $event->content->customer->cf_ptin_ce ) ) {
				$contact_data['field[%PTIN_CE_NUMBER%,0]'] = $event->content->customer->cf_ptin_ce;
				$numbers_updated                           = true;
			}

			// ACC
			if ( ! empty( $event->content->customer->cf_acc ) ) {
				$contact_data['field[%AMERICAN_COLLEGE_ID%,0]'] = $event->content->customer->cf_acc;
				$numbers_updated                                = true;
			}

			// Employer
			if ( ! empty( $event->content->customer->cf_employer ) ) {
				$contact_data['field[%EMPLOYER%,0]'] = $event->content->customer->cf_employer;
			}

			// IAR
			if ( ! empty( $event->content->customer->cf_iar_ce ) ) {
				$contact_data['field[%IAR_CE_NUMBER%,0]'] = $event->content->customer->cf_iar_ce;
				$numbers_updated                                = true;
			}
			
			if ( $numbers_updated ) {
				$contact_data['field[%IMCA_CE_SUBMITTED%,0]'] = 'SUBMITTED';
			}

			$api_result = $this->ac_api->api( 'contact/edit', $contact_data );

			$result = $api_result;
		}
	}

	function subscription_cancelled( $event, &$result ) {
		if ( ! isset( $this->plan_tags[ $event->content->subscription->plan_id ] ) ) {
			return; // no matching subscription
		}

		$contact_data = array(
			'email' => $event->content->customer->email,
			'tags'  => array(),
		);

		$nonpayment = false;
		if ( isset( $event->content->subscription->cancel_reason ) && in_array( $event->content->subscription->cancel_reason, array( 'not_paid', 'no_card' ) ) ) {
			$nonpayment = true;
		}

		//If this contact's plan_id contains a lapsed field
		if ( $this->plan_tags[ $event->content->subscription->plan_id ]['lapsed'] && $nonpayment ) {
			$contact_data['tags'][] = $this->plan_tags[ $event->content->subscription->plan_id ]['lapsed'];
		}

		// Add self-cancel tag
		if ( $event->source == 'portal' && isset( $this->plan_tags[ $event->content->subscription->plan_id ]['selfcancel'] ) ) {
			$contact_data['tags'][] = $this->plan_tags[ $event->content->subscription->plan_id ]['selfcancel'];
		}

		$contact_data['tags'][] = $this->plan_tags[ $event->content->subscription->plan_id ]['payf'];

        $this->update_wp_user(
            $event->content->customer->email,
            $event->content->customer->first_name,
            $event->content->customer->last_name,
            $this->get_wp_role_from_ac(
                $event->content->customer->email,
                $contact_data['tags']
            )
        );

		$result = $this->ac_api->api( 'contact/tag_add', $contact_data );
	}

	function subscription_cancellation_scheduled( $event, &$result ) {
		if ( ! isset( $this->plan_tags[ $event->content->subscription->plan_id ] ) ) {
			return; // no matching subscription
		}

		$contact_data = array(
			'email' => $event->content->customer->email,
			'tags'  => array(),
		);

		// Add self-cancel tag
		if ( $event->source == 'portal' && isset( $this->plan_tags[ $event->content->subscription->plan_id ]['selfcancel'] ) ) {
			$contact_data['tags'][] = $this->plan_tags[ $event->content->subscription->plan_id ]['selfcancel'];
		}

		if ( ! empty( $contact_data['tags'] ) ) {
			$result = $this->ac_api->api( 'contact/tag_add', $contact_data );
		}
	}

	function payment_succeeded( $event, &$result ) {
		if ( $event->content->invoice->first_invoice == 'true' ) {
			return; // only run for recurring payments
		}

		$this->subscription_renewed( $event, $result );
	}

	function subscription_renewed( $event, &$result ) {
		if ( ! isset( $this->plan_tags[ $event->content->subscription->plan_id ] ) ) {
			return; // no matching subscription.
		}

		if ( $event->content->subscription->due_invoices_count > 0 ) {
			return; // if this subscription isn't paid, bail.
		}

		$result = array();

		$contact_update_data = array(
			'email'                      => $event->content->customer->email,
			'field[%EXPIRATION_DATE%,0]' => date( 'Ymd\TH:i:s', $event->content->subscription->current_term_end ),
		);

		$year                = date( 'Y' );
		$expired_date        = date( 'F-Y', $event->content->subscription->current_term_end );
		$renewed_group_name  = "Renewed Subscription ($year)";
		$expiration_tag_name = "Reports-MemberRenewal-$expired_date-Expiration";

		$contact_tag_add = array(
			$this->plan_tags[ $event->content->subscription->plan_id ]['id'],
			$this->plan_tags[ $event->content->subscription->plan_id ]['renewed'],
			$renewed_group_name,
		);

		$contact_tag_remove = array(
			$this->plan_tags[ $event->content->subscription->plan_id ]['payf'],
			$this->plan_tags[ $event->content->subscription->plan_id ]['lapsed'],
		);

		if ( $this->plan_tags[ $event->content->subscription->plan_id ]['id'] === 'MemberAdmin-NewsletterMember' ) {
			// Add new tags to AC.
			$this->add_new_ac_tag( $renewed_group_name );
			$this->add_new_ac_tag( $expiration_tag_name );

			array_push( $contact_tag_add, $expiration_tag_name );
		}

		$result[] = $this->ac_api->api(
			'contact/tag_add',
			array(
				'email' => $event->content->customer->email,
				'tags'  => $contact_tag_add,
			)
		);

		// Remove Expired Tag in case it exists.
		$result[] = $this->ac_api->api(
			'contact/tag_remove',
			array(
				'email' => $event->content->customer->email,
				'tags'  => $contact_tag_remove,
			)
		);

		$result[] = $this->ac_api->api( 'contact/sync', $contact_update_data );
	}

    /**
     * @param string $email
     * @param string|null $first_name
     * @param string|null $last_name
     * @param string $role
     * @return bool
     */
	function update_wp_user( string $email, ?string $first_name, ?string $last_name, string $role, bool $force_reset_password = false ): bool {
		$current_user = get_user_by( 'email', $email );

		$id          = $current_user ? $current_user->ID : false;
		$random_pass = wp_generate_password( 8, false, false );

		$user_data = array(
			'ID'           => $id,
			'user_login'   => $email,
			'user_email'   => $email,
			'display_name' => "$first_name $last_name",
			'first_name'   => $first_name,
			'last_name'    => $last_name,
		);

        error_log( 'ChargeBee Connector $current_user: ' . var_export( $current_user, true ) );
        error_log( 'ChargeBee Connector $force_reset_password: ' . var_export( $force_reset_password, true ) );

		if ( ! $current_user || $force_reset_password ) {
		    error_log( 'Kitces ChargeBee Connector: Resetting password' );
			$user_data['user_pass'] = $random_pass;

            $date = Carbon::now()->addDay(); // 24 hours after now
			$key           = sha1( $random_pass . $email . uniqid( time(), true ) );
			$reset_page_id = function_exists( 'get_field' ) ? get_field( 'kitces_new_member_reset_link_page', 'option' ) : 0;

			$url = add_query_arg(
				array(
					'nmb_key' => $key,
					'nmb'     => $current_user,
				),
				get_the_permalink( $reset_page_id )
			);


			$contact_update = array(
				'email'               => $email,
				'field[%PASSWORD%,0]' => $url,
			);

            $ac_result = $this->ac_api->api( 'contact/sync', $contact_update );
            error_log( 'ChargeBee Connector API Password Sync Result: ' . var_export( $ac_result, true ) );

			update_user_meta( $current_user, 'member_activation_key', $key );
			update_user_meta( $current_user, 'member_activation_key_expiry', $date->timestamp );
		}

		$result = wp_insert_user( $user_data );

        error_log( 'ChargeBee Connector User Update Result: ' . var_export( $result, true ) );

		if ( is_a( $result, 'WP_Error') ) {
		    return false;
        }

		$user = get_user_by( 'id', $result );

        $member_roles = array(
            'subscriber',
            'premier',
            'basic',
            'student',
			'reader',
        );

        foreach( $member_roles as $member_role ) {
            $user->remove_role( $member_role );
        }

        $user->add_role( $role );

		if ( ! $current_user || $force_reset_password ) {
		    update_user_meta( $result, 'needs_password_reset', true );
        }

		return true;
	}

    /**
     * @param string $email
     * @param array|null $tags
     * @return string
     */
	public function get_wp_role_from_ac( string $email, array $tags = null ): string {
		$default_role = 'reader';
		$plan_map     = array(
			'kitces-report-autopilot' => 'premier',
			'kitces-basic-member'     => 'basic',
			'student'                 => 'student',
			'reader'                  => 'reader',
		);

		if ( is_null( $tags ) ) {
            $contact = $this->ac_api->api( "contact/view?email={$email}" );
		    $tags    = $contact->tags;
        }

		$plan = false;

		if ( ! is_array( $tags ) ) {
			return $default_role;
		}

		foreach ( $this->plan_tags as $plan_name => $plan_tags ) {
			if ( in_array( $plan_tags['id'], $tags, true ) && ! in_array( $plan_tags['payf'], $tags, true ) ) {
				$plan = $plan_name;
				break;
			}
		}

		if ( ! $plan ) {
			return $default_role;
		}

		return $plan_map[ $plan ];
	}

	/**
	 * Add New AC Tag
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $tag_name The tag to add.
	 *
	 * @return void
	 */
	public function add_new_ac_tag( $tag_name ) {

		// Bail if no tag name set.
		if ( empty( $tag_name ) ) {
			return;
		}

		// Get the array of existing tags.
		$existing_tags = json_decode( $this->ac_api->api( 'tags/list' ) );

		if ( ! empty( $existing_tags ) ) {

			// Build a temp array for looping through existing tag names.
			$existing_tag_names = array();
			foreach ( $existing_tags as $tag ) {
				if ( ! in_array( $tag->name, $existing_tag_names, true ) ) {
					$existing_tag_names[] = $tag->name;
				}
			}

			// If the tag name isn't in the existing tags array
			// create a new tag.
			if ( ! in_array( $tag_name, $existing_tag_names, true ) ) {
				$new_tag = array(
					'tag' => array(
						'tag'     => $tag_name,
						'tagType' => 'contact',
					),
				);

				// AC API doesn't have a method for creating tags
				// Use wp_remote_post to do a direct API post.
				$response = wp_remote_post(
					$this->ac_api_url . '/api/3/tags?api_key=' . $this->ac_api_key,
					array( 'body' => wp_json_encode( $new_tag ) )
				);
			}
		}
	}

	function subscription_created( $event, &$result ) {
		if ( ! isset( $this->plan_tags[ $event->content->subscription->plan_id ] ) ) {
			return; // no matching subscription
		}

		$result = array();

		$contact_data = array(
			'email'                      => $event->content->customer->email,
			'first_name'                 => $event->content->customer->first_name,
			'last_name'                  => $event->content->customer->last_name,
			'field[%CHARGEBEE_ID%,0]'    => $event->content->customer->id,
			'phone'                      => $event->content->customer->phone,
			'field[%EXPIRATION_DATE%,0]' => date( 'Ymd\TH:i:s', $event->content->subscription->current_term_end ),
			'field[%START_DATE%,0]'      => date( 'Ymd\TH:i:s', $event->content->subscription->current_term_start ),
		);

		$expired_date        = date( 'F-Y', $event->content->subscription->current_term_end );
		$expiration_tag_name = "Reports-MemberRenewal-$expired_date-Expiration";
		$returning_tag_name  = 'MemberAdmin-ReturningMember';

		$numbers_updated = false;

		$returning_customer = false;
		$has_old_account    = get_user_by( 'email', $event->content->customer->email );

		if ( false !== $has_old_account ) {
			$returning_customer = true;
		}

		// CFP
		if ( ! empty( $event->content->customer->cf_cfp_ce ) ) {
			$cfp_ce_number_key                             = urlencode( '%CFP_CE_NUMBER%' );
			$contact_data[ "field[$cfp_ce_number_key,0]" ] = $event->content->customer->cf_cfp_ce;
			$numbers_updated                               = true;
		}

		// IMCA
		if ( ! empty( $event->content->customer->cf_imca_ce ) ) {
			$contact_data['field[%IMCA_CE_NUMBER%,0]'] = $event->content->customer->cf_imca_ce;
			$numbers_updated                           = true;
		}

		// CPA
		if ( ! empty( $event->content->customer->cf_cpa_cpe ) ) {
			$contact_data['field[%CPA_CE_NUMBER%,0]'] = $event->content->customer->cf_cpa_cpe;
			$numbers_updated                          = true;
		}

		// PTIN
		if ( ! empty( $event->content->customer->cf_ptin_ce ) ) {
			$contact_data['field[%PTIN_CE_NUMBER%,0]'] = $event->content->customer->cf_ptin_ce;
			$numbers_updated                           = true;
		}

		// ACC
		if ( ! empty( $event->content->customer->cf_acc ) ) {
			$contact_data['field[%AMERICAN_COLLEGE_ID%,0]'] = $event->content->customer->cf_acc;
			$numbers_updated                                = true;
		}

		// IAR
		if ( ! empty( $event->content->customer->cf_iar_ce ) ) {
			$contact_data['field[%IAR_CE_NUMBER%,0]'] = $event->content->customer->cf_iar_ce;
			$numbers_updated                                = true;
		}

		// Employer
		if ( ! empty( $event->content->customer->cf_employer ) ) {
			$contact_data['field[%EMPLOYER%,0]'] = $event->content->customer->cf_employer;
		}

		if ( $numbers_updated ) {
			$contact_data['field[%IMCA_CE_SUBMITTED%,0]'] = 'SUBMITTED';
		}

		$result[] = $this->ac_api->api( 'contact/sync', $contact_data );

		$contact_tag_add = array(
			$this->plan_tags[ $event->content->subscription->plan_id ]['id'],
		);

		$contact_tag_remove = array(
			$this->plan_tags[ $event->content->subscription->plan_id ]['payf'],
			$this->plan_tags[ $event->content->subscription->plan_id ]['lapsed'],
		);

		$contact = $this->ac_api->api( "contact/view?email={$event->content->customer->email}" );

		// Add Membership Tag and / or Renewed tag
		if ( in_array( $this->plan_tags[ $event->content->subscription->plan_id ]['id'], $contact->tags ) ) {
			// If they don't have PayF tag, notify Rachel by adding "Purchased Duplicate Subscription" tag
			if ( ! in_array( $this->plan_tags[ $event->content->subscription->plan_id ]['payf'], $contact->tags ) ) {
				//$contact_tag_add[] = 'MemberAdmin-PurchasedDuplicateSubscription';
			}
			$contact_tag_add[] = $this->plan_tags[ $event->content->subscription->plan_id ]['renewed'];
		} else {
			$contact_tag_add[] = $this->plan_tags[ $event->content->subscription->plan_id ]['id'];
		}

        // Add Paying Tag
        if ( ! in_array( $this->plan_tags[ $event->content->subscription->plan_id ]['paying'], $contact->tags ) ) {
            $contact_tag_add[] = $this->plan_tags[ $event->content->subscription->plan_id ]['paying'];
        }

        // Remove PayF
        if ( in_array( $this->plan_tags[ $event->content->subscription->plan_id ]['payf'], $contact->tags ) ) {
            $contact_tag_remove[] = $this->plan_tags[ $event->content->subscription->plan_id ]['payf'];
        }

		// Handle Inside Info Combo
		if ( $event->content->invoice->sub_total == 39900 && $event->content->subscription->plan_id == 'kitces-report-autopilot' ) {
			$contact_tag_add[] = 'MemberType-Inside-Info-Combo';
		}

		// Remove Lasped
		if ( $this->plan_tags[ $event->content->subscription->plan_id ]['lapsed'] && in_array( $this->plan_tags[ $event->content->subscription->plan_id ]['lapsed'], $contact->tags ) ) {
			$contact_tag_remove[] = $this->plan_tags[ $event->content->subscription->plan_id ]['lapsed'];
		}

		if ( $this->plan_tags[ $event->content->subscription->plan_id ]['id'] === 'MemberAdmin-NewsletterMember' ) {
			// Add new tags to AC.
			$this->add_new_ac_tag( $expiration_tag_name );
			array_push( $contact_tag_add, $expiration_tag_name );
		}

		if ( $returning_customer ) {
			// Add new tags to AC.
			$this->add_new_ac_tag( $returning_tag_name );
			array_push( $contact_tag_add, $returning_tag_name );
		}

        $this->update_wp_user(
            $event->content->customer->email,
            $event->content->customer->first_name,
            $event->content->customer->last_name,
            $this->get_wp_role_from_ac(
                $event->content->customer->email,
                $contact_tag_add ),
            true
        );

		$result[] = $this->ac_api->api(
			'contact/tag_add',
			array(
				'email' => $event->content->customer->email,
				'tags'  => $contact_tag_add,
			)
		);

		// Remove Expired Tag in case it exists
		$result[] = $this->ac_api->api(
			'contact/tag_remove',
			array(
				'email' => $event->content->customer->email,
				'tags'  => $contact_tag_remove,
			)
		);
	}

	function subscription_changed( $event, &$result ) {
		// For now just add the right plan tags
		if ( ! isset( $this->plan_tags[ $event->content->subscription->plan_id ] ) ) {
			return; // no matching subscription
		}

		$result = array();

		$contact_data = array(
			'email'                      => $event->content->customer->email,
			'first_name'                 => $event->content->customer->first_name,
			'last_name'                  => $event->content->customer->last_name,
			'field[%CHARGEBEE_ID%,0]'    => $event->content->customer->id,
			'phone'                      => $event->content->customer->phone,
			'field[%EXPIRATION_DATE%,0]' => date( 'Ymd\TH:i:s', $event->content->subscription->current_term_end ),
			'field[%START_DATE%,0]'      => date( 'Ymd\TH:i:s', $event->content->subscription->current_term_start ),
		);

		$numbers_updated = false;

		// CFP
		if ( ! empty( $event->content->customer->cf_cfp_ce ) ) {
			$cfp_ce_number_key                             = urlencode( '%CFP_CE_NUMBER%' );
			$contact_data[ "field[$cfp_ce_number_key,0]" ] = $event->content->customer->cf_cfp_ce;
			$numbers_updated                               = true;
		}

		// IMCA
		if ( ! empty( $event->content->customer->cf_imca_ce ) ) {
			$contact_data['field[%IMCA_CE_NUMBER%,0]'] = $event->content->customer->cf_imca_ce;
			$numbers_updated                           = true;
		}

		// CPA
		if ( ! empty( $event->content->customer->cf_cpa_cpe ) ) {
			$contact_data['field[%CPA_CE_NUMBER%,0]'] = $event->content->customer->cf_cpa_cpe;
			$numbers_updated                          = true;
		}

		// PTIN
		if ( ! empty( $event->content->customer->cf_ptin_ce ) ) {
			$contact_data['field[%PTIN_CE_NUMBER%,0]'] = $event->content->customer->cf_ptin_ce;
			$numbers_updated                           = true;
		}

		// ACC
		if ( ! empty( $event->content->customer->cf_acc ) ) {
			$contact_data['field[%AMERICAN_COLLEGE_ID%,0]'] = $event->content->customer->cf_acc;
			$numbers_updated                                = true;
		}
		
		// IAR
		if ( ! empty( $event->content->customer->cf_iar_ce ) ) {
			$contact_data['field[%IAR_CE_NUMBER%,0]'] = $event->content->customer->cf_iar_ce;
			$numbers_updated                                = true;
		}

		// Employer
		if ( ! empty( $event->content->customer->cf_employer ) ) {
			$contact_data['field[%EMPLOYER%,0]'] = $event->content->customer->cf_employer;
		}

		if ( $numbers_updated ) {
			$contact_data['field[%IMCA_CE_SUBMITTED%,0]'] = 'SUBMITTED';
		}

		$result[]           = $this->ac_api->api( 'contact/sync', $contact_data );
		$contact_tag_add    = array();
		$contact_tag_remove = array();

		$contact = $this->ac_api->api( "contact/view?email={$event->content->customer->email}" );

		// Add Membership Tag and / or Renewed tag
		if ( ! in_array( $this->plan_tags[ $event->content->subscription->plan_id ]['id'], $contact->tags ) ) {
			$contact_tag_add[] = $this->plan_tags[ $event->content->subscription->plan_id ]['id'];
		}

		// Add Paying Tag
		if ( ! in_array( $this->plan_tags[ $event->content->subscription->plan_id ]['paying'], $contact->tags ) ) {
			$contact_tag_add[] = $this->plan_tags[ $event->content->subscription->plan_id ]['paying'];
		}

		// Remove PayF
		if ( in_array( $this->plan_tags[ $event->content->subscription->plan_id ]['payf'], $contact->tags ) ) {
			$contact_tag_remove[] = $this->plan_tags[ $event->content->subscription->plan_id ]['payf'];
		}

		// Handle Inside Info Combo
		if ( $event->content->invoice->sub_total == 34900 && $event->content->subscription->plan_id == 'kitces-report-autopilot' ) {
			$contact_tag_add[] = 'MemberType-Inside-Info-Combo';
		}

		// Remove Lasped
		if ( $this->plan_tags[ $event->content->subscription->plan_id ]['lapsed'] && in_array( $this->plan_tags[ $event->content->subscription->plan_id ]['lapsed'], $contact->tags ) ) {
			$contact_tag_remove[] = $this->plan_tags[ $event->content->subscription->plan_id ]['lapsed'];
		}

        $this->update_wp_user(
            $event->content->customer->email,
            $event->content->customer->first_name,
            $event->content->customer->last_name,
            $this->get_wp_role_from_ac(
                $event->content->customer->email,
                $contact_tag_add
            )
        );

		$result[] = $this->ac_api->api(
			'contact/tag_add',
			array(
				'email' => $event->content->customer->email,
				'tags'  => $contact_tag_add,
			)
		);

		// Remove Expired Tag in case it exists
		$result[] = $this->ac_api->api(
			'contact/tag_remove',
			array(
				'email' => $event->content->customer->email,
				'tags'  => $contact_tag_remove,
			)
		);

		// Future: how do we detect what plan they were on and add the PayF or other tags?
	}

	function test() {
		ChargeBee_Environment::configure( $this->site, $this->api_key );
		$test = ChargeBee_Customer::retrieve( '1u18eztQ8b2q0M2nh' );
		$test = $test->customer();
		var_dump( $test );
	}
}

$Kitces_ChargeBee_Connector = new Kitces_ChargeBee_Connector();
