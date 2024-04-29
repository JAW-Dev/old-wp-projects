<?php
use FpAccountSettings\Includes\Classes\Conditionals;
?>
<section class="price-blocks <?php echo $custom_classes; ?>" id="pricing">
	<div class="wrap">
		<div class="price-blocks-inner">
			<?php foreach ( $price_blocks as $price_block ) : ?>
				<div class="price-block <?php echo $price_block['stand_out_text'] ? 'stand-out' : ''; ?>">

				<?php if ( $price_block['stand_out_text'] ) : ?>
					<div class="stand-out-text"><?php echo $price_block['stand_out_text']; ?></div>
				<?php endif; ?>

					<div class="name"><h4><?php echo $price_block['name']; ?></h4></div>

					<div class="price-container">
						<?php if ( $price_block['price'] ) : ?>
							<span class="price"><?php echo $price_block['price']; ?></span>
						<?php else : ?>
							<span class="contact-us-message">Contact Us</span>
						<?php endif; ?>
					</div>

					<div class="description"><?php echo $price_block['description']; ?></div>

					<?php
					$button = objectiv_link_button( apply_filters( 'price_block_button', $price_block['button'], intval( $price_block['membership_level_id'] ) ), 'button red-button' );
					$button = Conditionals::is_deluxe_or_premier_group_member() ? false : $button;
					echo $button ? $button : 'Not Eligible';
					?>

				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( ! empty( $after_blocks_text ) ) : ?>
			<div class="after-blocks-text basemt2"><?php echo $after_blocks_text; ?></div>
		<?php endif; ?>
	</div>
</section>
