<?php

namespace FP_Core\Integrations\ProfitWell;

use \FP_Core\All_RCP_Memberships_Getter;
use \FP_Core\Utilities;
use FP_Core\Member;
use FP_Core\Integrations\ProfitWell\Controller;
use FP_Core\Integrations\ProfitWell\ProfitWell_API;

class Migrator {
	static public function migrate() {
		$memberships = All_RCP_Memberships_Getter::get();

		// foreach ( $memberships as $membership ) {
		// 	if ( ! $membership->get_upgraded_from() ) {
		// 		continue;
		// 	}

		// 	$previous_membership = rcp_get_membership( $membership->get_upgraded_from() );

		// 	if ( $previous_membership && ! $previous_membership->is_disabled() ) {
		// 		$id                     = $membership->get_id();
		// 		$previous_membership_id = $previous_membership->get_id();

		// 		echo "found it! {$id} had {$previous_membership_id}\n";
		// 	}
		// }

		// return;

		foreach ( $memberships as $membership ) {

			$subscription_id = rcp_get_membership_meta( $membership->get_id(), 'profitwell_subscription_id', true );

			if ( $subscription_id ) {
				$membership_id = $membership->get_id();

				// echo "skipping membership {$membership_id}, already has subscription id {$subscription_id}\n\n";

				continue;
			}

			self::migrate_membership( $membership );

		}

		return;

		// self::migrate_membership( rcp_get_membership( 448 ) );
	}

