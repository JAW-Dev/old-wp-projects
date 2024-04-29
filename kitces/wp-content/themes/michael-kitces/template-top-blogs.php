<?php
/**
* Template Name: Top Blogs List Template
*/


// full width layout
add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );


remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'top_blogs_loop' );

function top_blogs_loop() {
	global $wpdb;

	if ( have_posts() ) : while ( have_posts() ) : the_post();

			do_action( 'genesis_before_entry' );

			printf( '<article %s>', genesis_attr( 'entry' ) );

				do_action( 'genesis_entry_header' );

				do_action( 'genesis_before_entry_content' );
					printf( '<div %s>', genesis_attr( 'entry-content' ) );
						?>
						<?php the_content(); ?>
						<style type="text/css" media="screen">
						.top-advisor-blogs tr, .top-advisor-blogs td {
							text-align: center;
						}
						.top-advisor-blogs th {
							background: #ddd;
							padding: 3px 19px 6px;
							vertical-align: bottom;
							font-size: 16px;
							font-family: 'DaxCondensedBold';
						}

						.top-advisor-blogs td {
							font-size: 13px;
							padding: 10px;
							border-bottom: 1px solid #ddd
						}
						.top-advisor-blogs .even td {
							background: #efefef;
						}
						textarea {
							font-size: 12px;
							font-family: 'Courier New';
							height: 180px;
						}
						</style>

						<br />

						<?php
						global $wpdb;
						$query = "SELECT *
						FROM ".$wpdb->prefix."blog_import where bs_rank > 0
						ORDER BY bs_rank ASC
						LIMIT 0,50";
						$result = $wpdb->get_results($query, ARRAY_A);
						?>
						<table class="top-advisor-blogs" border="0" cellspacing="5" cellpadding="5">
							<tr>
								<th class="rank">Rank</th>
								<th class="blog-name">Blog Name</th>
								<th class="name">Advisor Name</th>
								<th class="firm">Firm Type</th>
								<th class="twitter">Twitter</th>
								<th class="page-auth">Moz Page Authority</th>
								<th class="moz-ext-link">Moz Rank</th>
								<th class="moz-total-link">Alexa Rank</th>
								<th class="moz-scrore">Total Score</th>
							</tr>
						<?php
						$c = 1;
						foreach($result as $row) {
	//								print_r($row);
						if ($c % 2 == 0) {
							$class = 'even';
						} else {
							$class = 'odd';
						}
						echo '
							<tr class="'.$class.'">
								<td class="rank">'.$row['bs_rank'].'</td>
								<td class="blog-name"><a href="'.$row['blog_url'].'">'.$row['blog_name'].'</a></td>
								<td class="name"><a href="'.$row['advisor_url'].'">'.$row['advisor_name'].'</a></td>
								<td class="firm">'.$row['type'].'</td>';
								if (strlen($row['twitter']) > 1 and $row['twitter'] != '(none)') {
									echo '<td class="twitter"><a href="http://twitter.com/'.$row['twitter'].'">'.$row['twitter'].'</a></td>';
								} else {
									echo '<td class="twitter"></td>';
								}
								echo '
								<td class="page-autd">'.number_format($row['moz_authority'], 2, '.', '').'</td>
								<td class="moz-ext-link">'.number_format($row['dom_moz_rank'], 2, '.', '').'</td>
								<td class="moz-total-link">'.$row['alexa_rank'].'</td>
								<td class="moz-scrore">'.number_format($row['comp_score'], 2, '.', '').'</td>
							</tr>';
						$c++;
						}
						?>
						</table>
						<hr />
						<h3><a name="badges"></a>Badges:</h3>
						<p>You can embed a badge for your site using the embed code below:</p>
						<h4>Large:</h4>
						<div class="row-fluid">
							<div class="span4">
								<div style="text-align: center; padding: 10px;">
									<a href="http://www.kitces.com/top-financial-advisor-blogs-and-bloggers/">
										<img src="/wp-content/uploads/Top_Advisor_badge_large.png" alt="Top Financial Advisor Blogs And Bloggers – Rankings From Nerd’s Eye View | Kitces.com" title="Top Financial Advisor Blogs And Bloggers – Rankings From Nerd’s Eye View | Kitces.com" />
									</a>
								</div>
							</div><!-- END 6 -->
							<div class="span5">
								<small>Copy the code below:</small>
								<textarea onclick="this.select()"><div style="text-align: center; padding: 10px;"><a href="http://www.kitces.com/top-financial-advisor-blogs-and-bloggers/?utm_source=brightscope_badge&utm_medium=badge&utm_campaign=embedded_badge_large"><img src="<?php bloginfo('url'); ?>/wp-content/uploads/Top_Advisor_badge_large.png" alt="Top Financial Advisor Blogs And Bloggers – Rankings From Nerd’s Eye View | Kitces.com" title="Top Financial Advisor Blogs And Bloggers – Rankings From Nerd’s Eye View | Kitces.com" /></a></div></textarea>
							</div><!-- END span5 -->
							<div class="clr"></div>
							<h3>Small:</h4>
							<div class="span4">
								<div style="text-align: center; padding: 10px;">
									<a href="http://www.kitces.com/top-financial-advisor-blogs-and-bloggers/">
										<img src="/wp-content/uploads/Top_Advisor_badge_small.png" alt="Top Financial Advisor Blogs And Bloggers – Rankings From Nerd’s Eye View | Kitces.com" title="Top Financial Advisor Blogs And Bloggers – Rankings From Nerd’s Eye View | Kitces.com" />
									</a>
								</div><!--end  -->
							</div><!-- END span6 -->
							<div class="span5">
								<small>Copy the code below:</small>
								<textarea onclick="this.select()"><div style="text-align: center; padding: 10px;"><a href="http://www.kitces.com/top-financial-advisor-blogs-and-bloggers/?utm_source=brightscope_badge&utm_medium=badge&utm_campaign=embedded_badge_small"><img src="<?php bloginfo('url'); ?>/wp-content/uploads/Top_Advisor_badge_small.png" alt="Top Financial Advisor Blogs And Bloggers – Rankings From Nerd’s Eye View | Kitces.com" title="TTop Financial Advisor Blogs And Bloggers – Rankings From Nerd’s Eye View | Kitces.com" /></a></div></textarea>

							</div><!--end span5 -->

						</div><!--END row-->
						<br />
						<h3><a name="scoring"></a>Scoring Methodology</h3>
						<p>
							Composite scores for blog ranking were determined using the following formula:
						</p>
						<p>
							<strong>60%</strong> * MozPageAuthority/10 + <br />
							<strong>30%</strong> * MozRank +<br />
							<strong>10%</strong> * (10 – AlexaRank/200,000)  (0 points if AlexaRank > 2,000,000)</p>
						<p>
							Results were then scaled to a top score of 100 for the highest composite score.
						</p>
					</div>
					<?php
				echo '</div>'; //* end .entry-content
				do_action( 'genesis_after_entry_content' );

				do_action( 'genesis_entry_footer' );

			echo '</article>';

			do_action( 'genesis_after_entry' );

		endwhile; //* end of one post
		do_action( 'genesis_after_endwhile' );

	else : //* if no posts exist
		do_action( 'genesis_loop_else' );
	endif; //* end loop

}

add_action( 'genesis_loop', 'cgd_jetpack_page_share', 1 );
function cgd_jetpack_page_share() {
    if ( function_exists( 'sharing_display' ) ) {
        echo '<div class="kitces-sharing is-inline">';
            sharing_display( '', true );
        echo '</div>';
    }

    if ( class_exists( 'Jetpack_Likes' ) && is_singular( 'post' ) ) {
        $custom_likes = new Jetpack_Likes;
        echo $custom_likes->post_likes( '' );
    }
}

add_action( 'genesis_loop', 'cgd_jetpack_page_float', 1 );
function cgd_jetpack_page_float() {
    if ( function_exists( 'sharing_display' ) ) {
        echo '<div class="kitces-sharing is-floating">';
            sharing_display( '', true );
        echo '</div>';
    }

    if ( class_exists( 'Jetpack_Likes' ) && is_singular( 'post' ) ) {
        $custom_likes = new Jetpack_Likes;
        echo $custom_likes->post_likes( '' );
    }
}


genesis();
