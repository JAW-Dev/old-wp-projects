<?php
/**
 * Template: Edit Group Details
 *
 * For modifying this template, please see: http://docs.restrictcontentpro.com/article/1738-template-files
 *
 * @package   rcp-group-accounts
 * @copyright Copyright (c) 2019, Restrict Content Pro team
 * @license   GPL2+
 * @since     1.0
 */

use function RCPGA\Shortcodes\get_dashboard;

global $rcp_options;

$dashboard = get_dashboard();

if ( empty( $dashboard->get_group() ) ) {
	return;
}

?>
<form method="post" id="rcpga-group-edit-form" class="rcp_form">

	<fieldset>
		<p id="rcpga-group-name-wrap">
			<label for="rcpga-group-name"><?php _e( 'Group Name', 'rcp-group-accounts' ); ?></label>
			<input type="text" name="rcpga-group-name" id="rcpga-group-name" placeholder="<?php esc_attr_e( 'Group Name', 'rcp-group-accounts' ); ?>" value="<?php // echo esc_attr( $dashboard->get_group()->get_name() ); ?>" autocomplete="off"/>
		</p>

		<p id="rcpga-group-description-wrap">
			<label for="rcpga-group-description"><?php _e( 'Group Description', 'rcp-group-accounts' ); ?></label>
			<textarea name="rcpga-group-description" id="rcpga-group-description" placeholder="<?php esc_attr_e( 'Group description', 'rcp-group-accounts' ); ?>"><?php echo esc_textarea( $dashboard->get_group()->get_description() ); ?></textarea>
		</p>

		<p class="rcp_submit_wrap">
			<input type="hidden" name="rcpga-group" value="<?php echo absint( $dashboard->get_group()->get_group_id() ); ?>"/>
			<input type="hidden" name="rcpga-action" value="edit-group"/>
			<?php wp_nonce_field( 'rcpga_edit_group', 'rcpga_edit_group_nonce' ); ?>
			<input type="submit" value="<?php _e( 'Update Group', 'rcp-group-accounts' ); ?>"/>
		</p>
	</fieldset>

</form>
