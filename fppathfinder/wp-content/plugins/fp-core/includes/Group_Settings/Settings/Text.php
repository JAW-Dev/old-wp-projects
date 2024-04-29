<?php

namespace FP_Core\Group_Settings\Settings;

abstract class Text extends Setting {
	public function __construct() {}

	abstract public function get_label(): string;

	public function output_field( int $group_id ) {
		?>
		<div style="display: flex; align-items: center; margin-top: 1rem;">
			<label style="margin: 0 1rem 0 0; line-height: 1;" for="no_advisor_names"><?php echo $this->get_label(); ?></label>
			<input type="text" id="<?php echo $this->get_name(); ?>" name="<?php echo $this->get_name(); ?>" value="<?php echo $this->get( $group_id ); ?>">
		</div>
		<?php
	}

	public function handle_submit( int $group_id ) {
		$this->set( $group_id, $_POST[ $this->get_name() ] ?? '' );
	}
}
