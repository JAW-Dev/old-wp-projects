<?php
/**
 * Ajax
 *
 * @package    FP_Core/
 * @subpackage FP_Core/InteractiveLists/Templates/ShareLink
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\InteractiveLists\Templates\ShareLink;

use FP_Core\InteractiveLists\Tables\LinkShare;
use FP_Core\InteractiveLists\Utilities\Page;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Ajax
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Ajax {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'wp_ajax_share_link_ajax_handler', array( $this, 'get_share_link' ) );
		add_action( 'wp_ajax_nopriv_share_link_ajax_handler', array( $this, 'get_share_link' ) );
	}

	/**
	 * Get Share Link
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function get_share_link() {
		if ( empty( $_POST ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'share-link-nonce' ) ) {
			return;
		}

		$client_button = ! empty( $_POST['clientButton'] ) ? sanitize_text_field( wp_unslash( $_POST['clientButton'] ) ) : false;
		$group_button  = ! empty( $_POST['groupButton'] ) ? sanitize_text_field( wp_unslash( $_POST['groupButton'] ) ) : false;

		if ( ! fp_is_feature_active( 'checklists_v_two' ) ) {
			$this->share_link();
		}

		if ( $client_button === 'true' ) {
			$this->share_link();
		}

		if ( fp_is_feature_active( 'checklists_v_two' ) && $group_button === 'true' ) {
			$this->share_link_v_two();
		}
	}

	/**
	 * Share link handler
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function share_link_v_two() {
		if ( empty( $_POST ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'share-link-nonce' ) ) {
			return;
		}

		$resource_id       = ! empty( $_POST['resourceid'] ) ? sanitize_text_field( wp_unslash( $_POST['resourceid'] ) ) : 0;
		$advisor_id        = ! empty( $_POST['advisorid'] ) ? sanitize_text_field( wp_unslash( $_POST['advisorid'] ) ) : 0;
		$hide_more_icon    = ! empty( $_POST['hidemoreicons'] ) ? sanitize_text_field( wp_unslash( $_POST['hidemoreicons'] ) ) : false;
		$show_more_details = ! empty( $_POST['showmoredetails'] ) ? sanitize_text_field( wp_unslash( $_POST['showmoredetails'] ) ) : false;
		$remove_questions  = ! empty( $_POST['removequestions'] ) ? sanitize_text_field( wp_unslash( $_POST['removequestions'] ) ) : false;
		$fields            = ! empty( $_POST['fields'] ) ? $_POST['fields'] : [];
		$notes             = ! empty( $_POST['notes'] ) ? $_POST['notes'] : [];
		$data              = [];

		$data['resource_id'] = sanitize_text_field( wp_unslash( $_POST['resourceid'] ?? '' ) );
		$data['advisor_id']  = sanitize_text_field( wp_unslash( $_POST['advisorid'] ?? '' ) );

		$datetime   = new \DateTime();
		$data       = array();
		$characters = substr( str_shuffle( '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ), 16, 16 );

		$data['share_key']   = $characters;
		$data['resource_id'] = $resource_id;
		$data['advisor_id']  = $advisor_id;
		$data['created']     = $datetime->format( 'Y-m-d H:i:s' );
		$data['fields']      = '';

		$data['fields'] = wp_json_encode(
			[
				'hide_more_icon'    => $hide_more_icon === 'false' ? false : true,
				'show_more_details' => $show_more_details === 'false' ? false : true,
				'remove_questions'  => $remove_questions === 'false' ? false : true,
				'fields'            => $fields,
				'notes'             => $notes,
			]
		);

		$advisor_links   = ! empty( get_user_meta( $advisor_id, 'fp_advisor_share_links', true ) ) ? get_user_meta( $advisor_id, 'fp_advisor_share_links', true ) : [];
		$advisor_links[] = $data;
		update_user_meta( $advisor_id, 'fp_advisor_share_links', $advisor_links );

		$url_query_parmas = [
			'sh' => $data['share_key'],
			'r'  => $data['resource_id'],
			'a'  => $data['advisor_id'],
			'ty' => 'group',
		];

		$url = add_query_arg( $url_query_parmas, get_the_permalink( $data['resource_id'] ) );

		echo $url;
		wp_die();
	}

	/**
	 * Share link handler
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function share_link() {
		if ( empty( $_POST ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'share-link-nonce' ) ) {
			return;
		}

		global $wpdb;

		$contact_id        = ! empty( $_POST['contactId'] ) ? sanitize_text_field( wp_unslash( $_POST['contactId'] ) ) : '';
		$client_name       = ! empty( $_POST['clientName'] ) ? sanitize_text_field( wp_unslash( $_POST['clientName'] ) ) : '';
		$post_id           = ! empty( $_POST['postId'] ) ? sanitize_text_field( wp_unslash( $_POST['postId'] ) ) : '';
		$account_id        = ! empty( $_POST['accountid'] ) ? sanitize_text_field( wp_unslash( $_POST['accountid'] ) ) : '';
		$hide_more_icon    = ! empty( $_POST['hidemoreicons'] ) ? sanitize_text_field( wp_unslash( $_POST['hidemoreicons'] ) ) : false;
		$show_more_details = ! empty( $_POST['showmoredetails'] ) ? sanitize_text_field( wp_unslash( $_POST['showmoredetails'] ) ) : false;
		$remove_questions  = ! empty( $_POST['removequestions'] ) ? sanitize_text_field( wp_unslash( $_POST['removequestions'] ) ) : false;
		$fields            = ! empty( $_POST['fields'] ) ? $_POST['fields'] : [];
		$notes             = ! empty( $_POST['notes'] ) ? $_POST['notes'] : [];

		if ( empty( $contact_id ) || $contact_id === '0' ) {
			$contact_id = md5( $client_name );
		}

		$table = LinkShare::get_resource_share_link_table_name();
		$entry = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE crm_contact_id = %s AND resource_id = $post_id", $contact_id ), ARRAY_A ); // phpcs:ignore

		$datetime   = new \DateTime();
		$expiration = ! empty( $entry['expiration'] ) ? strtotime( $entry['expiration'] ) : '';
		$now        = strtotime( $datetime->format( 'Y-m-d H:i:s' ) );
		$data       = array();
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$data['share_key']   = substr( str_shuffle( $characters ), 16, 16 );
		$data['resource_id'] = sanitize_text_field( wp_unslash( $_POST['resourceid'] ?? '' ) );
		$data['contact_id']  = $contact_id;
		$data['advisor_id']  = sanitize_text_field( wp_unslash( $_POST['advisorid'] ?? '' ) );
		$data['client_name'] = $client_name;
		$now                 = new \DateTime();
		$min_now             = new \DateTime();
		$data['created']     = $now->format( 'Y-m-d H:i:s' );
		$data['fields']      = '';
		$data['account_id']  = $account_id;

		$hours = 720; // 30 days.
		$now->add( new \DateInterval( "PT{$hours}H" ) );
		$data['expiration'] = $now->format( 'Y-m-d H:i:s' );

		$mid_hours = 360; // 15 days.
		$min_now->add( new \DateInterval( "PT{$mid_hours}H" ) );
		$data['mid_time'] = $min_now->format( 'Y-m-d H:i:s' );

		$data['fields'] = wp_json_encode(
			[
				'hide_more_icon'    => $hide_more_icon === 'false' ? false : true,
				'show_more_details' => $show_more_details === 'false' ? false : true,
				'remove_questions'  => $remove_questions === 'false' ? false : true,
				'fields'            => $fields,
				'notes'             => $notes,
			]
		);
		if ( ( ! empty( $entry ) && ! Page::is_resourece_share_link_completed( $entry ) && strtotime( $now->format( 'Y-m-d H:i:s' ) ) < $expiration ) ) {
			LinkShare::update( $data, $entry['id'] );
		} else {
			LinkShare::insert( $data );
		}

		$url_query_parmas = array(
			'sh' => $data['share_key'],
			'r'  => $data['resource_id'],
			'a'  => $data['advisor_id'],
			'c'  => $contact_id,
			'ty' => 'single',
		);

		$url = add_query_arg( $url_query_parmas, get_the_permalink( $data['resource_id'] ) );

		echo $url;
		wp_die();
	}
}
