<?php

namespace FP_Core;

class EnterpriseEssentialsSettings extends \WordPress_SimpleSettings {

	public $prefix = 'enterprise_essentials';

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'register_settings_pages' ) );
	}

	public function register_settings_pages() {
		add_options_page( 'Enterprise Essentials Settings 2', 'Enterprise Essentials 2', 'manage_options', 'enterprise-essentials-settings_2', array( $this, 'output_general_settings_page' ) );
		add_options_page( 'Enterprise Essentials Deluxe Discount Settings 2', 'Enterprise Essentials Deluxe Discounts 2', 'manage_options', 'enterprise-essentials-deluxe-discount-settings_2', array( $this, 'output_deluxe_discount_settings_page' ) );
	}

	/**
	 * Enterprise Essentials Get Group Discount String
	 *
	 * Gets the string used to store that group's individual deluxe upgrade discount.
	 *
	 * @param int $group_id Id for the group.
	 *
	 * @return string
	 */
	static function get_group_discount_string( $group_id ) {
		return 'enterprise_essentials_group_' . $group_id . '_discount';
	}

	/**
	 * Output General Settings Page
	 *
	 * Display the settings page.
	 *
	 * @return void
	 */
	function output_general_settings_page() {
		?>
			<div class="wrap">
				<h2>fpPathfinder Enterprise Essentials</h2>
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" style="max-width: 500px; display: flex; flex-direction: column; align-items: start;">
					<?php $this->the_nonce(); ?>

					<label for="<?php echo $this->get_field_name( 'stripe_deluxe_product_id' ); ?>">Stripe Deluxe Product ID:</label>
					<input placeholder="prod_xxxx" type="text" name="<?php echo $this->get_field_name( 'stripe_deluxe_product_id' ); ?>" value="<?php echo $this->get_setting( 'stripe_deluxe_product_id' ); ?>" /><br />

					<input class="button-primary" type="submit" value="Save Settings" style="margin-top: 3rem;" />
				</form>
			</div>
		<?php
	}

	/**
	 * Output Deluxe Discount Settings Page
	 *
	 * Display the deluxe discount settings page.
	 *
	 * @return void
	 */
	function output_deluxe_discount_settings_page() {
		$groups                         = rcpga_get_groups();
		$is_enterprise_essentials_group = function ( \RCPGA_Group $group ) {
			Utilities::group_is_active_at_level( $group, FP_ENTERPRISE_ESSENTIALS_ID );
		};
		$enterprise_essentials_groups   = array_filter( $groups, $is_enterprise_essentials_group );
		?>
			<div class="wrap">
				<h2>fpPathfinder Enterprise Essentials Deluxe Discounts</h2>
				<p>Set the dollar amount discount for an individual's upgrade to Deluxe in each group.</p>
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" style="max-width: 500px; display: flex; flex-direction: column; align-items: start;">
					<?php
					$this->the_nonce();

					foreach ( $groups as $group ) {
						$group_string = $this->get_group_discount_string( $group->get_group_id() );
						$field_name   = $this->get_field_name( $group_string );
						$value        = $this->get_setting( $group_string );
						?>
							<label for="<?php echo $field_name; ?>"><?php echo $group->get_name(); ?> (ID: <?php echo $group->get_group_id(); ?>)</label>
							<input placeholder="0" type="number" min='0' pattern="\d+" name="<?php echo $field_name; ?>" value="<?php echo $value; ?>" /><br />
						<?php
					}
					?>
					<input class="button-primary" type="submit" value="Save Settings" style="margin-top: 3rem;" />
				</form>
			</div>
		<?php
	}
}
