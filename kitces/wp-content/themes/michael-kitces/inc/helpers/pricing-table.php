<?php

function obj_do_pricing_table( $pricing_details = null ) {

	$r_title        = $pricing_details['mem_regular_price_title'];
	$r_price        = $pricing_details['mem_regular_price'];
	$r_duration     = $pricing_details['mem_regular_price_duration'];
	$r_subscription = $pricing_details['mem_regular_price_sub_link'];

	$p_title        = $pricing_details['mem_premiere_price_title'];
	$p_price        = $pricing_details['mem_premiere_price'];
	$p_duration     = $pricing_details['mem_premiere_price_duration'];
	$p_subscription = $pricing_details['mem_premiere_price_sub_link'];

	$i_title        = $pricing_details['mem_insider_price_title'];
	$i_price        = $pricing_details['mem_insider_price'];
	$i_duration     = $pricing_details['mem_insider_price_duration'];
	$i_subscription = $pricing_details['mem_insider_price_sub_link'];

	$features    = $pricing_details['mem_pricing_table_features'];
	$refund_text = $pricing_details['mem_refund_policy_text'];

	?>
	<div class="pricing-table mobile">
		<div class="small">
			<div class="pricing-header">
				<h2><?php echo $r_title; ?></h2>
			</div>
			<div class="pricing-price">
				<span class="price">
					<span class="price-sign">$</span><?php echo $r_price; ?>
				</span>
				<span class="price-duration">
					<?php echo $r_duration; ?>
				</span>
			</div>
			<div class="pricing-body">
				<ul class="feature-list">
					<?php foreach ( $features as $feature ) : ?>
						<?php if ( in_array( 'regular', $feature['feature_products'] ) ) : ?>
							<?php
								$new_label       = $feature['feature_display_new_label'];
								$new_label_class = $new_label ? 'new-label' : '';
							?>
							<li class="<?php echo $new_label_class; ?>">
								<?php if ( $new_label ) : ?>
									<span class="label">New</span>
								<?php endif; ?>
								<?php echo $feature['feature_title']; ?>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="pricing-footer">
				<!-- <a href="#signup-email-submit" class="button button-blue button-small email-inline">Subscribe</a> -->
				<a href="#signup-email-submit" class="button button-blue button-small email-inline">Subscribe</a>
			</div>
		</div>
		<div class="medium">
			<div class="pricing-pre-header most-popular">
				<h2>Most Popular</h2>
			</div>
			<div class="pricing-header">
				<h2><?php echo $p_title; ?></h2>
			</div>
			<div class="pricing-price">
				<span class="price">
					<span class="price-sign">$</span><?php echo $p_price; ?>
				</span>
				<span class="price-duration">
					<?php echo $p_duration; ?>
				</span>
			</div>
			<div class="pricing-body">
				<ul class="feature-list premiere">
					<?php foreach ( $features as $feature ) : ?>
						<?php if ( in_array( 'premiere', $feature['feature_products'] ) ) : ?>
							<?php
								$new_label       = $feature['feature_display_new_label'];
								$new_label_class = $new_label ? 'new-label' : '';
							?>
							<li class="<?php echo $new_label_class; ?>">
								<?php if ( $new_label ) : ?>
									<span class="label">New</span>
								<?php endif; ?>
								<?php echo $feature['feature_title']; ?>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="pricing-footer">
				<a href="<?php echo $p_subscription; ?>" class="button button-blue button-small">Subscribe</a>
				<!-- <a href="#signup-email-submit-most-popular" class="button button-blue button-small email-inline-most-popular">Subscribe</a> -->
			</div>
		</div>
		<div class="large">
			<div class="pricing-header">
				<h2><?php echo $i_title; ?></h2>
			</div>
			<div class="pricing-price">
				<span class="price">
					<span class="price-sign">$</span><?php echo $i_price; ?>
				</span>
				<span class="price-duration">
					<?php echo $i_duration; ?>
				</span>
			</div>
			<div class="pricing-body">
				<ul class="feature-list">
					<?php foreach ( $features as $feature ) : ?>
						<?php
							$new_label       = $feature['feature_display_new_label'];
							$new_label_class = $new_label ? 'new-label' : '';
						?>
						<?php if ( in_array( 'inside', $feature['feature_products'] ) ) : ?>
							<li class="<?php echo $new_label_class; ?>">
								<?php if ( $new_label ) : ?>
									<span class="label">New</span>
								<?php endif; ?>
								<?php echo $feature['feature_title']; ?>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="pricing-footer">
				<a href="#continue-div" class="button button-blue button-small fancy-inline">Subscribe</a>
			</div>
		</div>

		<?php if ( ! empty( $refund_text ) ) : ?>
			<div class="refund-policy">
				<em><?php echo $refund_text; ?></em>
			</div>
		<?php endif; ?>

	</div>

	<table class="pricing-table desktop">
		<thead>
			<tr>
				<th>
				</th>
				<th class="small sticky-table-header">
					<div class="pricing-header">
						<h2><?php echo $r_title; ?></h2>
					</div>
					<div class="pricing-price">
						<span class="price">
							<span class="price-sign">$</span><?php echo $r_price; ?>
						</span>
						<span class="price-duration">
							<?php echo $r_duration; ?>
						</span>
					</div>
				</th>
				<th class="medium featured sticky-table-header">
					<div class="pricing-pre-header most-popular">
						<h2>Most Popular</h2>
					</div>
					<div class="pricing-header">
						<h2><?php echo $p_title; ?></h2>
					</div>
					<div class="pricing-price">
						<span class="price">
							<span class="price-sign">$</span><?php echo $p_price; ?>
						</span>
						<span class="price-duration">
							<?php echo $p_duration; ?>
						</span>
					</div>
				</th>
				<th class="large sticky-table-header">
					<div class="pricing-header">
						<h2><?php echo $i_title; ?></h2>
					</div>
					<div class="pricing-price">
						<span class="price">
							<span class="price-sign">$</span><?php echo $i_price; ?>
						</span>
						<span class="price-duration">
							<?php echo $i_duration; ?>
						</span>
					</div>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( ! empty( $features ) ) : ?>
				<?php foreach ( $features as $feature ) : ?>
					<?php
						$new_label       = $feature['feature_display_new_label'];
						$new_label_class = $new_label ? 'new-label' : '';
					?>
					<tr>
						<td class="row-header <?php echo $new_label_class; ?>">
							<?php echo $feature['feature_title']; ?>
							<?php if ( $new_label ) : ?>
								<span class="label">New</span>
							<?php endif; ?>
						</td>
						<td>
							<?php if ( in_array( 'regular', $feature['feature_products'] ) ) : ?>
								<i class="far fa-check-circle"></i>
							<?php endif; ?>
						</td>
						<td class="premiere">
							<?php if ( in_array( 'premiere', $feature['feature_products'] ) ) : ?>
								<i class="far fa-check-circle"></i>
							<?php endif; ?>
						</td>
						<td>
							<?php if ( in_array( 'inside', $feature['feature_products'] ) ) : ?>
								<i class="far fa-check-circle"></i>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	<table class="pricing-table pricing-table-2 desktop">
		<tfoot>
		<tr>
			<td class="row-header">
			</td>
			<td>
				<a href="<?php echo $r_subscription; ?>" class="button button-blue button-small">Subscribe</a>
				<!-- <a href="#signup-email-submit" class="button button-blue button-small email-inline">Subscribe</a> -->
			</td>
			<td class="most-popular">
				<a href="<?php echo $p_subscription; ?>" class="button button-blue button-small">Subscribe</a>
				<!-- <a href="#signup-email-submit-most-popular" class="button button-blue button-small email-inline-most-popular">Subscribe</a> -->
			</td>
			<td>
				<a href="#continue-div" class="button button-blue button-small fancy-inline">Subscribe</a>
			</td>
		</tr>
		<?php if ( ! empty( $refund_text ) ) : ?>
			<tr>
				<td>
				</td>
				<td colspan="3" class="refund-policy">
					<em><?php echo $refund_text; ?></em>
				</td>
			</tr>
		<?php endif; ?>
		</tfoot>
	</table>

	<!-- <div id="continue-div" class="continue-div" style="display:none">
		<div id="combo-message" class="combo-message show">
			<p>
				Thanks for your interest in our special Inside Info Combo deal! Please note that this special offer is only for first-time subscribers to both Inside Information, and the Kitces.com Members Section.
			</p>
			<p>
				We appreciate your cooperation! Happy reading!
			</p>
			<div class="centered-buttons">
				<a href="#" id="signup-toggle-email" class="button button-blue button-small toggle-email">Subscribe</a>
				<span id="close-modaal" class="close-modaal button button-small">Cancel</span>
			</div>
		</div>
		<div id="combo-signup-email-submit" class="signup-email-submit hide"> -->
			<?php // email_signup_form( 'kitces-report-autopilot', 'fancy-inline', 'true' ); ?>
		<!-- </div>
	</div> -->

	<div id="continue-div" style="display:none">
		<p>
			Thanks for your interest in our special Inside Info Combo deal! Please note that this special offer is only for first-time subscribers to both Inside Information, and the Kitces.com Members Section.
		</p>
		<p>
			We appreciate your cooperation! Happy reading!
		</p>
		<div class="centered-buttons">
			<a href="<?php echo $i_subscription; ?>" class="button button-blue button-small">Continue</a>
			<span id="close-modaal" class="button button-small">Cancel</span>
		</div>
	</div>

	<!-- <div id="signup-email-submit" class="signup-email-submit hide" style="display:none;"> -->
		<?php // email_signup_form( 'kitces-basic-member', 'email-inline' ); ?>
	<!-- </div> -->

	<!-- <div id="signup-email-submit-most-popular" class="signup-email-submit hide" style="display:none;"> -->
		<?php // email_signup_form( 'kitces-report-autopilot', 'email-inline-most-popular' ); ?>
	<!-- </div> -->
	<?php
}

/**
 * The email check from markup
 */
function email_signup_form( $plan, $modal, $iic = 'false' ) {
	?>
	<form class="signup-email-submit__form" method="POST">
		<?php wp_nonce_field( 'chargebee_email_lookup', 'chargebee_email_lookup_field' ); ?>
		<p>Enter your email address.</p>
		<input
			type="email"
			class="signup-email-submit__email-input" name="email"
			placeholder="Email"
			data-member="<?php echo esc_url( home_url( 'login/?redirect_to=/member/?current=true' ) ); ?>"
			data-plan="<?php echo esc_attr( $plan ); ?>"
			data-modal="<?php echo esc_attr( $modal ); ?>"
			data-iic="<?php echo esc_attr( $iic ); ?>">
		<input type="submit" class="signup-email-submit__submit-button button button-small" value="submit">
	</form>
	<?php
}
