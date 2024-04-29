<?php

function do_member_nav_block( $do_buttons = true, $do_renewal = true, $all_alone = true, $wrap = true ) {
	global $CGD_CECredits;
	$user_cfpnumber = do_shortcode( '[kitces_members_contact fields=CFP_CE_NUMBER]' );
	$user_cfpnumber = trim( $user_cfpnumber );

	$user_imca_number = do_shortcode( '[kitces_members_contact fields=IMCA_CE_NUMBER]' );
	$user_imca_number = trim( $user_imca_number );

	$user_cpa_number = do_shortcode( '[kitces_members_contact fields=CPA_CE_NUMBER]' );
	$user_cpa_number = trim( $user_cpa_number );

	$user_ptin_number = do_shortcode( '[kitces_members_contact fields=PTIN_CE_NUMBER]' );
	$user_ptin_number = trim( $user_ptin_number );

	$user_american_college_id = do_shortcode( '[kitces_members_contact fields=AMERICAN_COLLEGE_ID]' );
	$user_american_college_id = trim( $user_american_college_id );

	$user_iar_number = do_shortcode( '[kitces_members_contact fields=IAR_CE_NUMBER]' );
	$user_iar_number = trim( $user_iar_number );


	$expiration_details      = mk_get_customer_subscription_expiration();
	$user_expiration         = mk_key_value( $expiration_details, 'user_expiration' );
	$expire_date_title_class = mk_key_value( $expiration_details, 'expire_date_title_class' );
	$expire_date_title       = mk_key_value( $expiration_details, 'expire_date_title' );

	$alone_class = '';
	if ( $all_alone ) {
		$alone_class = 'all-alone';
	}

	?>

	<div class="members-top-bar <?php echo $alone_class; ?>">
		<?php if ( $wrap ) : ?>
		<div class="wrap">
		<?php endif; ?>
			<div class="members-top-bar-inner">
				<div class="members-top-bar-number-badge-wrap">
					<span class="cfp-number number-badge">
						<span class="number-badge-label">
							CFP #:
						</span>
						<span class="number-badge-content">
							<?php if ( empty( $user_cfpnumber ) ) : ?>
								<a href="/member/my-account/">Update</a>
							<?php else : ?>
								<a href="/member/my-account/"><?php echo $user_cfpnumber; ?></a>
							<?php endif; ?>
						</span>
					</span>
					<span class="cfp-number number-badge">
						<span class="number-badge-label">
							ACC #:
						</span>
						<span class="number-badge-content">
							<?php if ( empty( $user_american_college_id ) ) : ?>
								<a href="/member/my-account/">Update</a>
							<?php else : ?>
								<a href="/member/my-account/"><?php echo $user_american_college_id; ?></a>
							<?php endif; ?>
						</span>
					</span>
					<span class="imca-number number-badge">
						<span class="number-badge-label">
							IWI #:
						</span>
						<span class="number-badge-content">
							<?php if ( empty( $user_imca_number ) ) : ?>
								<a href="/member/my-account/">Update</a>
							<?php else : ?>
								<a href="/member/my-account/"><?php echo $user_imca_number; ?></a>
							<?php endif; ?>
						</span>
					</span>
					<span class="cpa-number number-badge">
						<span class="number-badge-label">
							CPA #:
						</span>
						<span class="number-badge-content">
							<?php if ( empty( $user_cpa_number ) ) : ?>
								<a href="/member/my-account/">Update</a>
							<?php else : ?>
								<a href="/member/my-account/"><?php echo $user_cpa_number; ?></a>
							<?php endif; ?>
						</span>
					</span>
					<span class="ptin-number number-badge">
						<span class="number-badge-label">
							PTIN #:
						</span>
						<span class="number-badge-content">
							<?php if ( empty( $user_ptin_number ) ) : ?>
								<a href="/member/my-account/">Update</a>
							<?php else : ?>
								<a href="/member/my-account/"><?php echo $user_ptin_number; ?></a>
							<?php endif; ?>
						</span>
					</span>
					<span class="iar-number number-badge">
						<span class="number-badge-label">
							CRD #:
						</span>
						<span class="number-badge-content">
							<?php if ( empty( $user_iar_number ) ) : ?>
								<a href="/member/my-account/">Update</a>
							<?php else : ?>
								<a href="/member/my-account/"><?php echo $user_iar_number; ?></a>
							<?php endif; ?>
						</span>
					</span>
					<?php if ( ! empty( $user_expiration ) && $do_renewal ) : ?>
						<span class="renewal-date-badge number-badge">
							<span class="number-badge-label <?php echo $expire_date_title_class; ?>"><?php echo $expire_date_title; ?></span><span class="number-badge-content <?php echo $expire_date_title_class; ?>"><?php echo $user_expiration; ?></span>
						</span>
					<?php endif ?>
				</div>
				<?php if ( $do_buttons ) : ?>
					<div class="members-top-bar-actions">
						<a href="/member" class="top-bar-button">Member Home</a>
						<a class="top-bar-button" href="<?php echo wp_logout_url( get_home_url() ); ?>">Log Out</a>
					</div>
				<?php endif; ?>
			</div>
		<?php if ( $wrap ) : ?>
		</div>
		<?php endif; ?>
	</div>
	<?php
}