	/**
	 * Update Churn
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function update_churn() {
		$memberships = All_RCP_Memberships_Getter::get();

		foreach ( $memberships as $membership ) {
			$subscription_id = rcp_get_membership_meta( $membership->get_id(), 'profitwell_subscription_id', true );
			$user_id         = User_Id_Meta_Manager::get( $membership->get_customer()->get_user_id() );

			if ( $membership->get_status() === 'expired' ) {
				$response = ProfitWell_API::get_subscription_history_for_user( $user_id );

				if ( ! is_wp_error( $response ) && ( 200 === $response['response']['code'] || 201 === $response['response']['code'] ) ) {
					$body   = json_decode( $response['body'] );
					$last   = end( $body );
					$status = ! empty( $last->status ) ? $last->status : '';

					if ( 'churned' !== $status ) {
						$unix_effective = strtotime( gmdate( 'Y-m-d H:i:s' ) );

						ProfitWell_API::churn_subscription( $subscription_id, $unix_effective );
					}
				}
			}
		}
	}

	static public function delete_all_data() {
		global $wpdb;

		$user_keys = array(
			'pwu_b0xFDvFyT9Ad',
			'pwu_YbeHqo8NWYMp',
			'pwu_GaA0uHAlm67l',
		);

		foreach ( $user_keys as $key ) {
			try {
				ProfitWell_API::delete_user( $key );
			} catch ( \Exception $e ) {
			}
		}

		$rows = $wpdb->get_results( 'SELECT * from wp_usermeta where meta_key = \'profitwell_user_id\'' );

		foreach ( $rows as $row ) {
			delete_user_meta( $row->user_id, $row->meta_key );
			echo $row->user_id . ' meta was deleted <br />';
		}

		rcp_delete_membership_meta_by_key( 'profitwell_subscription_id' );

		echo 'Meta deletion complete';

		return;
	}

	static public function delete_all_data_2() {
		$user_keys = array(
			'pwu_b0xFDvFyT9Ad',
			'pwu_YbeHqo8NWYMp',
			'pwu_GaA0uHAlm67l',
		);

		foreach ( $user_keys as $key ) {
			try {
				ProfitWell_API::delete_user( $key );
			} catch ( \Exception $e ) {
			}
		}
	}

	static public function delete_all_data_3() {
		$user_keys = array(
			'pwu_FsWNjb1uD0zv',
			'pwu_SOu387LXB3Vd',
			'pwu_NEOLD6ZzUw9s',
			'pwu_kn3Bm3n7ayUU',
			'pwu_bIjOuyNSA8Hy',
			'pwu_TyA8CoqrJeGW',
			'pwu_j532jrEDzbve',
			'pwu_bgYEDjIG9lup',
			'pwu_xlngP2jCqsRZ',
			'pwu_XEqvq0gPq4j0',
			'pwu_lBS80NVSu7ru',
			'pwu_7OzHSGqE1ZgX',
			'pwu_JUpZSx51DBmH',
			'pwu_Yyy9sUpzZKpA',
			'pwu_JgC1hXUjYp9Q',
			'pwu_5bfdqX5llQ2m',
			'pwu_OTWuH1TCqImY',
			'pwu_y9HM1KGknC0L',
			'pwu_v95X319sMctJ',
			'pwu_3yXMx7qLtVNA',
			'pwu_m19nkGwv1Qfs',
			'pwu_Gfan4dUfVh1n',
			'pwu_GdfFGxLKY7EJ',
			'pwu_w0xOmb85VwED',
			'pwu_mvIDkQEmwKKx',
			'pwu_jiPXtOPTS2E4',
			'pwu_xIqwOXSMRXfG',
			'pwu_SRfOALmYGVdz',
			'pwu_XYl9VHd8VjTA',
			'pwu_eEbeTTNliGpN',
			'pwu_6t3zhNmEEt34',
			'pwu_Y5eF95uEG9yB',
			'pwu_ZEB6eqWM7rWy',
			'pwu_aDyGNiMIbNI5',
			'pwu_s2ejziR3BHYZ',
			'pwu_EwTqJPYhbRgo',
			'pwu_a8Rc1Aq0cCGh',
			'pwu_PnCflW2kd6p0',
			'pwu_0f1q3hSNBWnd',
			'pwu_gUXRK4XGNAxS',
			'pwu_dZGc0mmDgrIT',
			'pwu_6soCo2XV02Fq',
			'pwu_9Z52q4KEwm8T',
			'pwu_olVXI3lJ44p8',
			'pwu_ZKB3NYoBxTBY',
			'pwu_0B5V98i8rZOL',
			'pwu_o5QOPoBe9Z52',
			'pwu_qSYASneBrtiJ',
			'pwu_iHo38oSeDlmb',
			'pwu_X6ggMxOK6CSQ',
			'pwu_WTEGbH4n1bwM',
			'pwu_gM0ye6Sij2rm',
			'pwu_UYn3gukOmneD',
			'pwu_jD4ClkPsWSPj',
			'pwu_bdbeIVWqvm8X',
			'pwu_dbcOLkaaSTgz',
			'pwu_6TIKaq78PSz3',
			'pwu_roOjHyOY3Cdc',
			'pwu_95KjxvsAWjgH',
			'pwu_1wUIyqaVMHHm',
			'pwu_wLc4MmCeA9VL',
			'pwu_eTDsdB2fhSAj',
			'pwu_8bYJucS06746',
			'pwu_Sm2wSo1bqYwH',
			'pwu_qRvgl5cc5Uqz',
			'pwu_myHIltRoGKkb',
			'pwu_gsr4zhd3Nmpo',
			'pwu_aqdwAsJOyxqL',
			'pwu_R8noxHe4l423',
			'pwu_aAqKD9NjvhoM',
			'pwu_JuioLQp7WrKm',
			'pwu_A6gsoUNqdPCo',
			'pwu_q2pNM8hvws24',
			'pwu_jlo87DdkaDUN',
			'pwu_Wvmk4tIDnhF8',
			'pwu_nRhE5UqcdKxS',
			'pwu_eMVNrXaDmpuz',
			'pwu_wZI45v0ldGko',
			'pwu_Md1N16y480td',
			'pwu_YHJQX7xD7Jvg',
			'pwu_QlwMoftyrZ9z',
			'pwu_Y87LWirUhiOb',
			'pwu_dEmtIl5xLnJe',
			'pwu_dXMi1ZAofxwQ',
			'pwu_D1iamcgYenWD',
			'pwu_h5mgjUzPOmyq',
			'pwu_Ew2eCJBkp839',
			'pwu_iZgDpOcIVvLN',
			'pwu_fr5il1HfEnIf',
			'pwu_r341S38HFVEI',
			'pwu_SivfrPt8GkfS',
			'pwu_bA1oELJdszYf',
			'pwu_ghKbxSk0ARUx',
			'pwu_FbunREul4pGs',
			'pwu_5WymZ5sUpuDx',
			'pwu_A0a4g6ijMkxn',
			'pwu_vnOSv9XpnSe7',
			'pwu_soGoKXzpkXbJ',
			'pwu_jdOUDqm2w3iT',
			'pwu_2fW7k9wg95P6',
			'pwu_gKvDxF6GnYBK',
			'pwu_aLeAz4GBuctI',
			'pwu_O4JuEfMJ8YaK',
			'pwu_TQN6OOF99n9s',
			'pwu_TMQS0NRVQnQC',
			'pwu_UtSwGvXpSljs',
			'pwu_dTGuYOe16UAX',
			'pwu_lMgAzAUtILRx',
			'pwu_tlvVT0zgr3X9',
			'pwu_D3YSrZdeIWHj',
			'pwu_aH3Lh1cX8ISI',
			'pwu_5c5mktFQAuHp',
			'pwu_10vWyKSkgibw',
			'pwu_S1jL16o14mnh',
			'pwu_mSELdtn3bqpC',
			'pwu_K9XPq3plIOWO',
			'pwu_nO50YfsjqdFD',
			'pwu_3SPdaZDenssb',
			'pwu_FAsdhlLFWTr7',
			'pwu_RF1A1yM7D8Ek',
			'pwu_A1JDKWuV8ImL',
			'pwu_HhOmTyyzgeve',
			'pwu_ELInC284p4nE',
			'pwu_6V3hoMSPcbhY',
			'pwu_j2wgpSeu8j9x',
			'pwu_DmJYHqNmtRF4',
			'pwu_SFUDdzwACQLG',
			'pwu_XCZVqVukt6kf',
			'pwu_nW3Us0yJgsJN',
			'pwu_fyhAfiJ5oO4k',
			'pwu_TOUcck6SvSLs',
			'pwu_OWUmSgJmF3KI',
			'pwu_pkNCnFbPpH7g',
			'pwu_WFHoCYSXvZM9',
			'pwu_GOVK2AmxUb2a',
			'pwu_k0eh7iJohWdS',
			'pwu_VB73pji9WGg2',
			'pwu_Sg65fdCyhz2J',
			'pwu_NAmNLCp19NJx',
			'pwu_rTfneb5aDbIr',
			'pwu_r04SEPb8pIVA',
			'pwu_fYo1tNkE8slg',
			'pwu_bfo3y4g0kN3Y',
			'pwu_pJ7z03EraARo',
			'pwu_cTez0yYORII9',
			'pwu_QdoBnbNknRbK',
			'pwu_Vgb74nISf31Q',
			'pwu_x3Y3GeRmnVsY',
			'pwu_6pJ7AOtg9gRP',
			'pwu_5TEQOiSCDUjB',
			'pwu_fjbEFEkc2Zkd',
			'pwu_TCGRztnKjYx2',
			'pwu_q8I1caVxahsa',
			'pwu_uvnW20s7weLN',
			'pwu_cNMzGGOPfwE7',
			'pwu_foVTGhNoDWyK',
			'pwu_WZfBCrUhFE4Q',
			'pwu_2ObKpusbXWNN',
			'pwu_ynRmXHQRzAt2',
			'pwu_QKD8StQLHDG4',
			'pwu_OLt46QhxwOCL',
			'pwu_RR64IyUEBiZX',
			'pwu_aIbfcyIj028R',
			'pwu_WX6G0PYRJuQd',
			'pwu_1s92UzrJ3lPb',
			'pwu_X6gp1TN7DgsP',
			'pwu_ujZ9HLIJ2WhT',
			'pwu_2XewOZO3j9hz',
			'pwu_O4Vms8t0HHBZ',
			'pwu_ikYMYhfSTgxL',
			'pwu_tOnpJkhWz8kB',
			'pwu_EDwm21JOn5lX',
			'pwu_j9AlwWBffL38',
			'pwu_RAlJzyZAf0gc',
			'pwu_qGa1D9CMEvRz',
			'pwu_FEYqAq25d030',
			'pwu_ht64HgOt4XI9',
			'pwu_ccWmSuq2aU06',
			'pwu_dZGc0mmDgrIT',
			'pwu_500oPodrivik',
			'pwu_6soCo2XV02Fq',
			'pwu_xgVBzohMhCis',
			'pwu_eomFdLamkrTz',
			'pwu_Lk5qpxcAedR5',
			'pwu_W0nS1jMWWhDC',
			'pwu_tNNj8ORf3tau',
			'pwu_9XMYffQrMcey',
			'pwu_Dbwt4ETiUY2h',
			'pwu_tVUXYn6NEx7Z',
			'pwu_jdDsWDVeOYJb',
			'pwu_fksie8mfm2EJ',
			'pwu_VXci4acYdefn',
			'pwu_LmgRnSOWt0t7',
			'pwu_uryElPZSNOXV',
			'pwu_f9ELI2sJ9DJp',
			'pwu_IenviYLfqxf7',
			'pwu_GneFKyOAK70E',
			'pwu_DCmA1itzp4Uh',
			'pwu_x9rF5GnwSmOe',
			'pwu_BIrLG9jHvukb',
			'pwu_3DwISAyoaCQ8',
			'pwu_wF56TAr87iiq',
			'pwu_i3vRaM3pvn6C',
			'pwu_o5QXAaEr2meD',
			'pwu_o0HHxfJCLLTt',
			'pwu_UC4LBn1R4iFL',
			'pwu_b95QRPvaYJeu',
			'pwu_sSKuePZQD7K7',
			'pwu_k8x6LTBLsPTG',
			'pwu_WuTnGZZMpEcu',
			'pwu_19lj8zKtBZKz',
			'pwu_woTxTM6Gdv03',
			'pwu_vW3dvlRx2ren',
			'pwu_uCSlml02gACh',
			'pwu_z4sRz1gFKxH5',
			'pwu_rZLsIOy17jUs',
			'pwu_Drju21oTZn2J',
			'pwu_LMzs5lERdnyQ',
			'pwu_1vuvaWOlxodt',
			'pwu_CRsDZ4umDbuI',
			'pwu_4JieMjlqvkHN',
			'pwu_2hXYp6t6DWv1',
			'pwu_Frxds563csdO',
			'pwu_9Z52q4KEwm8T',
			'pwu_AIaVCv5RZ0sC',
			'pwu_XUU2Y5OGV49I',
			'pwu_s1ououP6iuVY',
			'pwu_sMtvS54XKSUl',
			'pwu_MCFbgoYQgPiK',
			'pwu_REhmvmL1HK9l',
			'pwu_NajGdBKVzSTv',
			'pwu_UpuuImqFoaeI',
			'pwu_QHyHgCzLcx3z',
			'pwu_sNlkFTgcWQEk',
			'pwu_TD5rQxwK0JXL',
			'pwu_Qmaj01HRYQB4',
			'pwu_lGqhwbyAJR1Z',
			'pwu_YwO2EPuPA0sd',
			'pwu_la5MOo1TzpBM',
			'pwu_onxlAhlp3eT2',
			'pwu_XXm6emVofc70',
			'pwu_5n6EwuRaKy9w',
			'pwu_oWaDK9nwYzDp',
			'pwu_txBy2LDIPAna',
			'pwu_uBmorFf9a89g',
			'pwu_mnGXKmwbeef4',
			'pwu_AIC6g8QmnGTf',
			'pwu_JCO3lkBwe8Se',
			'pwu_GfN8Q0X3Da79',
			'pwu_dMUilU1gseaC',
			'pwu_1DsVkFMnUDjs',
			'pwu_yUZ0Sc77nxZn',
			'pwu_dP2zSKsgIxQO',
			'pwu_yg9yXbh3kBTT',
			'pwu_bQiTKFd3uxsa',
			'pwu_O4GSm51IKdsQ',
			'pwu_YTdvsv87pTiF',
			'pwu_zMsgSHDkKwxV',
			'pwu_NyZKLsWbxywG',
			'pwu_C7Us3ZrEJ0E4',
			'pwu_Hs3fu5Bj5kuW',
			'pwu_vdJkAeZOXKAi',
			'pwu_5qYlrZbaLrLz',
			'pwu_tnTI4RpyZPWV',
			'pwu_XOBj5U8iWflc',
			'pwu_0HeWpMgUz7Te',
			'pwu_3xt97JyeFKi3',
			'pwu_MPpJipugFVog',
			'pwu_dxNnCPKsyZ2O',
			'pwu_Vwj7pbFs98Kw',
			'pwu_BLobtUU7X7hN',
			'pwu_KhUS3o3gzeGM',
			'pwu_D5SXD1sYK4Bc',
			'pwu_Ghm5jZysqkz7',
			'pwu_UqgqXFs9TGWp',
			'pwu_18rQB4ICU4He',
			'pwu_s2ejziR3BHYZ',
			'pwu_fFtkYGazzDzX',
			'pwu_TOZyOuiBxbyB',
			'pwu_U8JqLz6Soqm8',
			'pwu_asJG4HF3IWAq',
			'pwu_s6dEgsLDYxS1',
			'pwu_mQqeWVfB4WSr',
			'pwu_gQwfbFNH0WKN',
			'pwu_t7iyXuivPYoB',
			'pwu_EEGhbMsO0ikq',
			'pwu_xqpwUoagJtgY',
			'pwu_xvt7jnM7TXVC',
			'pwu_ozlOvrA3ePSP',
			'pwu_Kf2Wo9fqRGJ6',
			'pwu_lA7fm2iC8cmK',
			'pwu_Oa246z33cqBg',
			'pwu_tseZJzLO1plo',
			'pwu_JgrdB9cxxuJq',
			'pwu_LSaFNnAwWb9a',
			'pwu_xAx3ctgo4JWI',
			'pwu_ON27LZdhbKTq',
			'pwu_AwrPVSxFDALS',
			'pwu_fIKjH6FKkM9I',
			'pwu_EZw1NJMZ1AIA',
			'pwu_Gfg6Hjoy3wsS',
			'pwu_bFQznOtI2oRr',
			'pwu_W2cmCpaJBvmQ',
			'pwu_4GelHS15TCs2',
			'pwu_jfNxBbckngXw',
			'pwu_CFWuGfoMC5lP',
			'pwu_uWjUD8KevxhZ',
			'pwu_mK4K0eMrtBYy',
			'pwu_2zr6om8lVxyB',
			'pwu_2gC0GxpdHxwj',
			'pwu_mGVKyhOQxW5g',
			'pwu_gaUEwzZyOt8y',
			'pwu_WsBTTWEzXseT',
			'pwu_Ny6UYmoZN10t',
			'pwu_ska6ZrAr1MrJ',
			'pwu_um1oEys6z5EK',
			'pwu_Tz2hKToVLhvV',
			'pwu_KxC0xDQBVnUK',
			'pwu_zR1PNLXIfWte',
			'pwu_XjPEjJwuGDRB',
			'pwu_1wUIyqaVMHHm',
			'pwu_wLc4MmCeA9VL',
			'pwu_eTDsdB2fhSAj',
			'pwu_yt2a31aYlam3',
			'pwu_xjxvAC2JvTKU',
			'pwu_FoDo4MCinUUK',
			'pwu_FtHb6OYS1FHR',
			'pwu_OWUmSgJmF3KI',
			'pwu_Dbeq0c8qsrqH',
			'pwu_pdKoJg4ZEGNz',
			'pwu_prP8EDKWaREu',
			'pwu_jdfYC0shoVN8',
			'pwu_0B5V98i8rZOL',
			'pwu_3OSh1uRe1hZR',
			'pwu_an8mtIlxcayq',
			'pwu_givlyvjZ8nM0',
			'pwu_cDtv1DW2mDp2',
			'pwu_nkfkaoPFYCID',
			'pwu_0PyeiR8hPBop',
			'pwu_JntdA5Tw9mG0',
			'pwu_x0ZRxoIXMSFG',
			'pwu_SjqsrmS4kAQ1',
			'pwu_Ptu7vP14oKng',
			'pwu_RtNb3ZSdNbjf',
			'pwu_LKiWLnoRrElP',
			'pwu_S0KRbrFOSxrO',
			'pwu_Glqn0v3GG8zd',
			'pwu_nnsA9Zu3rVZn',
			'pwu_SdcPxqwCmBZJ',
			'pwu_RDrtnBImyC5L',
			'pwu_9QgFpOqSuotE',
			'pwu_Ti4RiyEyyGae',
			'pwu_XUi9qFkk4CJc',
			'pwu_OAwYDLqNSBh4',
			'pwu_QobQ9uevLW6P',
			'pwu_YbotIF0srVyc',
			'pwu_E31sVMYx6WBh',
			'pwu_oYw09uf6j5Bo',
			'pwu_WIZhMAYLnSDz',
			'pwu_ikUeuHijMbYc',
			'pwu_kL7gqBDfjdNB',
			'pwu_2UUoeMwi14xr',
			'pwu_5nGnMk0BhAcN',
			'pwu_JuioLQp7WrKm',
			'pwu_81LRCu5Z9Oq8',
			'pwu_F6zqA0EMF8Oh',
			'pwu_yFnHMzFTqo37',
			'pwu_OctDwgFeeNAL',
			'pwu_NchHqmOlvYj5',
			'pwu_39Kvdg3EgKig',
			'pwu_zqTvumt6qa82',
			'pwu_rmcu8nt5gi2c',
			'pwu_lxbyqb6mH1Tz',
			'pwu_VKN6AdEJjkma',
			'pwu_m91lmEI8VMs7',
			'pwu_bqcS2h6Pbphj',
			'pwu_uKt8nxNrcrqM',
			'pwu_SIEKE08K1gdC',
			'pwu_cUHLK0mUIAGW',
			'pwu_yOTBCbE6keF0',
			'pwu_Pcr37uWgCZHJ',
			'pwu_r9sDhMbvgpkn',
			'pwu_qoLu8zxV7nan',
			'pwu_9OhJbvkbc4qv',
			'pwu_eBDoqzD3y0W0',
			'pwu_QN3BVjY0Walm',
			'pwu_bCqH89lcJrFr',
			'pwu_pqYO8892ZP55',
			'pwu_9nbTc7YlAWlO',
			'pwu_opSiulTjeyNF',
			'pwu_7V6frALI3Zdy',
			'pwu_p2wF17JDu34o',
			'pwu_Yk1yewRqxW4m',
			'pwu_hkXJ46hvlWkU',
			'pwu_OKr9CHxbyYaJ',
			'pwu_k6UfX0EFvl3K',
			'pwu_S1PzU5beFdNc',
			'pwu_yhjwiHOowMtI',
			'pwu_bkIYiS4BoMoH',
			'pwu_4cNsTtFEDcon',
			'pwu_pJDsfrqJEvb1',
			'pwu_ePDO2RVBuY8q',
			'pwu_YijqIkHlvRCV',
			'pwu_X69YhoV7PWw8',
			'pwu_8HWASrsB5dQk',
			'pwu_FFqtPWLnCuIa',
			'pwu_ROqJAiOvxCVO',
			'pwu_qD6T9nWCBWwP',
			'pwu_I9PQMGY5JxEf',
			'pwu_UxUHMjGAee24',
			'pwu_ag3KRONgY5iB',
			'pwu_iRMoj7aozRBg',
			'pwu_9mzEOdJXFJYR',
			'pwu_7ZGjora5UK0e',
			'pwu_mhGYPwa9C3wv',
			'pwu_0qvOq0stHZ2M',
			'pwu_f4LNhBVyAEBC',
			'pwu_8LYV1WoA9tHd',
			'pwu_hjmpxPN4CELz',
			'pwu_3Q43b0irx5dJ',
			'pwu_70kYbY2rdfjI',
			'pwu_0ndxtTYUa05f',
			'pwu_vnueoQDWyK5J',
			'pwu_8VT7WizKKtyz',
			'pwu_TflqzlpaKApY',
			'pwu_e7QdCA7fKJ5I',
			'pwu_D3x5hcILIxYo',
			'pwu_TsGbVESlaDx1',
			'pwu_bvOidyUF1ji7',
			'pwu_tJBgjiZgdsld',
			'pwu_Uu45aVmLzGJZ',
			'pwu_oM5xMA2mCF8S',
			'pwu_Ir46uHy7QrAG',
			'pwu_7thwiBTEJA50',
			'pwu_UEHfC3QandXY',
			'pwu_Vg8EfVDiDd4D',
			'pwu_fxyJRAQT6Neh',
			'pwu_KS8ws2dwqrE4',
			'pwu_Jkbb6XNX8wPq',
			'pwu_uaGTCKSUTIhF',
			'pwu_C2l1FtWr6lDv',
			'pwu_8ok1YV92WJbQ',
			'pwu_hCTtOiXatP9p',
			'pwu_rQOevEthJF2S',
			'pwu_TmM37AGIIVwC',
			'pwu_suNDnyQZFNu6',
			'pwu_aO4oggp3Eh3k',
			'pwu_488Zbd0aBilU',
			'pwu_K2bcJ8z2BDXi',
			'pwu_9tgHLzIHgKdx',
			'pwu_x5X1UZ1M5tiD',
			'pwu_VRF95hEJzgIP',
			'pwu_0OD22Kc6eFJW',
			'pwu_DB0I8tKyfBIR',
			'pwu_1949SjKLVeqh',
			'pwu_pWruOiVidJeS',
			'pwu_TbTyp6wc7YSV',
			'pwu_2BGBDZ2Uc27r',
			'pwu_vsbPLNlZSyST',
			'pwu_d3qgJqjAmdIi',
			'pwu_aaBIXXzr6yOU',
			'pwu_gxeetGxoCxfF',
			'pwu_NL0HbsVKXdwN',
			'pwu_CPY45iBxrmhQ',
			'pwu_3sMZ2uREp9xE',
			'pwu_r4AQAU7asAAb',
			'pwu_rlv0MHfucHFi',
			'pwu_V9oEsQoznGvf',
			'pwu_D93pk1ZJUbnc',
			'pwu_TyzRr1CxKM8Y',
			'pwu_g6Oeq4233D6k',
			'pwu_xL7YOhyUh9cq',
			'pwu_pl8CFsaIsqph',
			'pwu_yvCnLVASuISA',
			'pwu_VEoGRcyF4CQY',
			'pwu_DMFX01NGpKC8',
			'pwu_rASWhb1AKhnE',
			'pwu_hKSCu1czvffb',
			'pwu_oVVSYJgkYxOb',
			'pwu_e1wYK6S1dlJf',
			'pwu_YjHq4Nt7LKVk',
			'pwu_ihO9ZWCIjV1I',
			'pwu_JEFQbZjKAvzL',
			'pwu_H2J2jDkYapzN',
			'pwu_NlB7EPWAIep3',
			'pwu_EZkd2sIiVnLV',
			'pwu_3ZrWHijmGu1O',
			'pwu_jsmhhGrPcBl3',
			'pwu_UUIK5qUDIGxX',
			'pwu_3qTXJ8Q7vuQg',
			'pwu_tdBo8vyZF6j3',
			'pwu_Ew2eCJBkp839',
			'pwu_cBPdr3nb5ajC',
			'pwu_YRms2ocnBWT6',
			'pwu_lMhHrRuSsAvU',
			'pwu_SJx4dYE2kBep',
			'pwu_LMM4tlBmE5wO',
			'pwu_mJHTRtnlTeS2',
			'pwu_1WNjM66M9oN2',
			'pwu_v7PGFjEjJQz1',
			'pwu_5Dcj8ylTEI9x',
			'pwu_dbLiiUN925LA',
			'pwu_h8dc0ABXdzlb',
			'pwu_p7jl558b3Ehu',
			'pwu_LHa6y3LlRx82',
			'pwu_dzbjZlHKSyPD',
			'pwu_JldpgVAwSUgY',
			'pwu_zof1CGQY6NPB',
			'pwu_mwIM1eb3vwlW',
			'pwu_8ZPXUOgDJo4f',
			'pwu_ZAak6eEj3P87',
			'pwu_waNsPDJmnl2j',
			'pwu_SE7S6ZM0S54s',
			'pwu_ZBt1jaJRKwcx',
			'pwu_nbJV82g2p0zW',
			'pwu_i8TZkDLdPNZz',
			'pwu_K4ziLhlXOvkc',
			'pwu_1mrZ8SDbGLya',
			'pwu_9JR9KF5hhde7',
			'pwu_DqmTD0z1dbtl',
			'pwu_5GZtFE9tIMra',
			'pwu_xKGl14mmVMsM',
			'pwu_DF8DciH5UooN',
			'pwu_2PqlHhnqwHmS',
			'pwu_WjMuc2kOgf8b',
			'pwu_YN1wLlBlkI3P',
			'pwu_t8N13PXOBLcM',
			'pwu_PemGUQJHHQp1',
			'pwu_jtCrz1f2QdSL',
			'pwu_sXeYARgputsP',
			'pwu_DBhWf6rKgCCN',
			'pwu_AspNS5stzV9o',
			'pwu_SEISDwC5o4AJ',
			'pwu_TMQS0NRVQnQC',
			'pwu_cE42Em1LpQCs',
			'pwu_TWc835tZQje5',
			'pwu_l2ykcqKMMjH5',
			'pwu_4iJYraPFaEgz',
			'pwu_DyUWYkGQZHlH',
			'pwu_w5b3EQGPmjSF',
			'pwu_qCezehWXHtxq',
			'pwu_1qQNnhZEBS8p',
			'pwu_h8CImU8NkOZB',
			'pwu_h53ocJ64xbBv',
			'pwu_4F7zEIRxFNzC',
			'pwu_DrOOutjI3qjo',
			'pwu_YyW5KdmUR39J',
			'pwu_mQc8oscPhhtv',
			'pwu_xzrjAsQv9oll',
			'pwu_h9QAJ3hZOpxi',
			'pwu_OIoOQPfi9kgG',
			'pwu_Pka3oRVyoEal',
			'pwu_iLSXtlPbHHoE',
			'pwu_hTajbLYmHp8X',
			'pwu_LAOLTrgw84uD',
			'pwu_Q5HoCiKkdCof',
			'pwu_XdWUagpGIDmh',
			'pwu_vW3dvlRx2ren',
			'pwu_beoO4UBwi2DZ',
			'pwu_Ac21p87xhjMU',
			'pwu_PhabplYyvY5k',
			'pwu_9ongfENhF5cg',
			'pwu_n9XnKRzk8qgD',
			'pwu_PrwnzlD1KVNQ',
			'pwu_RJcifO2CWDQr',
			'pwu_9DnA9PiILqio',
			'pwu_FNwSYZQOjFKH',
			'pwu_zxidoHMaLkbD',
			'pwu_uee71wVFmAw4',
			'pwu_sG0ZDKKMy7kH',
			'pwu_JPloYt07BwQV',
			'pwu_74l6VxKh08VJ',
			'pwu_UNdxIEorOtJE',
			'pwu_AIkS2xtf0rkk',
			'pwu_xifV7lDWTb5E',
			'pwu_UTh5WTyUzKod',
			'pwu_XghBjgheWmuN',
			'pwu_FWT4BOZCOzL9',
			'pwu_hoIjhEjw65Np',
			'pwu_CqphyXTVW0Bj',
			'pwu_2Gl6DJ4GcgMO',
			'pwu_UkjViVqKHggy',
			'pwu_KdwykGzRoBaM',
			'pwu_FNvJ6zUNRJ82',
			'pwu_2tmgvd0yjHmE',
			'pwu_kWMbxnknQyKw',
			'pwu_ZtVEdq2YrKgS',
			'pwu_JK7UHKJXAu8b',
			'pwu_VPBROPZ2uLeN',
			'pwu_HBMqibO9aCoq',
			'pwu_CRCrGH6omV9l',
			'pwu_OWLPG0MWnLF5',
			'pwu_RySxfhUMmfwp',
			'pwu_DxsWqJ5L23yU',
			'pwu_iU93QQaHuMUu',
			'pwu_L9qQH7Ynl0bP',
			'pwu_BxNdZxHufFp9',
			'pwu_9vSPWZtDzOIR',
			'pwu_kN9COVmi0IRk',
			'pwu_rjymp5EHqOGg',
			'pwu_bmuUmbBo0fSt',
			'pwu_fvOQwYoYfjSi',
			'pwu_P2iEqrWsfI1C',
			'pwu_S06lJj8drXSX',
			'pwu_t3HTTytwzX3t',
			'pwu_YilrqgmcSnY3',
			'pwu_kzOZOENBZlH5',
			'pwu_OGe5Crl5w67Q',
			'pwu_q5rF75sTaVzf',
			'pwu_HqTm1VzORHVx',
			'pwu_kTGGyEN8PnVO',
			'pwu_eIoos7hyDKJp',
			'pwu_9cYTUT2JFazu',
			'pwu_NQK5K7yhmjKb',
			'pwu_9YLhK03qwmNT',
			'pwu_yQZoR85GDLfE',
			'pwu_c2VykptLDQgv',
			'pwu_tuPgzZdBdV0X',
			'pwu_H4zaalRiapa3',
			'pwu_O7oNw3nVnnH4',
			'pwu_Vua9hTIBRhVH',
			'pwu_nKHxcKJTCvC1',
			'pwu_9tfaCHsLaS2m',
			'pwu_MwIlJFxPU57W',
			'pwu_EXcdNbEZHpaZ',
			'pwu_1kJUr7FgKAJA',
			'pwu_s6dEgsLDYxS1',
			'pwu_z4sRz1gFKxH5',
			'pwu_Aqu9R9rgWycE',
			'pwu_SbiknbFIdu6v',
			'pwu_uyvWZpxqTrDz',
			'pwu_paLbvYxwVqOr',
			'pwu_5WymZ5sUpuDx',
			'pwu_6zVmvWwZhVnO',
			'pwu_mTVpmC6F7piU',
			'pwu_cwhgOF3pApEv',
			'pwu_hrTXw4K40jHq',
			'pwu_lT0OG1R1RD8b',
			'pwu_bs54VFjHFdIX',
			'pwu_yUXslKltXPHx',
			'pwu_Q8V6cINXxvy2',
			'pwu_dZnY1fUiNc8Q',
			'pwu_1yVYe2mSHqG9',
			'pwu_d2SBFpy2xyjK',
			'pwu_2ynH2PSIDWCR',
			'pwu_uU4bKBdQao90',
			'pwu_ovQ2m9GnIKCk',
			'pwu_0nItPM3UJX6g',
			'pwu_xOMRDfPwHOR1',
			'pwu_nLvRLnvEMxfi',
			'pwu_5C9PfFprC1Vm',
			'pwu_6ubpPGkKrSyz',
			'pwu_XfnH5kpE0ML0',
			'pwu_An8XmBxNZx8I',
			'pwu_1zDe74yLRYly',
			'pwu_eiuhk7GT4tO7',
			'pwu_hlrEaUUJglNM',
			'pwu_PD0WYk97pCur',
			'pwu_tDWlqn0OyER8',
			'pwu_uRAikzGEsGj7',
			'pwu_FJq3uIDotlId',
			'pwu_K814ebOfGqsu',
			'pwu_yKeqUg0EcsTU',
			'pwu_hxZwJI4lny1J',
			'pwu_baqcnAaa8QW1',
			'pwu_gTH7PkeDH3F5',
			'pwu_fzh9IdvXPSDL',
			'pwu_F6k9htqS9Wsf',
			'pwu_5OdOsb7aFDQI',
			'pwu_kaFMYEIVB2rY',
			'pwu_3u4Wr2Aaaqwz',
			'pwu_ymlUH6iPz6Jz',
			'pwu_6Q3m3SxX9RAz',
			'pwu_ccmL6DvFDXWc',
			'pwu_CdPogpwgLKyM',
			'pwu_sUFwgJBYLeQm',
			'pwu_I63wPLq4n3XA',
			'pwu_xIKraYhtJDki',
			'pwu_IH9eXYGJlKcM',
			'pwu_BaKTEGKDtkZf',
			'pwu_5DyX4Hra01QE',
			'pwu_ySG0M7Rwo9Du',
			'pwu_BNLKgTvpkNUN',
			'pwu_hIcBCPaiz2Ty',
			'pwu_scojANA5xHaZ',
			'pwu_Re4pWnaNwDkG',
			'pwu_7nCCXXRR4GlR',
			'pwu_cmBFaZy9AVEH',
			'pwu_tiDFDBbybSnZ',
			'pwu_wvqB1fBD57nc',
			'pwu_grQ7WpC6drgC',
			'pwu_JNQ6tfGwKhm4',
			'pwu_8HOcXH1p7oeG',
			'pwu_Yc6C18bIoUye',
			'pwu_BSiUl17z6W0B',
			'pwu_tnNChqxVw75b',
			'pwu_egvhhkWx6IK8',
			'pwu_LkhaSj2dgteK',
			'pwu_hOfWqrclWw3X',
			'pwu_659L5a59ut2A',
			'pwu_d5cfmoJZQJPg',
			'pwu_c7CGA6zap8qE',
			'pwu_Luyey2Jje5je',
			'pwu_9gdmoffxC3Hs',
			'pwu_dhYHBCTNJu1b',
			'pwu_MxJ4VdspzOFg',
			'pwu_AnBuNx3k4UBv',
			'pwu_j6q3v8I7izx5',
			'pwu_rkksPRUJzNQZ',
			'pwu_uXHUpKKgwFsE',
			'pwu_728YNUuP5Jml',
			'pwu_2GN1SJctIoNe',
			'pwu_5qUR0NmJ9Rpb',
			'pwu_6LH2Vz6398iu',
			'pwu_6gxMwiPdF0GP',
			'pwu_ziHPGRoB3ozz',
			'pwu_lbrE7pV7rFnF',
			'pwu_x7uZzxqeNh7i',
			'pwu_9ZDt5MEQAufA',
			'pwu_zEMgYwCqYCN8',
			'pwu_eMNX6HkLXfcz',
			'pwu_bgAOk1f0aeoT',
			'pwu_cep1GatKyi5W',
			'pwu_aumBfUZsNA2e',
			'pwu_CQkJLGGPA5VM',
			'pwu_CYPaCkQrLAHy',
			'pwu_cnYugOcAGIB6',
			'pwu_R86u980ntD2m',
			'pwu_OWlzVvyiJNTP',
			'pwu_18rQB4ICU4He',
			'pwu_TAZCMcHQtD52',
			'pwu_IoiTnTWVcWvb',
			'pwu_rznBnih1dZtf',
			'pwu_xVJBXb92S7kf',
			'pwu_ZTbFElC7zj7F',
			'pwu_kVhKLbutMadF',
			'pwu_dzJHXBO1oOjR',
			'pwu_4QG73iIO0iWK',
			'pwu_M36RwSwNCwsm',
			'pwu_8cpOjHfqi6KU',
			'pwu_tQKmDWMxzH1k',
			'pwu_7HO6Mzxlnc6X',
			'pwu_UGN8cS0bPPEd',
			'pwu_OzmldOOXrZiv',
			'pwu_tScqAMon3Oq4',
			'pwu_4hJvUZMvDZsQ',
			'pwu_HzdBoXvtE4JD',
			'pwu_tyXyNrj8Ob6I',
			'pwu_kjm7yWkYuoT2',
			'pwu_qR2ULmloHByg',
			'pwu_7zp58JtnEr5w',
			'pwu_hP6ZVtifmaqL',
			'pwu_6FD5Py4R9X5x',
			'pwu_h1fHzyz8zg2Z',
			'pwu_JBzxeLDxDZZH',
			'pwu_VRknd6tYGVuS',
			'pwu_oTxFd2VvIEEC',
			'pwu_h8mbIW3t091r',
			'pwu_mvbYI42IuN55',
			'pwu_ootCGSnUCe6V',
			'pwu_06RajutbFZkB',
			'pwu_5zWoACxDsLvv',
			'pwu_ijKFj2loGKfo',
			'pwu_hW1QXIHwhm18',
			'pwu_OrqEQ4FtuwKI',
			'pwu_kcEHnrH7OPej',
			'pwu_UmR1MRGQVaq4',
			'pwu_h5mgjUzPOmyq',
			'pwu_hYhdTiwr47fI',
			'pwu_PVMsPNiUw8hN',
			'pwu_yxkfeU8dftWg',
			'pwu_SV8j4X3bwg4K',
			'pwu_hOmunZmet2ow',
			'pwu_bcYKqR1SaVNE',
			'pwu_El0o15lna6U3',
			'pwu_u3sAbu0xwjMi',
			'pwu_t7er4EbAwdcS',
			'pwu_4t82b3EYXuBs',
			'pwu_CbpaEcig87bf',
			'pwu_Kl3TKFySs3bl',
			'pwu_SjdmgtpJFlS6',
			'pwu_x0JNZBocKp4J',
			'pwu_E1CWq1poetw6',
			'pwu_s5V6mgNo6L00',
			'pwu_Eq8uA2iUep0M',
			'pwu_dNLubiPwqP88',
			'pwu_lGE0uzlHjCqL',
			'pwu_AJvewW2VMKKg',
			'pwu_pAmSiJpGT2Mi',
			'pwu_x67fE4CigiAw',
			'pwu_k44rJPxq7q7I',
			'pwu_B3rU3Xb2CKLa',
			'pwu_L0m7A3SKS1Bz',
			'pwu_gUuv0kTiXnzm',
			'pwu_ImTlrWFIdPAw',
			'pwu_NM78I2q4wRuc',
			'pwu_3vJtLNb8aHlv',
			'pwu_3TOI5GB4hFnt',
			'pwu_YXCllxucGT6T',
			'pwu_RJcifO2CWDQr',
			'pwu_62MiuwhqlhkV',
			'pwu_BCNNooa7bLit',
			'pwu_OIjtazFLT1xd',
			'pwu_BFyNiQOCGn4u',
			'pwu_qr3VQ9piTipA',
			'pwu_mSLEzm2wHBv7',
			'pwu_5WhhEwbiIsqE',
			'pwu_yZ0YWFwEeIvw',
			'pwu_iAh8ZrluVRRT',
			'pwu_lH4c76Dmse9U',
			'pwu_IhtVXguEm8uj',
			'pwu_HDObLIv2HOTN',
			'pwu_6v2rNrfC1fKw',
			'pwu_MvTGgaRnESNJ',
			'pwu_8l0ynERPqf8y',
			'pwu_ppDlfV20mQMB',
			'pwu_T02dpUSgg7iC',
			'pwu_4O6Z7QGiGWc3',
			'pwu_sElXIdghlOnD',
			'pwu_fiUtHLWqDXZb',
			'pwu_ZgkmOj2UYCDO',
			'pwu_yQH0tyJr6mv3',
			'pwu_FiMvimXtyEeu',
			'pwu_jh2K5vfBJMkG',
			'pwu_f0KbBpymEQJc',
			'pwu_dmCpSaPUOH4z',
			'pwu_VtYt2bAH03Mi',
			'pwu_QrGSto9miAeE',
			'pwu_cMeZxMN5O0Qw',
			'pwu_2m2yvxcm5OWC',
			'pwu_rSzoPdBCrj68',
			'pwu_etD6SpcpqDKS',
			'pwu_FXG9etf3QgHu',
			'pwu_Zt7C1zUg85h1',
			'pwu_MpUGVsEw59cu',
			'pwu_JG5jZ8cATXWU',
			'pwu_8s3IH3McbXBs',
			'pwu_6KdzCDxRPIWi',
			'pwu_XQtRjJFelD1I',
			'pwu_Q9uKqcpIqJmb',
			'pwu_crAABkAsbDk8',
			'pwu_c94JEM3eZxW4',
			'pwu_WUhWSxRj5iTa',
			'pwu_1Cpm2LHtMTuw',
			'pwu_RLElugdkDQqx',
			'pwu_ya1HyzZM3BvS',
			'pwu_uiC18rOOPARo',
			'pwu_K2cdq4xOkjgF',
			'pwu_3UmYYRp8d5AY',
			'pwu_ozBoRvIflc1i',
			'pwu_HsoBLNb0B6QA',
			'pwu_4VNYARA9k80A',
			'pwu_clBgIhnA8v1X',
			'pwu_nu7WwCjdIROP',
			'pwu_aPJpeln9ryJD',
			'pwu_JgIMZuP5dAk7',
			'pwu_nRZArInkSHfT',
			'pwu_P5IfuDhGj91T',
			'pwu_S6B7seRKNyWI',
			'pwu_KpIPVR19q5h4',
			'pwu_gAQ8CZxjoZOD',
			'pwu_Wrh0PRcEynkA',
			'pwu_w91P46nUD1HK',
			'pwu_s5z6b4DwE16c',
			'pwu_awZWutKWHwfc',
			'pwu_WHFs6grAgdvU',
			'pwu_j4EhJs5xjk0D',
			'pwu_e5OgSx4cJRIj',
			'pwu_DB1ogWT7JwZC',
			'pwu_fHoNdLUiIQX6',
			'pwu_Y9ZCWLzw5oBp',
			'pwu_akSFK5h0txwz',
			'pwu_dLBxSu0mzX6K',
			'pwu_RsbIV2mntLZJ',
			'pwu_xOMRDfPwHOR1',
			'pwu_iMEFyqF1IkuG',
			'pwu_fysbFPeMJGan',
			'pwu_DzgGGhVQHbQF',
			'pwu_QqqgAulwcKuj',
			'pwu_kaFMYEIVB2rY',
			'pwu_2pyGJPPa4jBJ',
			'pwu_FsQda8obrW7F',
			'pwu_Jow8yqwDRGAS',
			'pwu_mca8bkEkjG25',
			'pwu_dCM1mfkeCC6Z',
			'pwu_ebaucgPEDwRk',
			'pwu_VwLBW5IWpTUe',
			'pwu_bIWdL8vT1dBH',
			'pwu_vyY69rKZMUq1',
			'pwu_pe2ryNKdHz4B',
			'pwu_2mWjuIpS2mRW',
			'pwu_2DaHZfYqBaFy',
			'pwu_hGsk6dAMtvN4',
			'pwu_hUf2M9k3GO9a',
			'pwu_0rGXAiCcIVoH',
			'pwu_TytkNRxh4PeN',
			'pwu_L2rn636sM5tb',
			'pwu_Ew7hW7IQ7RiH',
			'pwu_WIT5FYEYmc09',
			'pwu_VM4KYJICi58m',
			'pwu_34Gt0AuRr93c',
			'pwu_G5zvZAW8P5IC',
			'pwu_tWEivFjZe4dh',
			'pwu_kGyKS2kOkngO',
			'pwu_AXM6nOcZpkED',
			'pwu_J9WMJPnptk23',
			'pwu_q2EeX18jlNYo',
			'pwu_MTfinZVrRcdn',
			'pwu_rlsY6zIbbBoH',
			'pwu_JXTeBolqCshD',
			'pwu_fXv2Wi3vTHib',
			'pwu_hZru0BXbTofv',
			'pwu_jUB4FTJJRuyq',
			'pwu_OtmXx4UTTdIU',
			'pwu_PQjt78VXxlk3',
			'pwu_2LNYy9kKhqi8',
			'pwu_dgKY6AH9T7x2',
			'pwu_yR1K11X5Yxiw',
			'pwu_MDMrFjcOruCZ',
			'pwu_jh6kb4dmMq0B',
			'pwu_j6m3Aa96Rngb',
			'pwu_z8HsOsdGXYq2',
			'pwu_9ZPjeMI2FttL',
			'pwu_LeHPfUiTtFke',
			'pwu_c6eCMeXPIYrg',
			'pwu_S5KtM1R4tEQ3',
			'pwu_RRMZiJDHh3Vh',
			'pwu_lIZ0Q6Iw199i',
			'pwu_xRsomnOHaA9r',
			'pwu_Aj9v7WT0rfIL',
			'pwu_g2AI97M4hNs8',
			'pwu_lmhNrFQ18u0N',
			'pwu_FIgT84Rl3b3E',
			'pwu_uHEdmtxCbalD',
			'pwu_dk6QXwxrSFDr',
			'pwu_f3iB37ECIw1O',
			'pwu_6lZ7ZDI5NApq',
			'pwu_WYSeTyQ4E4tM',
			'pwu_uHBqjWSzANHy',
			'pwu_BGsaT8LvUPPL',
			'pwu_Ip0NvWRlu2dk',
			'pwu_vTg5UQPEG8oe',
			'pwu_VH16rtW3mnos',
			'pwu_6IrSLhNwTRNB',
			'pwu_9XGMczDQc5DK',
			'pwu_X8c8dVTvZjGd',
			'pwu_MHwM4U2EHxas',
			'pwu_pLVSdcqbEht4',
			'pwu_oPXpzgbpKwQN',
			'pwu_BEtFH1H0gGMR',
			'pwu_4d9vlybDKSFn',
			'pwu_p79wkSjEepAx',
			'pwu_J3E97jC7h5oM',
			'pwu_xGmXgAErbKmT',
			'pwu_L1UXVSPSvxSL',
			'pwu_eTmh3VioocAV',
			'pwu_sqj23Ur2seii',
			'pwu_kIbxUwHGIvCG',
			'pwu_fm2wf0C8Y1nb',
			'pwu_H5My8CfcF0RA',
			'pwu_JtROAdTot8Cl',
			'pwu_PpljjRMOQVbV',
			'pwu_rSqkG1Srtffk',
			'pwu_wXYEqgn8o4PE',
			'pwu_SxD0ikM5ZDNI',
			'pwu_4l632ZkSsw68',
			'pwu_DjDhzwJdRwBL',
			'pwu_JjQH9fOfI8t3',
			'pwu_Sf6bFSwctnux',
			'pwu_obULtt35EtFr',
			'pwu_ctBLyIeY8usQ',
			'pwu_CprVt1Cx5AYB',
			'pwu_s1BOvf987wGw',
			'pwu_BznbzL2bQ33a',
			'pwu_XG2VLdUOtT5g',
			'pwu_gWBbgS4iw75z',
			'pwu_0IQ4QGykXxMo',
			'pwu_2SyM0n2CHLlW',
			'pwu_jsc8eqlpQqwq',
			'pwu_ZjB180YUNheb',
			'pwu_47in6S7ZYd1V',
			'pwu_sKLcK6SWKss2',
			'pwu_tG4HlSjE7zdA',
			'pwu_hMI6DraXIQtQ',
			'pwu_m8anQHkIik2l',
			'pwu_YvCJE4PqvI4H',
			'pwu_I9vJlwuoO2Dv',
			'pwu_s4YHHg9XU6Ei',
			'pwu_h7P5O2KZRWJq',
			'pwu_hTAMIjYJuOCN',
			'pwu_lRnXrS0R3dsq',
			'pwu_P1OnlAZYS9E5',
			'pwu_pc0fnIO3mRD0',
			'pwu_CA6YMOiCwgvA',
			'pwu_z7gI5YlOmYSO',
			'pwu_05G7CKYpBnXU',
			'pwu_G94hXDZgFzqU',
			'pwu_BO6mHUJb8XfD',
			'pwu_2XAEbCjC0nvP',
			'pwu_smSQrBBwFUf8',
		);

		foreach ( $user_keys as $key ) {
			try {
				ProfitWell_API::delete_user( $key );
			} catch ( \Exception $e ) {
			}
		}
	}

	static public function delete_all_data_4() {
		$user_keys = array(
			'pwu_twO2XOZSYXDD',
			'pwu_gCVBXrA5tegQ',
			'pwu_sFh03dV0AqU1',
			'pwu_7GPzBbALVcpF',
			'pwu_Z6B6GBRydqRP',
			'pwu_M1m4wA4d2ZgQ',
			'pwu_K752M1662SkK',
			'pwu_yhu10tTeISFe',
			'pwu_KgiYD5i9utTj',
			'pwu_26t7mg8H8SEA',
			'pwu_oNER8pwJRIO4',
			'pwu_sAUorZuKDTxn',
			'pwu_2gsKjc4mLJfS',
			'pwu_gTKx6SyWNx0r',
			'pwu_9y91JGHdapvX',
			'pwu_pbJloTZJZD5b',
			'pwu_PKwEVYHQsfnx',
			'pwu_vW7JubTWyA9N',
			'pwu_TbHfieUEuUlU',
			'pwu_dPNcyaEIkg21',
			'pwu_PpljjRMOQVbV',
			'pwu_0kz4uT8nlbnj',
			'pwu_LvoY3JXJMixj',
			'pwu_9ZPjeMI2FttL',
			'pwu_6Gy4v3EwGzRR',
			'pwu_taBcHLxTCrxM',
			'pwu_4KQ3uWalGE2L',
			'pwu_gXLgxI6l4vjP',
			'pwu_xCYBTPRGE1mS',
			'pwu_iKROYIi898Gl',
			'pwu_rtqCXgeXSCEU',
			'pwu_cLMPTx12PozU',
			'pwu_JcADjiVynCBE',
			'pwu_lbJhbr8vR5Jn',
			'pwu_FKaSHcbTG2DE',
			'pwu_El0o15lna6U3',
			'pwu_6gUBxBTujteG',
			'pwu_nTwSPt5HnEDw',
			'pwu_ad0h9nQ2i9AT',
			'pwu_S9F7q03HRg1T',
			'pwu_RBsIQiZOOFXD',
			'pwu_hHtS6aVkPjhl',
			'pwu_noB17zkovnJY',
			'pwu_0fYIQxAQXRmK',
			'pwu_SThdRvF5uvRP',
			'pwu_Vd6WLzBDCBbR',
			'pwu_20bsFbhOsUCj',
			'pwu_4j1NNd9P84ae',
			'pwu_EA9OC1lHyocT',
			'pwu_tfC5kt1MU05b',
			'pwu_4RuJDj46215J',
			'pwu_Hn2MzI1TBlWY',
			'pwu_iv0FRpQP9aV6',
			'pwu_rMS0z9L3B3ci',
			'pwu_VDzvqb9n8A72',
			'pwu_HvylY87vgLXf',
			'pwu_HGRjYqbvLrgY',
			'pwu_wzugtqn7oB1s',
			'pwu_CdapyNB76cOD',
			'pwu_u9wPHdZwl2Vz',
			'pwu_KgOXOJh7kQFl',
			'pwu_umXR8Chh41cD',
			'pwu_KRh753NA1AEO',
			'pwu_Q0E0sYQpR2d9',
			'pwu_rQZRpFmXr8OX',
			'pwu_FTBYQs7xIKaU',
			'pwu_w1cn39tq1oi3',
			'pwu_jhzxFfPg4G5t',
			'pwu_i0xOvnVqN3JK',
			'pwu_2pDpuWhD05pa',
			'pwu_Z5vB7eAnXMVa',
			'pwu_CL7lz6QdXmNS',
			'pwu_rn1HPKh1udRP',
			'pwu_SbXLnY7alEQJ',
			'pwu_kmtnnTaqAoGx',
			'pwu_JD0zq8b6D5Ia',
			'pwu_ZD0LIEkNCm4T',
			'pwu_2pkAaDzhCpqu',
			'pwu_eySpvw2YkLHO',
			'pwu_mKPLB1guL9iJ',
			'pwu_XG2VLdUOtT5g',
			'pwu_bPYFD9R3i8xY',
			'pwu_gWBbgS4iw75z',
			'pwu_BWLc5C02p46b',
			'pwu_5WreyJiZtlmi',
			'pwu_qyIGa2gdwBFc',
			'pwu_JLB6zpJiI3rc',
			'pwu_s7zeWasuIblh',
			'pwu_Xm7Bmj0tNh86',
			'pwu_iAXbItDxPDjw',
			'pwu_5GgkOkd1Z87j',
			'pwu_cQOrwfGjxJEF',
			'pwu_7HOsRJDrh7EP',
			'pwu_4BKUspyxFaYb',
			'pwu_wDtrcCWnZZWy',
			'pwu_WWyZkpDKaQV8',
			'pwu_fLEgeQ236FbZ',
			'pwu_Dp0R7qf2bFEy',
			'pwu_L4uLvcNHdx4v',
			'pwu_a3JoF6y5Mk8t',
			'pwu_OrT4psoYh8sk',
			'pwu_jSk7Ph9HDkm8',
			'pwu_b2yCLKibmhHl',
			'pwu_Z8EZFyfIK7vs',
			'pwu_lO0ky2OHssJv',
			'pwu_zPmnBRS6Inaa',
			'pwu_lcXwKeYea2qw',
			'pwu_gvxy4PTBJw2a',
			'pwu_a0lr4Et03XdB',
			'pwu_HPd4wkC0V7Kq',
			'pwu_zcwDXYm7QwQG',
			'pwu_P3jEu8oFDvLI',
			'pwu_yChAlXOYEozt',
			'pwu_Xz2WqOkfjLok',
			'pwu_JovnQfecsCop',
			'pwu_QdxEJdJXxaDg',
			'pwu_YZY6A7qBdBEM',
			'pwu_ux6hK7XumkN7',
			'pwu_nCATmUCp63EH',
			'pwu_ThAJMkretXOq',
			'pwu_1Ik84dn2bjXt',
			'pwu_TsnWRQsRWE4P',
			'pwu_7oWW93nUcf2w',
			'pwu_5HWKh0xu4LQ0',
			'pwu_WjOdNt1Yp1ZO',
			'pwu_dLWh5sNAJCkd',
			'pwu_AXM6nOcZpkED',
			'pwu_CylCAumGSzqm',
			'pwu_YdrJbNOEWMci',
			'pwu_wvsgJDb7eZOH',
			'pwu_3NqzvXZXeuOQ',
			'pwu_lhu6vqsKcTu2',
			'pwu_auy1aDXPHZKR',
			'pwu_lBmbpP2IPm92',
		);

		foreach ( $user_keys as $key ) {
			try {
				ProfitWell_API::delete_user( $key );
			} catch ( \Exception $e ) {
			}
		}
	}

	static public function migrate_membership( \RCP_Membership $membership ) {

		$id = $membership->get_id();
		echo "\n$id\n";

		$customer = $membership->get_customer();
		$member   = new \RCP_Member();

		if ( ! $customer ) {
			echo "no customer\n";
			var_dump( $membership );
			echo "\n";
			return;
		}

		if ( 'pending' === $membership->get_status() ) {
			echo "membership is pending\n";
			return;
		}

		if ( $membership->get_upgraded_from() ) {
			echo "membership {$id} is being updated\n";
			var_dump( $membership );
			echo "\n";

			Controller::update_subscription( $member, $customer, $membership );
			sleep( 1 );
		} else {
			echo "membership {$id} is being created\n";
			Controller::create_subscription( $member, $customer, $membership );
			sleep( 1 );
		}

		$subscription_id = rcp_get_membership_meta( $membership->get_id(), 'profitwell_subscription_id', true );

		if ( ! $subscription_id ) {
			echo "membership {$id} had no subscription id\n";
			return;
		}

		if ( ! $membership->is_active() && ! $membership->was_upgraded() ) {
			echo "membership {$id} is being churned\n";

			ProfitWell_API::churn_subscription( $subscription_id, strtotime( $membership->get_expiration_date( false ) ) );
			sleep( 1 );
		}

		echo rcp_get_membership_meta( $membership->get_id(), 'profitwell_subscription_id', true ) . "\n";
	}

	/**
	 * Update Members
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function update_members() {
		$user_keys = array(
			'pwu_h0gRikdRahje',
		);

		foreach ( $user_keys as $key ) {
			try {
				$reponse = ProfitWell_API::delete_user( $key );
			} catch ( \Exception $e ) {
			}
		}

		$emails = [
			'eldon1@objectiv.co',
		];

		foreach ( $emails as $email ) {
			$user       = get_user_by( 'email', $email );
			$user_id    = $user->ID;
			$member     = new Member( $user_id );
			$membership = $member->get_membership();

			// Controller::update_subscription( $membership );
			Controller::membership_added( $user );
		}
	}

	/**
	 * Fix Churned
	 *
	 * Fix customers that should not be churned
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function fix_churned() {
		// self::trial_memberships();
		// self::group_members();
		// self::updated_members();

		// $members = [
		// 	'wfriedland15@gmail.com',
		// 	'jonnoagnew@gmail.com',
		// 	'mike+ft@fppathfinder.com',
		// ];
		// self::individual_members( $members );
	}

	/**
	 * Trial Memberships
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function trial_memberships() {

		$memberships = rcp_get_memberships(
			array(
				'status__in' => array( 'expired' ),
				'number'     => 999999,
			)
		);

		$customers = self::get_trial_customers( $memberships );
		self::unchurn_customer( $customers );
	}

	/**
	 * Group Members
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function group_members() {
		$memberships = rcp_get_memberships(
			array(
				'status__in' => array( 'expired', 'cancelled' ),
				'number'     => 999999,
			)
		);

		$customers = self::get_group_customers( $memberships );
		self::unchurn_customer( $customers );
	}

	/**
	 * Updated Members
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function updated_members() {
		$memberships = rcp_get_memberships(
			array(
				'status__in' => array( 'active' ),
				'number'     => 999999,
			)
		);

		$customers = self::get_upgraded_customers( $memberships );
		self::unchurn_customer( $customers );
	}

	/**
	 * Individual Members
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $members The members to unchurn.
	 *
	 * @return void
	 */
	public static function individual_members( $members ) {
		$customers = self::get_updated_customers( $members );
		self::unchurn_customer( $customers );
	}


