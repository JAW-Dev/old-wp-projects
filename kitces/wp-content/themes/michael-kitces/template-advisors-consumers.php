<?php

/*
Template Name: Advisors & Consumers Page
*/

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

	$classes[] = 'advisors-consumers';
	return $classes;

}

remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_after_header', 'cgd_advisors_desc' );
function cgd_advisors_desc() { ?>
	<?php
	$prefix    = '_cgd_';
	$desc      = wpautop( get_post_meta( get_the_ID(), $prefix . 'advisor_desc', true ) );
	$nav_icons = get_post_meta( get_the_ID(), $prefix . 'advisors_icon_group', true );
	?>
    <section class="content-section advisors-header" <?php if ( empty( $nav_icons ) ): echo 'style="background: #ffffff;"'; endif; ?> >
		<div class="wrap">
			<?php echo $desc; ?>
		</div>
	</section>
<?php
}

add_action( 'genesis_after_header', 'cgd_advisors_nav_icons' );
function cgd_advisors_nav_icons() {
?>
	<?php
	$prefix    = '_cgd_';
	$nav_icons = get_post_meta( get_the_ID(), $prefix . 'advisors_icon_group', true );
	?>
	<?php if ( ! empty( $nav_icons ) ) : ?>
		<section class="content-section advisors-nav">
			<div class="wrap">
				<?php foreach ( $nav_icons as $icon ) : ?>
					<div class="nav-block">
						<a href="<?php echo $icon['link']; ?>">
							<span class="fa-stack fa-lg">
								<i class="fas fa-circle fa-stack-2x"></i>
								<i class="fas <?php echo $icon['icon']; ?> fa-stack-1x fa-inverse"></i>
							</span>
						</a>
						<p class="nav-block-title"><?php echo $icon['title']; ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>
<?php
}

add_action( 'genesis_after_header', 'cgd_advisors_outsourcing_solutions' );
function cgd_advisors_outsourcing_solutions() {
?>
	<?php
	$prefix         = '_cgd_';
	$company_groups = get_post_meta( get_the_ID(), $prefix . 'companies_group', true );
	$companies      = get_post_meta( get_the_ID(), $prefix . 'company_group', true );
	$count          = 1;
	?>
	<section class="content-section outsourcing-solutions">
		<div class="wrap">
			<?php if ( ! empty( $company_groups ) ) : ?>
				<?php foreach ( $company_groups as $company_group ) : ?>
					<div id="<?php echo $company_group['company_group_id']; ?>" class="company-group">
						<h2 class="company-group-title"><?php echo $company_group['company_group_name']; ?></h2>
						<div class="companies-grid-wrap one2grid">
							<?php foreach ( $companies as $company ) : ?>
								<?php if ( $company_group['company_group_id'] == $company['company_belongs_to_group_id'] ) : ?>
									<div class="company">
										<div class="company-logo">
											<?php $alt = get_post_meta( $company['company_logo_id'], '_wp_attachment_image_alt', true ); ?>
											<img src="<?php echo $company['company_logo']; ?>" alt="<?php echo $alt; ?>">
										</div>
										<h3 class="company-title"><?php echo $company['company_name']; ?></h3>
										<p class="company-desc"><?php echo $company['company_desc']; ?></p>
										<a href="<?php echo $company['company_link']; ?>" class="button">Visit <?php echo $company['company_name']; ?></a>
									</div>
								<?php endif; ?>
								<?php $count++; ?>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</section>
<?php
}

genesis();
