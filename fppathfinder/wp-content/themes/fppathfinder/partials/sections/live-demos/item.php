<section id="live-demo-block" class="live-demos simple-cta-section page-flexible-section <?php echo $padding_classes; ?> <?php echo $custom_classes ?>">
	<div class="wrap">
		<div class="live-demos__inner">
			<header class="live-demos__header">
				<h3 class="live-demos__header-title"><?php echo esc_html( $header ); ?></h3>
				<div class="live-demos__header-blurb"><?php echo wp_kses_post( $header_blurb ); ?></div>
			</header>

			<div class="live-demos__wrap">
				<div class="live-demos__left">
					<div class="live-demos__learn">
						<h3><?php echo esc_html( $learn_title ); ?></h3>
						<?php
						foreach ( $learn_list as $item ) {
							?>
							<div class="live-demos__learn-item"><?php echo esc_html( $item['item'] ); ?></div>
							<?php
						}
						?>
					</div>
				</div>

				<div class="live-demos__right">
					<div class="live-demos__team-members">
						<h3><?php echo esc_html( $team_header ); ?></h3>
						<div class="live-demos__team-members-wrap">
							<?php
							foreach ( $team_images as $team_image ) {
								$src = wp_get_attachment_image_src( $team_image['image'] );
								?>
								<div class="live-demos__member">
									<img src="<?php echo esc_attr( $src[0] ); ?>">
									<div class="name"><?php echo esc_html( $team_image['name'] ); ?></div>
									<div class="blurb"><?php echo wp_kses_post( $team_image['blurb'] ); ?></div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>


			<div class="live-demos__dates">
				<h3><?php echo esc_html( $dates_header ); ?></h3>
				<h4><?php echo esc_html( $dates_subheader ); ?></h4>
				<?php
				foreach ( $dates as $date ) {
					$time = $date['date'] . ' @ ' . str_replace( [ 'am', 'pm' ], [ ' a.m', ' p.m' ], $date['time'] );
					?>
					<div class="live-demos__dates-inner">
						<span class="live-demos__dates-date"><?php echo esc_html( $time ); ?></span>
						<a href="<?php echo esc_url( $date['url'] ); ?>" style="color: white;" target="_blank"></a>
					</div>
					<?php
				}
				?>
			</div>
			<p><?php echo esc_html( $bottom_blurb ); ?></p>
		</div>
	</div>
</section>
