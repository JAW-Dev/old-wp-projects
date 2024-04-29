<?php
/**
 * View
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Admin/RCP/Groups
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Admin\RCP\Groups;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * View
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class View {

	/**
	 * Group Types
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $group_types;

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->set_group_types();
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
		add_action( 'rcpga_add_group_form_fields_after', [ $this, 'render_add_group' ] );
		// add_action( 'rcpga_edit_group_form_fields_after', [ $this, 'render_edit_group' ] );
	}

	/**
	 * Render Add Group
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function render_add_group() {
		?>
		<tr>
			<th scope="row" class="row-title">
				<label for="rcpga-group-type"><?php _e( 'Type:', 'rcp-group-accounts' ); ?></label>
			</th>
			<td style="display: flex; position: relative;">
				<?php
				foreach ( $this->group_types as $type ) {
					?>
					<input style="position: relative; top: 5px;" type="radio" id="rcpga-group-type-<?php echo esc_attr( $type ); ?>" name="rcpga-group-type" autocomplete="off" value="<?php echo esc_attr( $type ); ?>"/>
					<label for="rcpga-group-type-<?php echo esc_attr( $type ); ?>"  style="margin-right: 1rem;">
						<?php echo esc_html( ucwords( $type ) ); ?>
					</label>
					<?php
				}
				?>

			</td>
		</tr>
		<?php
	}

	/**
	 * Set Group Types
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function set_group_types() {
		$this->group_types = [
			'basic',
			'exclusive',
		];
	}

	/**
	 * Get Group Types
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function get_group_types() {
		return $this->group_types;
	}
}
