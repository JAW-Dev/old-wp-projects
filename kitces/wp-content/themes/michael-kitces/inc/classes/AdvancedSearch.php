<?php
/**
 * Advanced Search Form
 *
 * @package    Kitces
 * @subpackage Kitces/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) Date, Objectiv
 * @license    GPL-2.0
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( 'AdvancedSearch' ) ) {

	/**
	 * Advanced Search Form
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 */
	class AdvancedSearch {

		/**
		 * Team Transient Name
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var string
		 */
		protected $team_transient_name = '_kitces_menu_team_members';

		/**
		 * Authors Transient Name
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @var string
		 */
		protected $author_transient_name = '_kitces_menu_authors';

		/**
		 * Initialize the class
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function __construct() {
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
			add_filter( 'pre_get_posts', array( $this, 'search_query' ) );
			add_filter( 'query_vars', array( $this, 'query_vars' ) );
			add_action( 'profile_update', array( $this, 'update_transients' ) );
			add_action( 'user_register', array( $this, 'update_transients' ) );
			add_action( 'save_post', array( $this, 'update_transients' ) );
		}

		/**
		 * Query Vars
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array $vars The Query varaibles.
		 *
		 * @return array
		 */
		public function query_vars( $vars ) {
			$vars[] = 'by-author';
			$vars[] = 'add-author';
			$vars[] = 'by-category';
			$vars[] = 'from-date';
			$vars[] = 'to-date';
			return $vars;
		}

		/**
		 * Search Query
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param WP_Query $query The WP Query object.
		 *
		 * @return void
		 */
		public function search_query( $query ) {

			if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
				return;
			}

			$author     = ! empty( $query->query['by-author'] ) ? $query->query['by-author'] : '';
			$add_author = ! empty( $query->query['add-author'] ) ? $query->query['add-author'] : '';
			$category   = ! empty( $query->query['by-category'] ) ? $query->query['by-category'] : '';
			$from_date  = ! empty( $query->query['from-date'] ) ? $query->query['from-date'] : '';
			$to_date    = ! empty( $query->query['to-date'] ) ? $query->query['to-date'] : '';

			if ( ! empty( $author ) ) {
				$meta_query_args = array(
					'posts_per_page' => -1,
					'meta_query' => array(
						array(
							'key'     => 'additional_authors',
							'value'   => strval( $author ),
							'compare' => 'LIKE',
						),
					),
					'fields'         => 'ids',
				);

				$meta_query_query = new \WP_Query( $meta_query_args );

				$author_args = array(
					'posts_per_page' => -1,
					'author'         => $author,
					'fields'         => 'ids',
				);

				$author_query = new \WP_Query( $author_args );

				$ids = array_merge( $meta_query_query->posts, $author_query->posts );

				$query->set( 'post__in', $ids );
			}

			if ( ! empty( $add_author ) ) {
				$meta_query = array(
					array(
						'key'     => 'additional_authors',
						'value'   => $add_author,
						'compare' => 'LIKE',
					),
				);
				$query->set( 'meta_query', $meta_query );
			}

			if ( ! empty( $category ) ) {
				$query->set( 'cat', $category );
			}

			if ( ! empty( $from_date ) && empty( $to_date ) ) {
				$from_month = date( 'm', strtotime( $from_date ) );
				$from_year  = date( 'Y', strtotime( $from_date ) );

				$date_args = array(
					array(
						'year'  => $from_year,
						'month' => $from_month,
					),
				);

				$query->set( 'date_query', $date_args );
			}

			if ( ! empty( $from_date ) && ! empty( $to_date ) ) {
				$from_day   = date( 'd', strtotime( $from_date ) );
				$from_month = date( 'm', strtotime( $from_date ) );
				$from_year  = date( 'Y', strtotime( $from_date ) );

				$to_day   = date( 'd', strtotime( $to_date ) );
				$to_month = date( 'm', strtotime( $to_date ) );
				$to_year  = date( 'Y', strtotime( $to_date ) );

				$date_args = array(
					array(
						'after'     => array(
							'year'  => $from_year,
							'month' => $from_month,
							'day'   => $from_day,
						),
						'before'    => array(
							'year'  => $to_year,
							'month' => $to_month,
							'day'   => $to_day,
						),
						'inclusive' => true,
					),
				);

				$query->set( 'date_query', $date_args );
			}
		}

		/**
		 * Form
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function form() {
			$search_term = get_search_query() ? get_search_query() : '';
			?>
			<div class="search-wrap">
				<form method="get" class="search-form" id="advanced-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<div class="search-basic">
						<input type="text" class="search-form-input" name="s" id="advanced-s" placeholder="<?php esc_attr_e( 'Search this site...', 'numbers' ); ?>" value="<?php echo esc_attr( $search_term ); ?>" />
						<button type="submit" class="search-form-submit" name="submit"></button>
						<button class="advanced-toggle">Advanced</button>
					</div>

					<?php $this->advanced_form(); ?>
				</form>
				<button class="search-toggle">
					<svg width="16" height="16" viewBox="0 0 24 24" view fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13 11.162L4.515 2.677a1 1 0 0 0-1.414 0l-.424.424a1 1 0 0 0 0 1.414L11.162 13l-8.485 8.485a1 1 0 0 0 0 1.414l.424.424a1 1 0 0 0 1.414 0L13 14.838l8.485 8.485a1 1 0 0 0 1.414 0l.424-.424a1 1 0 0 0 0-1.414L14.838 13l8.485-8.485a1 1 0 0 0 0-1.414l-.424-.424a1 1 0 0 0-1.414 0L13 11.162z" fill="#333"/></svg>
				</button>
			</div>
			<?php
		}

		/**
		 * Mobile Form
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function mobile_form() {
			$search_term = get_search_query() ? get_search_query() : '';
			?>
			<div class="mobile-search-wrap">
				<form method="get" class="search-form" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<div class="search-basic">
						<input type="text" class="search-form-input" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'numbers' ); ?>" value="<?php echo esc_attr( $search_term ); ?>" />
						<button type="submit" class="search-form-submit" name="submit"></button>
						<button class="advanced-toggle">Advanced</button>
					</div>

					<?php $this->advanced_form(); ?>
				</form>
			</div>
			<?php
		}

		/**
		 * Advanced Form
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function advanced_form() {
			$author_param    = ! empty( $_GET['by-author'] ) ? sanitize_text_field( wp_unslash( $_GET['by-author'] ) ) : '';
			$category_param  = ! empty( $_GET['by-category'] ) ? sanitize_text_field( wp_unslash( $_GET['by-category'] ) ) : '';
			$from_date_param = ! empty( $_GET['from-date'] ) ? sanitize_text_field( wp_unslash( $_GET['from-date'] ) ) : '';
			$to_date_param   = ! empty( $_GET['to-date'] ) ? sanitize_text_field( wp_unslash( $_GET['to-date'] ) ) : '';
			$search_term     = get_search_query() ? get_search_query() : '';
			?>
			<div class="search-advanced">
				<div class="search-advanced__row">

					<div class="form-controlls">
						<label for="s">Search Term: </label>
						<input type="text" class="search-form-term" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'numbers' ); ?>" value="<?php echo esc_attr( $search_term ); ?>" />
					</div>


					<div class="form-controlls">
						<label for="advanced-author">Author: </label>
						<select id="advanced-author" class="advanced-search-form-input" name="by-author">
							<option value="">Select an Author</option>
							<?php
							$team_transient = get_transient( $this->team_transient_name );
							$team_query     = array();
      
							if ( ! empty( $team_transient ) ) {
								$team_query = $team_transient;
							} else {
								set_transient( $this->team_transient_name, $this->get_team_members(), 2592000 );
								$team_query = $this->get_team_members();
							}

							$author_transient = get_transient( $this->author_transient_name );
							$author_query     = array();

							if ( ! empty( $author_transient ) ) {
								$author_query = $author_transient;
							} else {
								set_transient( $this->author_transient_name, $this->get_authors(), 2592000 );
								$author_query = $this->get_authors();
							}

							?>
							<optgroup label="Kitces Team">
								<?php
								foreach ( $team_query as $team ) {
									?>
										<option value="<?php echo esc_attr( $team->ID ); ?>" <?php selected( $author_param, $team->ID ); ?>>
									<?php
										echo esc_html( $team->display_name );
									?>
										</option>
									<?php
								}
								?>
							</optgroup>
							<optgroup label="Guest Authors">
								<?php
								foreach ( $author_query as $author ) {
									$user_data   = get_userdata( $author->ID );
									$author_name =  $user_data->first_name . ' ' . $user_data->last_name;

									if ( empty( trim( $author_name ) ) ) {
										$author_name = $author->display_name;
									}
									?>
										<option value="<?php echo esc_attr( $author->ID ); ?>" <?php selected( $author_param, $author->ID ); ?>>
									<?php
										echo esc_html( $author_name );
									?>
										</option>
									<?php
								}
								?>
							</optgroup>
						</select>
					</div>

					<div class="form-controlls">
						<label for="advanced-category">Category: </label>
						<select id="advanced-category" class="advanced-search-form-input" name="by-category">
							<option value="">Select a Category</option>
							<?php
							$categories = get_categories();
							foreach ( $categories as $category ) {
								?>
									<option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category_param, $category->term_id ); ?>>
								<?php
								echo esc_html( $category->name );
								?>
									</option>
								<?php
							}
							?>
						</select>
					</div>
				</div>

				<div class="form-controlls">
				<label>Date: </label>
				<div class="date-range">
					<input type="text" class="datepicker from-date" name="from-date" placeholder="From Date" value="<?php echo esc_attr( $from_date_param ); ?>" />
					<input type="text" class="datepicker to-date" name="to-date" placeholder="To Date" value="<?php echo esc_attr( $to_date_param ); ?>" />
				</div>
				</div>
				<button type="submit" class="advanced-search-button search-form-submit" name="submit">Search</button>
			</div>
			<?php
		}

		/**
		 * Update Transients
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param WP_Post $post The posts object.
		 *
		 * @return void
		 */
		public function update_transients( $post ) {
			if ( get_post_type( $post ) === 'post' ) {
				set_transient( $this->team_transient_name, $this->get_team_members(), 2592000 );
				set_transient( $this->author_transient_name, $this->get_authors(), 2592000 );
			}
			set_transient( $this->team_transient_name, $this->get_team_members(), 2592000 );
			set_transient( $this->author_transient_name, $this->get_authors(), 2592000 );
		}

		/**
		 * Get Team Members
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function get_team_members() {
			global $wpdb;

			$prepare = $wpdb->prepare(
				"SELECT u.ID, u.display_name
				 FROM $wpdb->users u
				 LEFT JOIN $wpdb->usermeta um
				 ON u.ID = um.user_id
				 WHERE um.meta_key = %s
				 AND meta_value = %d",
				'author_kitces_team_member',
				1
			);

			$query = $wpdb->get_results( $prepare );

			return $query;
		}

		/**
		 * Get Authors
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function get_authors() {
			global $wpdb;

			$prepare = $wpdb->prepare(
				"SELECT *
				 FROM $wpdb->users
				 INNER JOIN $wpdb->usermeta
				 ON ( {$wpdb->prefix}users.ID = {$wpdb->prefix}usermeta.user_id )
				 WHERE {$wpdb->prefix}users.ID
				 IN (
					 SELECT user_id
					 FROM {$wpdb->prefix}usermeta
					 WHERE meta_key = %s
					 AND meta_value LIKE %s)
					 AND {$wpdb->prefix}usermeta.meta_key = %s
					 ORDER BY {$wpdb->prefix}usermeta.meta_value ASC",
				"{$wpdb->prefix}capabilities",
				'%guest_author%',
				'last_name'
			);

			$query = $wpdb->get_results( $prepare );

			return $query;
		}
	}
}