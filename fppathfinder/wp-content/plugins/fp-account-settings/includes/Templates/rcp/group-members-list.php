<?php
/**
 * Template: Group Members List
 *
 * For modifying this template, please see: http://docs.restrictcontentpro.com/article/1738-template-files
 *
 * @package   rcp-group-accounts
 * @copyright Copyright (c) 2019, Restrict Content Pro team
 * @license   GPL2+
 * @since     1.0
 */

use function RCPGA\Shortcodes\get_dashboard;

$dashboard = get_dashboard();

top_actions( $dashboard );
members( $dashboard );
pagination( $dashboard );

/**
 * The role filter and search
 *
 * @param object $dashboard The member dashboard.
 *
 * @return void
 */
function top_actions( $dashboard ) {
	?>
	<div class="member-top-actions tabs__body-section">
		<?php
		role_filter( $dashboard );
		member_search( $dashboard );
	?>
	</div>
	<?php
}

/**
 * Filter by roles
 *
 * @param object $dashboard The member dashboard.
 *
 * @return void
 */
function role_filter( $dashboard ) {
	$current_role = ! isset( $_GET['rcpga-role'] ) ? 'total' : urldecode( $_GET['rcpga-role'] );
	?>
	<div id="rcpga-group-members-list-roles-filter" class="member-role-links__wrap">
		<?php
		$counts = rcpga_get_group_member_counts( array( 'group_id' => $dashboard->get_group()->get_group_id() ) );
		foreach ( $counts as $role => $count ) {
			if ( 'total' == $role ) :
				$url = remove_query_arg( 'rcpga-role' );
				$url = add_query_arg( 'scroll', 'members-sub-tab', $url ) . '#group-settings';
				?>
				<a href="<?php echo esc_url( $url ); ?>" class="member-role-links__item" <?php echo $role == $current_role ? 'style="color: #3b82f6;"' : ''; ?>><?php printf( '%s (%d)', __( 'Total', 'rcp-group-accounts' ), $count ); ?></a>
			<?php
			else :
				$url = add_query_arg( array( 'rcpga-role' => urlencode( $role ), 'scroll' => 'members-sub-tab' ) ) . '#group-settings';
				?>
				<a href="<?php echo esc_url( $url ); ?>" class="member-role-links__item" <?php echo $role == $current_role ? 'style="color: #3b82f6;"' : ''; ?>><?php printf( '%s (%d)', rcpga_get_member_role_label( $role ), $count ); ?></a>
			<?php endif; ?>
			<?php
		}
		?>
	</div>
	<?php
}

/**
 * Search for members
 *
 * @param object $dashboard The member dashboard.
 *
 * @return void
 */
function member_search( $dashboard ) {
	$search = ! empty( $_GET['rcpga-search'] ) ? rawurldecode( $_GET['rcpga-search'] ) : '';
	$url    = add_query_arg( 'scroll', 'members-sub-tab', get_permalink() ) . '#group-settings';
	?>
	<div class="member-search__wrap">
		<form id="rcpga-members-search" class="member-search__form" method="GET" action="<?php echo esc_url( $url ); ?>">
			<label for="rcpga-members-search-input" class="screen-reader-text" style="visibility: hidden;"><?php _e( 'Search Members', 'rcp-group-accounts' ); ?></label>
			<input type="search" id="rcpga-members-search-input" name="rcpga-search" placeholder="<?php esc_attr_e( 'Username or Email', 'rcp-group-accounts' ); ?>" value="<?php echo esc_attr( $search ); ?>">
			<input type="hidden" name="rcpga-group" value="<?php echo absint( $dashboard->get_group()->get_group_id() ); ?>"/>
			<input type="submit" class="button" value="<?php esc_attr_e( 'Search', 'rcp-group-accounts' ); ?>">
		</form>
	</div>
	<?php
}

/**
 * Display members list
 *
 * @param object $dashboard The member dashboard.
 *
 * @return void
 */
