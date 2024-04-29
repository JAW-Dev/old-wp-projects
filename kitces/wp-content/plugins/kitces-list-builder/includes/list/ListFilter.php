<?php

namespace MKLB;

class ListFilter {

	private $list_details = array();
	private $list_fields  = array();
	private $list_filters = array();
	private $list_posts   = array();

	public function __construct( $list_details = null, $list_posts = null ) {

		if ( ! empty( $list_details ) && is_array( $list_details ) ) {
			$this->list_details = $list_details;
			$this->list_filters = mk_key_value( $list_details, 'filters_filters' );
			$this->list_fields  = mk_key_value( $list_details, 'item_fields' );
		}

		if ( ! empty( $list_posts ) && is_array( $list_posts ) ) {
			$this->list_posts = $list_posts;
		}

	}

	public function get_markup() {

		$markup   = null;
		$continue = ! empty( $this->list_filters ) && ! empty( $this->list_posts ) && ! empty( $this->list_details ) && ! empty( $this->list_fields );

		if ( $continue ) {

			ob_start();
			foreach ( $this->list_filters as $filter_details ) {

				$field_slug    = mk_key_value( $filter_details, 'field' );
				$field_details = $this->get_field_details( $field_slug );
				$filter_type   = mk_key_value( $field_details, 'type' );
				$filter_label  = mk_key_value( $filter_details, 'label' );
				$csl           = false;

				if ( 'csl' === $filter_type ) {
					$csl = true;
				}

				if ( 'csl' === $filter_type || 'text-short' === $filter_type ) {

					if ( empty( $filter_label ) ) {
						$filter_label = mk_key_value( $field_details, 'label' );
					}

					echo $this->get_select_output( $field_slug, $filter_label, $csl );
				}
			}

			$markup = ob_get_contents();
			ob_end_clean();
		}

		return $markup;

	}

	private function get_field_details( $slug = null ) {

		foreach ( $this->list_fields as $field ) {
			$field_slug = mk_key_value( $field, 'slug' );

			if ( $slug === $field_slug ) {
				return $field;
			}
		}

		return null;
	}

	public function get_select_output( $filter_slug, $filter_label, $csl ) {
		$select_options = array();

		foreach ( $this->list_posts as $post ) {
			$field_value = mk_get_field( $filter_slug, $post->ID );

			if ( $csl ) {
				$pieces = explode( ',', $field_value );
				foreach ( $pieces as $piece ) {
					$piece_name = trim( $piece );
					$piece_slug = sanitize_title_with_dashes( $piece_name );

					if ( ! empty( $piece_name ) ) {
						$select_options[ $piece_slug ] = $piece_name;
					}
				}
			} else {
				$piece_name = trim( $field_value );
				$piece_slug = sanitize_title_with_dashes( $piece_name );

				if ( ! empty( $piece_name ) ) {
					$select_options[ $piece_slug ] = $piece_name;
				}
			}
		}

		ksort( $select_options );

		if ( ! empty( $select_options ) && is_array( $select_options ) ) {
			ob_start();
			?>
				<select class="list-filter-select" data-filter-key="<?php echo $filter_slug; ?>" style="max-width: 250px;">

					<?php if ( ! empty( $filter_label ) ) : ?>
						<option selected value="all">All <?php echo $filter_label; ?>s</option>
					<?php endif; ?>

					<?php foreach ( $select_options as $key => $name ) : ?>
						<option value="<?php echo $key; ?>"><?php echo $name; ?></option>
					<?php endforeach; ?>

				</select>
			<?php
			$select_markup = ob_get_contents();
			ob_end_clean();
		}

		return $select_markup;
	}
}
