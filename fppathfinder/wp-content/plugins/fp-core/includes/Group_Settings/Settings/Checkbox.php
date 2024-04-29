<?php

namespace FP_Core\Group_Settings\Settings;

abstract class Checkbox extends Setting {
	public function __construct() {}

	abstract public function get_label(): string;

	public function output_field( int $group_id ) {
		?>
		<div style="display: flex; align-items: center; margin: 1rem 0">
			<input type="checkbox" id="<?php echo $this->get_name(); ?>" name="<?php echo $this->get_name(); ?>" <?php echo $this->get( $group_id ); ?>>
			<label style="margin: 0 0 0 0.5rem; line-height: 1;" for="no_advisor_names"><?php echo $this->get_label(); ?></label>
		</div>
		<?php
	}

	public function handle_submit( int $group_id ) {
		$checked = $_POST[ $this->get_name() ] ?? false;

		$this->set( $group_id, $checked ? 'checked' : '' );
	}
}