function members( $dashboard ) {
	$current_page = add_query_arg( 'rcpga-group', urlencode( $dashboard->get_group()->get_group_id() ) );
	$members      = $dashboard->get_members_list();
	?>
	<section class="body-section__members-list">
		<?php
		if ( ! empty( $members ) ) {
			foreach ( $members as $member ) {
				$user_data = get_userdata( $member->get_user_id() );
				$add_hash  = '#group-settings';
				do_action( 'rcpga_before_member_data', $user_data, $member, $dashboard->get_group() );
				?>
				<div class="white-content-box">
					<div class="white-content-box__inner">
						<div class="white-content-box__left">
							<h4 class="member-name" data-th="<?php esc_attr_e( 'Name', 'rcp-group-accounts' ); ?>">
								<?php echo esc_html( $user_data->display_name ); ?>
							</h4>
						</div>
						<div class="white-content-box__right members-list__right">
							<div class="member-role pill-button pill-button__red" data-th="<?php esc_attr_e( 'Role', 'rcp-group-accounts' ); ?>">
								<?php echo esc_html( rcpga_get_member_role_label( $member->get_role() ) ); ?>
							</div>
							<div class="member-actions" data-th="<?php esc_attr_e( 'Actions', 'rcp-group-accounts' ); ?>">
								<?php
								if ( 'owner' !== $member->get_role() ) :
									$url = wp_nonce_url(
										add_query_arg(
											array(
												'rcpga-action'    => 'remove-member',
												'group-member-id' => absint( $member->get_id() ),
												'scroll'          => 'members-sub-tab',
											),
											$current_page
										) . '#group-settings',
									'rcpga_remove_from_group' );
									?>
									<a href="<?php echo esc_url( $url ); ?>"><?php _e( 'Remove from Group', 'rcp-group-accounts' ); ?></a>
									<br/>
									<?php
									if ( 'admin' === $member->get_role() ) :
										$url = wp_nonce_url(
											add_query_arg(
												array(
													'rcpga-action'    => 'make-member',
													'group-member-id' => absint( $member->get_id() ),
													'scroll'          => 'members-sub-tab',
												),
												$current_page
											) . '#group-settings',
										'rcpga_make_member' );
										?>
										<a href="<?php echo esc_url( $url ); ?>"><?php _e( 'Set as Member', 'rcp-group-accounts' ); ?></a>
										<?php
									elseif ( 'member' == $member->get_role() ) :
										$url = wp_nonce_url(
											add_query_arg(
												array(
													'rcpga-action'    => 'make-admin',
													'group-member-id' => absint( $member->get_id() ),
													'scroll'          => 'members-sub-tab',
												),
												$current_page
											) . '#group-settings',
										'rcpga_make_admin' );
										?>
										<a href="<?php echo esc_url( $url ); ?>"><?php _e( 'Set as Admin', 'rcp-group-accounts' ); ?></a>
										<?php
									elseif ( 'invited' == $member->get_role() ) :
										$url1 = wp_nonce_url(
											add_query_arg(
												array(
													'rcpga-action'    => 'make-member',
													'group-member-id' => absint( $member->get_id() ),
													'scroll'          => 'members-sub-tab',
												),
												$current_page
											) . '#group-settings',
										'rcpga_make_member' );
										$url2 = wp_nonce_url(
											add_query_arg(
												array(
													'rcpga-action'    => 'resend-invite',
													'group-member-id' => absint( $member->get_id() ),
													'scroll'          => 'members-sub-tab',
												),
												$current_page
											) . '#group-settings',
										'rcpga_resend_invite' );
									?>
										<a href="<?php echo esc_url( $url1 ); ?>"><?php _e( 'Set as Member', 'rcp-group-accounts' ); ?></a> |
										<a href="<?php echo esc_url( $url2 ); ?>"><?php _e( 'Resend Invite', 'rcp-group-accounts' ); ?></a>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php
				do_action( 'rcpga_after_member_data', $user_data, $member, $dashboard->get_group() );
			}
		} else {
			?>
			<div class="body-section__box">
				<?php
				if ( ! empty( $search ) ) {
					printf( __( 'Member &#8220;%s&#8221; not found. <a href="%s">Clear search.</a>', 'rcp-group-accounts' ), esc_html( $search ), esc_url( remove_query_arg( 'rcpga-search' ) ) );
				} else {
					_e( 'No members found.', 'rcp-group-accounts' );
				}
				?>
			</div>
			<?php
		}
		?>
	</section>
	<?php
}

/**
 * Member list pagination
 *
 * @param object $dashboard The member dashboard.
 *
 * @return void
 */
function pagination( $dashboard ) {
	$total_members = $dashboard->get_members_list_count();
	$total_pages   = ceil( $total_members / 50 );

	if ( $total_pages > 1 ) {
		?>
		<div id="rcpga-group-members-pagination" class="member-pagination">
			<?php
			$big = 999999;

			add_filter(
				'paginate_links',
				function ( $link ) {
					$link = strtok( $link, '?' );

					return $link . '?scroll=members-sub-tab#group-settings';
				}
			);

			$args = array(
				'type'     => 'list',
				'base'     => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'   => '?paged=%#%',
				'total'    => $total_pages,
				'current'  => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
				'end_size' => 1,
				'mid_size' => 5,
			);

			echo wp_kses_post( paginate_links( $args ) );
			?>
		</div>
		<?php
	}
}
