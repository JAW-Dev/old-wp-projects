<?php
$prefix = '_cgd_';
$r_title = get_post_meta( get_the_ID(), $prefix . 'pricing_regular_title', true );
$r_price = get_post_meta( get_the_ID(), $prefix . 'pricing_regular_price', true );
$r_duration = get_post_meta( get_the_ID(), $prefix . 'pricing_regular_duration', true );
$r_subscription = get_post_meta( get_the_ID(), $prefix . 'pricing_regular_subscription_link', true );

$p_title = get_post_meta( get_the_ID(), $prefix . 'pricing_premiere_title', true );
$p_price = get_post_meta( get_the_ID(), $prefix . 'pricing_premiere_price', true );
$p_duration = get_post_meta( get_the_ID(), $prefix . 'pricing_premiere_duration', true );
$p_subscription = get_post_meta( get_the_ID(), $prefix . 'pricing_premiere_subscription_link', true );

$i_title = get_post_meta( get_the_ID(), $prefix . 'pricing_inside_title', true );
$i_price = get_post_meta( get_the_ID(), $prefix . 'pricing_inside_price', true );
$i_duration = get_post_meta( get_the_ID(), $prefix . 'pricing_inside_duration', true );
$i_subscription = get_post_meta( get_the_ID(), $prefix . 'pricing_inside_subscription_link', true );

$features = get_post_meta( get_the_ID(), $prefix . 'product_features_group', true );
$refund_text = get_post_meta( get_the_ID(), $prefix . 'members_refund_policy_text', true );

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
				<?php foreach( $features as $feature ): ?>
					<?php if ( in_array( 'regular', $feature['products'] ) ): ?>
						<li><?php echo $feature['title'] ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="pricing-footer">
			<a href="<?php echo $r_subscription; ?>" class="button button-blue button-small">Subscribe</a>
		</div>
	</div>
	<div class="medium">
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
			<ul class="feature-list">
				<?php foreach( $features as $feature ): ?>
					<?php if ( in_array( 'premiere', $feature['products'] ) ): ?>
						<li><?php echo $feature['title'] ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="pricing-footer">
			<a href="<?php echo $p_subscription; ?>" class="button button-blue button-small">Subscribe</a>
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
				<?php foreach( $features as $feature ): ?>
					<?php if ( in_array( 'inside', $feature['products'] ) ): ?>
						<li><?php echo $feature['title'] ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="pricing-footer">
			<a href="#continue-div" id="fancy-inline" class="button button-blue button-small fancy-inline">Subscribe</a>
		</div>
	</div>

	<?php if ( ! empty( $refund_text ) ) : ?>
		<div class="refund-policy">
			<em><?php echo $refund_text ?></em>
		</div>
	<?php endif; ?>

</div>

<table class="pricing-table desktop">
	<thead>
		<tr>
			<th>
			</th>
			<th class="small">
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
			<th class="medium featured">
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
			<th class="large">
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
		<?php foreach( $features as $feature ): ?>
			<tr>
				<td class="row-header">
					<?php echo $feature['title']; ?>
				</td>
				<td>
					<?php if ( in_array( 'regular', $feature['products'] ) ): ?>
						<i class="fas fa-check-circle-o"></i>
					<?php endif; ?>
				</td>
				<td>
					<?php if ( in_array( 'premiere', $feature['products'] ) ): ?>
						<i class="fas fa-check-circle-o"></i>
					<?php endif; ?>
				</td>
				<td>
					<?php if ( in_array( 'inside', $feature['products'] ) ): ?>
						<i class="fas fa-check-circle-o"></i>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td>
			</td>
			<td>
				<a href="<?php echo $r_subscription; ?>" class="button button-blue button-small">Subscribe</a>
			</td>
			<td>
				<a href="<?php echo $p_subscription; ?>" class="button button-blue button-small">Subscribe</a>
			</td>
			<td>
				<a href="#continue-div" id="fancy-inline" class="button button-blue button-small fancy-inline">Subscribe</a>
			</td>
		</tr>
		<?php if ( ! empty( $refund_text ) ) : ?>
			<tr>
				<td>
				</td>
				<td colspan="3" class="refund-policy">
					<em><?php echo $refund_text ?></em>
				</td>
			</tr>
		<?php endif; ?>
	</tfoot>
</table>

<div id="continue-div" style="display:none">
	<p>
		Thanks for your interest in our special Inside Info Combo deal! Please note that this special offer is only for first-time subscribers to both Inside Information, and the Kitces.com Members Section.
	</p>
	<p>
		We appreciate your cooperation! Happy reading!
	</p>
	<div class="centered-buttons">
		<a href="<?php echo $i_subscription; ?>" class="button button-blue button-small">Continue</a>
		<span onClick="parent.jQuery.fancybox.close();" class="button button-small">Cancel</span>
	</div>
</div>
