<?php
/**
 * UserIntegrationAbstract
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms;

use FP_Core\InittableInterface;

abstract class UserIntegrationAbstract implements InittableInterface {
	static protected $integration_slugs = array();

	protected $name;
	protected $slug;
	protected $settings_page;
	protected $activation_meta_key;

	public function __construct( string $slug, string $name ) {
		if ( isset( self::$integration_slugs[ $slug ] ) ) {
			throw new \Exception( "UserIntegrationAbstracts must have unique slugs. Duplicate found:{$slug}" );
		}

		self::$integration_slugs[] = $slug;
		$this->slug                = $slug;
		$this->name                = $name;
		$this->activation_meta_key = "{$slug}_integration_active";
	}

	public function get_slug(): string {
		return $this->slug;
	}

	public function get_name(): string {
		return $this->name;
	}

	public function is_active( int $user_id ): bool {
		$this->crm_reset();
		return get_user_meta( $user_id, $this->activation_meta_key, true );
	}

	/**
	 * CRM Reset
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function crm_reset() {
		$user_id     = get_current_user_id();
		$current_crm = get_user_meta( $user_id, 'current_active_crm', true );

		if ( ! empty( $current_crm ) ) {
			return;
		}

		$crm_keys = array(
			'wealthbox' => get_user_meta( $user_id, 'wealthbox_tokens', true ),
			'redtail'   => get_user_meta( $user_id, 'redtail_tokens', true ) ? get_user_meta( $user_id, 'redtail_tokens', true ) : get_user_meta( $user_id, 'redtail_user_key', true ),
		);

		if ( empty( $crm_keys ) ) {
			return;
		}

		foreach ( $crm_keys as $key => $value ) {
			if ( empty( $value ) ) {
				continue;
			} else {
				update_user_meta( $user_id, 'current_active_crm', $key );
				break;
			}
		}
	}

	abstract public function init();
	abstract public function get_settings_page( int $user_id ): string;
}