	/**
	 * Get Upgraded Customers
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $memberships The memberships.
	 *
	 * @return void
	 */
	public static function get_upgraded_customers( $memberships ) {
		$customers = [];

		foreach ( $memberships as $membership ) {

			if ( $membership->get_upgraded_from() <= 0 ) {
				continue;
			}

			$body = self::get_customer_data( $membership );

			if ( empty( $body ) ) {
				continue;
			}

			foreach ( $body as $entry ) {
				$customer_id = $entry->customer_id;

				if ( ! in_array( $customer_id, $customers, true ) ) {
					array_push( $customers, $customer_id );
				}
			}
		}

		return $customers;
	}

	/**
	 * Get Trial Customers
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $memberships The memberships.
	 *
	 * @return array
	 */
	public static function get_trial_customers( $memberships ) {
		$customers = [];

		foreach ( $memberships as $membership ) {
			$plan = $membership->get_object_id();

			if ( '9' !== $plan ) {
				continue;
			}

			$body = self::get_customer_data( $membership );

			if ( empty( $body ) ) {
				continue;
			}

			foreach ( $body as $entry ) {
				$customer_id = $entry->customer_id;

				if ( ! in_array( $customer_id, $customers, true ) ) {
					array_push( $customers, $customer_id );
				}
			}
		}

		return $customers;
	}

