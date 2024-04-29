<?php
/**
 * Tabs Abstract.
 *
 * @package    Fp_Account_Settings
 * @subpackage Fp_Account_Settings/Inlcudes/Classes/Template_Parts/Tabs
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FpAccountSettings\Includes\Classes\TemplateParts\Tabs;

use FpAccountSettings\Includes\Classes\TemplateParts\Fields;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Tabs Abstract.
 *
 * @author Objectiv
 * @since  1.0.0
 */
abstract class TabsAbstract {

	/**
	 * Slug
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Args
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Logo Field
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $logo_field;

	/**
	 * Text Field
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $text_field;

	/**
	 * Colorset Field
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $colorset_field;

	/**
	 * Hidden Field
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $hidden_field;

	/**
	 * Wysiwyg Field
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $wysiwyg_field;

	/**
	 * Textarea Field
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var object
	 */
	protected $textarea_field;

	/**
	 * Set transients
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $set_tranisents = true;

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @param string $slug The section slug.
	 * @param array  $args The section args.
	 *
	 * @return void
	 */
	public function __construct( $slug, $args = [] ) {
		$this->slug = $slug;
		$this->args = $args;

		$this->logo_field     = new Fields\Logo();
		$this->text_field     = new Fields\Text();
		$this->colorset_field = new Fields\ColorSet();
		$this->hidden_field   = new Fields\Hidden();
		$this->wysiwyg_field  = new Fields\Wysiwyg();
		$this->textarea_field = new Fields\Textarea();

		$this->render();
	}

	/**
	 * Init Classes
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function render() {
		$title   = $this->args['title'];
		$page    = strtolower( str_replace( ' ', '-', $title ) );
		$id_name = ! empty( $title ) ? strtolower( str_replace( ' ', '-', $title ) ) : '';
		?>
		<div
			class="sub-tabs-<?php echo esc_attr( $this->slug ); ?> tabs__body-content"
			data-page="<?php echo esc_attr( $page ); ?>"
			role="tabpanel"
			id="<?php echo esc_attr( $id_name ); ?>-sub-tab"
			aria-labelledby="<?php echo esc_attr( $id_name ); ?>"
			aria-expanded="true">
			<?php
				$this->maybe_render_blurb();
				$this->render_content();
			?>
		</div>
		<?php
	}

	/**
	 * Maybe Render Blurb
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_render_blurb() {
		$section_slug = isset( $this->args['section_slug'] ) ?? '';

		if ( ! empty( $this->args[ 'blurb_' . $section_slug ] ) ) :
			?>
			<section class="tabs__body-blurb">
				<?php echo wp_kses_post( $this->args[ 'blurb_' . $this->args['section_slug'] ] ); ?>
			</section>
			<?php
		endif;
	}

	/**
	 * Render Content
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function render_content() {
	}
}
