<?php

namespace MK_Link_Builder;

class Main {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'add_meta_boxes_post', array( $this, 'add_meta_box' ) );
	}

	public function scripts( $hook = null ) {

		if ( $hook != 'post.php' ) {
			return;
		}

		wp_enqueue_script( 'kitces-admin-copy', MK_LIST_BUILDER_PLUGIN_URL . 'assets/admin/js/main.js' );
	}

	public function add_meta_box() {
		add_meta_box( 'kitces-link-builder', __( 'Link Builder' ), array( $this, 'kitces_link_builder_metabox' ), null, 'side', 'high' );
	}

	public function kitces_link_builder_metabox() {
		$categories = get_field( 'weekend_reading_tag_categories', 'option' );
		$cats_array = null;

		if ( ! empty( $categories ) ) {
			$cats_array = explode( ',', $categories );
		}

		?>
		<style>
			#kitces-link-builder-form .input-wrap {
				margin-top: 0.6rem;
			}

			#kitces-link-builder-form-submit {
				margin-top: 1rem;
			}

			#kitces-link-builder-form input[type="text"],
			#kitces-link-builder-form select {
				margin-top: 0.2rem;
				width: 100%;
				max-width: 100%;
			}

			#kitces-link-builder-form select {
				display: block;
				max-width: calc(100% - 64px);
			}

			#kitces-link-builder-error {
				color: red;
			}
		</style>
		<div class="kitces-link-builder-wrap">
			<p class="">Use the following form to generate a weekend reading tracking link. You can add/remove available categories <a href="/wp/wp-admin/admin.php?page=theme-general-settings">here</a></p>
			<p id="kitces-link-builder-error"></p>
			<div id="kitces-link-builder-form">
				<div class="input-wrap">
					<label for="kitces_link_builder_url" class="">Url</label>
					<input name="kitces_link_builder_url" type="text" id="kitces_link_builder_url">
				</div>
				<div class="input-wrap">
					<label for="kitces_link_builder_link_text" class="">Link Text</label>
					<input name="kitces_link_builder_link_text" type="text" id="kitces_link_builder_link_text">
				</div>
				<div class="input-wrap">
					<label for="kitces_link_builder_title" class="">Title (for title attribute)</label>
					<input name="kitces_link_builder_title" type="text" id="kitces_link_builder_title">
				</div>
				<?php if ( ! empty( $cats_array ) && is_array( $cats_array ) ) : ?>
					<div class="input-wrap">
						<label for="kitces_link_builder_link_category" class="">Tracking Link Category</label>
						<select name="kitces_link_builder_link_category" id="kitces_link_builder_link_category">
							<option selected="selected" value="">Select</option>
							<?php foreach ( $cats_array as $cat ) : ?>
								<option value="<?php echo str_replace( ' ', '-', trim( $cat ) ); ?>"><?php echo $cat; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>
				<div class="input-wrap">
					<label for="kitces_link_builder_new_tab">
						<input name="kitces_link_builder_new_tab" type="checkbox" id="kitces_link_builder_new_tab" checked>
						Open link in a new tab
					</label>
				</div>
				<div class="input-wrap">
					<label for="kitces_link_builder_nofollow">
						<input name="kitces_link_builder_nofollow" type="checkbox" id="kitces_link_builder_nofollow">
						Add <code>rel="nofollow"</code> to link
					</label>
				</div>
				<div class="input-wrap">
					<label for="kitces_link_builder_sponsored">
						<input name="kitces_link_builder_sponsored" type="checkbox" id="kitces_link_builder_sponsored">
						Add <code>rel="sponsored"</code> to link
					</label>
				</div>
				<div class="input-wrap">
					<label for="kitces_link_builder_ugc">
						<input name="kitces_link_builder_ugc" type="checkbox" id="kitces_link_builder_ugc">
						Add <code>rel="UGC"</code> to link
					</label>
				</div>
				<button id="kitces-link-builder-form-submit" class="button button-primary">Generate & Copy Link</button>
			</div>
		</div>
		<?php
	}
}