	/**
	 * Get Group Customers
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $memberships The memberships.
	 *
	 * @return array
	 */
	public static function get_group_customers( $memberships ) {
		$customers = [];

		foreach ( $memberships as $membership ) {
			$user_id = $membership->get_user_id();
			$groups  = Utilities::get_groups( $user_id );

			if ( empty( $groups ) ) {
				continue;
			}

			$body = self::get_customer_data( $membership );

			if ( empty( $body ) ) {
				continue;
			}

			foreach ( $body as $entry ) {
				$customer_id = $entry->customer_id;

				if ( ! in_array( $customer_id, $customers, true ) ) {
					array_push( $customers, $customer_id );
				}
			}
		}

		return $customers;
	}

	/**
	 * Get updated Customers
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $user_emails The user emails to unchurn.
	 *
	 * @return void
	 */
	public static function get_updated_customers( $user_emails ) {
		$customers = [];
		$user_ids  = [];

		foreach ( $user_emails as $user_email ) {
			$user = get_user_by( 'email', $user_email );
			array_push( $user_ids, $user->ID );
		}

		foreach ( $user_ids as $user_id ) {
			$membership = rcp_get_membership_by( 'user_id', $user_id );

			$body = self::get_customer_data( $membership );

			if ( empty( $body ) ) {
				continue;
			}

			foreach ( $body as $entry ) {
				$customer_id = $entry->customer_id;

				if ( ! in_array( $customer_id, $customers, true ) ) {
					array_push( $customers, $customer_id );
				}
			}
		}

		return $customers;
	}


