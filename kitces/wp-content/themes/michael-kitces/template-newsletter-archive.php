<?php

/*
Template Name: Newsletter Archive
*/

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

// After the default content we'll display the news archive.
add_action( 'genesis_loop', 'objectiv_display_news_archive' );
function objectiv_display_news_archive() {
	$page_id     = get_the_ID();
	$news_blocks = get_field( 'newsletter_blocks', $page_id );

	if ( ! empty( $news_blocks ) ) { ?>

		<table class="objNewsTable">
		  <thead>
			<th>Issue #</th>
			<th>Topic</th>
			<th>
				CE Eligibility
				<br>
				<span class="onlyCEEligible eligible">Show CE-Eligible Only</span>
				<span class="onlyCEEligible all">Show All</span>
			</th>
		  </thead>
		  <?php foreach ( $news_blocks as $block ) : ?>

				<?php if ( ! empty( $block['title'] ) ) : ?>
				  <tr class="newsArchBlockTitle">
					  <td colspan="3"><?php echo $block['title']; ?></td>
				  </tr>
			  <?php endif; ?>

				<?php if ( ! empty( $block['newsletter_issues'] ) ) : ?>

					<?php foreach ( $block['newsletter_issues'] as $issue ) : ?>
						<?php
						$issue_number = $issue['issue_number'];
						$topic_name   = $issue['topic_name'];
						$topic_url    = $issue['pdf_to_link_to'];
						$ce_e         = $issue['ce_eligibility'];

						$eligible_class = 'ce-eligible';
						if ( ! $ce_e ) {
							$eligible_class = 'not-ce-eligible';
						}
						?>
					<tr class="<?php echo $eligible_class; ?>">
						<!-- Issue Number -->
						<td>
							<?php if ( ! empty( $issue_number ) ) : ?>
								<?php echo $issue_number; ?>
							<?php endif; ?>
						</td>

						<!-- Topic Name and Link -->
						<td>
							<?php if ( ! empty( $topic_name ) && ! empty( $topic_url ) ) : ?>
								<a href="<?php echo $topic_url; ?>"><?php echo $topic_name; ?></a>
							<?php endif; ?>
						</td>
						<td>
							<?php if ( $ce_e ) : ?>
								<div class="newsArchCreditAvailable">
									<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path d="M24.001 11.998c0-.9-.481-1.705-1.221-2.144.513-.69.649-1.617.308-2.448a2.486 2.486 0 00-1.949-1.514 2.497 2.497 0 00-.655-2.38 2.528 2.528 0 00-2.378-.654A2.496 2.496 0 0016.59.912a2.537 2.537 0 00-2.448.304 2.497 2.497 0 00-4.288 0A2.529 2.529 0 007.407.913a2.496 2.496 0 00-1.515 1.946 2.529 2.529 0 00-2.378.655 2.494 2.494 0 00-.654 2.38 2.498 2.498 0 00-1.642 3.961 2.498 2.498 0 000 4.289 2.503 2.503 0 00-.304 2.449 2.49 2.49 0 001.945 1.514 2.501 2.501 0 00.654 2.379 2.526 2.526 0 002.378.654 2.495 2.495 0 001.515 1.947c.816.339 1.77.204 2.448-.304a2.496 2.496 0 004.288 0 2.527 2.527 0 002.449.304 2.496 2.496 0 001.514-1.947 2.529 2.529 0 002.378-.654 2.497 2.497 0 00.654-2.378 2.492 2.492 0 001.642-3.963 2.495 2.495 0 001.222-2.147zm-6.66-3.132L9.84 15.867a.498.498 0 01-.695-.013l-2.498-2.5a.5.5 0 01.707-.707l2.158 2.158 7.147-6.67a.5.5 0 11.682.731z"/><g><path fill="none" d="M0 0h24v24H0z"/></g></svg>
								</div>
							<?php else : ?>
								<div class="newsArchNoCredit">
									<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path d="M24 11.998c0-.9-.481-1.705-1.22-2.144.513-.69.649-1.617.307-2.448a2.486 2.486 0 00-1.948-1.514 2.501 2.501 0 00-.655-2.38 2.523 2.523 0 00-2.378-.654A2.496 2.496 0 0016.591.912a2.531 2.531 0 00-2.448.304 2.497 2.497 0 00-4.288 0A2.536 2.536 0 007.406.913a2.498 2.498 0 00-1.515 1.946 2.537 2.537 0 00-2.378.655 2.498 2.498 0 00-.654 2.38A2.48 2.48 0 00.915 7.408c-.345.83-.21 1.757.302 2.447a2.497 2.497 0 00.001 4.288 2.503 2.503 0 00-.305 2.449 2.49 2.49 0 001.946 1.514c-.21.833.018 1.742.654 2.379.62.619 1.544.865 2.378.654a2.495 2.495 0 001.515 1.947c.816.338 1.77.204 2.448-.304a2.496 2.496 0 004.288 0 2.527 2.527 0 002.449.304 2.494 2.494 0 001.514-1.947c.833.21 1.76-.034 2.378-.654a2.497 2.497 0 00.654-2.378 2.494 2.494 0 001.643-3.963A2.494 2.494 0 0024 11.998zm-7.146 4.148a.5.5 0 01-.707.707L12 12.706l-4.147 4.147a.5.5 0 01-.707-.707l4.147-4.147-4.146-4.147a.5.5 0 01.707-.707L12 11.292l4.146-4.146a.5.5 0 01.707.707l-4.147 4.146 4.148 4.147z"/><g><path fill="none" d="M0 0h24v24H0z"/></g></svg>
								</div>
							<?php endif; ?>
						</td>
					</tr>

				<?php endforeach; ?>

			  <?php endif; ?>

		  <?php endforeach; ?>
		</table>


		<?php
	}
}


genesis();