	/**
	 * Get Customer Data
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param object $membership The user's membership.
	 *
	 * @return array
	 */
	public static function get_customer_data( $membership ) {
		$user_id    = $membership->get_user_id();
		$user_data  = $user_id ? get_userdata( $user_id ) : '';
		$user_email = ! empty( $user_data ) ? $user_data->user_email : '';

		if ( empty( $user_email ) ) {
			return [];
		}

		$response = ProfitWell_API::search_for_user( $user_email );

		if ( 200 !== $response['response']['code'] ) {
			return [];
		}

		$body = json_decode( $response['body'] );

		return $body;
	}

	/**
	 * Unchurn Customer
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $customers The customers.
	 *
	 * @return void
	 */
	public static function unchurn_customer( $customers ) {
		foreach ( $customers as  $customer ) {
			$response = ProfitWell_API::get_subscription_history_for_user( $customer );

			if ( is_wp_error( $response ) ) {
				continue;
			}

			$body = json_decode( $response['body'] );

			if ( empty( $body ) ) {
				continue;
			}

			foreach ( $body as $entry ) {
				if ( $entry->status === 'churned_voluntary' ) {
					$subscription_id = $entry->subscription_id;
					$email           = $entry->email;

					if ( empty( $subscription_id ) ) {
						continue;
					}

					$excludes = [
						'pws_VnGqAxVZrZsj',
						'pws_qXk5vOmeRZth',
						'pws_PdNRwKOffHrj',
					];

					if ( ! in_array( $subscription_id, $excludes, true ) ) {
						ProfitWell_API::unchurn_subscription( $subscription_id );
					}
				}
			}
		}
	}
}
