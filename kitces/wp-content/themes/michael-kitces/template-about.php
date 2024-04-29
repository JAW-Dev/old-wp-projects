<?php

/*
Template Name: About Page
*/

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

	$classes[] = 'about';
	return $classes;

}

remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_after_header', 'cgd_about_content' );
function cgd_about_content() { ?>
	<?php
	$prefix = '_cgd_';
	$video = get_post_meta( get_the_ID(), $prefix . 'about_video', true );
	$video_desc = get_post_meta( get_the_ID(), $prefix . 'about_video_desc', true );
	$education_title = get_post_meta( get_the_ID(), $prefix . 'about_education_title', true );
	$education_left_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'about_education_left_content', true ) );
	$education_right_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'about_education_right_content', true ) );
	$degrees_title = get_post_meta( get_the_ID(), $prefix . 'about_degrees_title', true );
	$left_degrees_list = get_post_meta( get_the_ID(), $prefix . 'about_degrees_left_list', true );
	$right_degrees_list = get_post_meta( get_the_ID(), $prefix . 'about_degrees_right_list', true );
	$audience_title = get_post_meta( get_the_ID(), $prefix . 'audience_title', true );
	$audience_left_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'audience_left_content', true ) );
	$audience_right_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'audience_right_content', true ) );
	$numbers_title = get_post_meta( get_the_ID(), $prefix . 'numbers_title', true );
	$number_degrees = get_post_meta( get_the_ID(), $prefix . 'numbers_degrees', true );
	$number_years = get_post_meta( get_the_ID(), $prefix . 'years_experience', true );
	$number_speaking = get_post_meta( get_the_ID(), $prefix . 'speaking_engagements', true );
	$number_members = get_post_meta( get_the_ID(), $prefix . 'numbers_members', true );
	$number_visitors = get_post_meta( get_the_ID(), $prefix . 'numbers_visitors', true );
	$why_title = get_post_meta( get_the_ID(), $prefix . 'why_title', true );
	$why_quote = get_post_meta( get_the_ID(), $prefix . 'why_quote', true );
	$why_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'why_content', true ) );
	$why_buttons = get_post_meta( get_the_ID(), $prefix . 'why_buttons_group', true );
	$ideas_title = get_post_meta( get_the_ID(), $prefix . 'ideas_title', true );
	$ideas_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'ideas_content', true ) );
	$ideas_buttons = get_post_meta( get_the_ID(), $prefix . 'idea_buttons_group', true );
	$background_title = get_post_meta( get_the_ID(), $prefix . 'background_title', true );
	$background_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'background_content', true ) );
	$sleep_title = get_post_meta( get_the_ID(), $prefix . 'sleep_title', true );
	$sleep_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'sleep_content', true ) );
	$sleep_buttons = get_post_meta( get_the_ID(), $prefix . 'sleep_buttons_group', true );
	?>
    <section class="content-section video">
		<div class="wrap">
			<div class="about-video">
				<?php echo wp_oembed_get( $video ); ?>
				<p class="about-video-desc"><?php echo $video_desc; ?></p>
			</div>
		</div>
	</section>
	<section class="content-section education">
		<div class="wrap">
			<h2 class="section-content-title"><?php echo $education_title; ?></h2>
			<div class="one-half first">
				<?php echo do_shortcode($education_left_content); ?>
			</div>
			<div class="one-half">
				<?php echo do_shortcode($education_right_content); ?>
			</div>
		</div>
	</section>
	<section class="content-section degrees">
		<div class="wrap">
			<i class="fas fa-graduation-cap"></i>
			<h2 class="section-content-title"><?php echo $degrees_title; ?></h2>
			<div class="degrees-list">
				<div class="one-half first">
					<ul>
						<?php foreach( $left_degrees_list as $degree ): ?>
							<li><?php echo $degree['title']; ?>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="one-half">
					<ul>
						<?php foreach( $right_degrees_list as $degree ): ?>
							<li><?php echo $degree['title']; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<section class="content-section audience">
		<div class="wrap">
			<h2 class="section-content-title"><?php echo $audience_title; ?></h2>
			<div class="one-half first">
				<?php echo do_shortcode($audience_left_content); ?>
			</div>
			<div class="one-half">
				<?php echo do_shortcode($audience_right_content); ?>
			</div>
		</div>
	</section>
	<section class="content-section numbers">
		<div class="wrap">
			<h2 class="section-content-title"><?php echo $numbers_title; ?></h2>
			<div class="number-stat one-third first">
				<span class="number-stat-number"><?php echo $number_degrees; ?></span>
				<p class="number-stat-desc">Degrees And Designations</p>
			</div>
			<div class="number-stat one-third">
				<span class="number-stat-number"><?php echo $number_years; ?></span>
				<p class="number-stat-desc">Years of Experience</p>
			</div>
			<div class="number-stat one-third">
				<span class="number-stat-number"><?php echo $number_speaking; ?></span>
				<p class="number-stat-desc">Annual Speaking Engagements</p>
			</div>
			<div class="number-stat one-half first">
				<span class="number-stat-number"><?php echo $number_members; ?></span>
				<p class="number-stat-desc">Members Section Advisors</p>
			</div>
			<div class="number-stat one-half">
				<span class="number-stat-number"><?php echo $number_visitors; ?></span>
				<p class="number-stat-desc">Nerd's Eye View Unique Visitors</p>
			</div>
		</div>
	</section>
	<section class="content-section why">
		<div class="wrap">
			<h2 class="section-content-title"><?php echo $why_title; ?></h2>
			<div class="why-quote">
				<p><?php echo $why_quote; ?></p>
			</div>
			<div class="why-content">
				<?php echo $why_content; ?>
			</div>
			<div class="why-buttons">
				<?php foreach( $why_buttons as $button ): ?>
					<a href="<?php echo $button['link']; ?>" class="button button-blue"><?php echo $button['text']; ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<section class="content-section ideas">
		<div class="wrap">
			<h2 class="section-content-title"><?php echo $ideas_title; ?></h2>
			<div class="two-thirds first">
				<?php echo $ideas_content; ?>
			</div>
			<div class="one-third">
				<?php foreach( $ideas_buttons as $button ): ?>
					<a href="<?php echo $button['link']; ?>" class="button button-blue"><?php echo $button['text']; ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<section class="content-section background">
		<div class="wrap">
			<h2 class="section-content-title"><?php echo $background_title; ?></h2>
			<div class="background-content three-fourths first">
				<?php echo $background_content; ?>
			</div>
			<div class="background-image one-fourth">
				<?php
				$prefix = '_cgd_';
				$small_background_image = wp_get_attachment_image( get_post_meta( get_the_ID(), $prefix . 'background_image_id', true ), 'medium' );
				$big_background_image = get_post_meta( get_the_ID(), $prefix . 'background_image', true );
				?>
				<a class="fancybox" rel="group" href="<?php echo $big_background_image; ?>"><?php echo $small_background_image; ?></a>
			</div>
		</div>
	</section>
	<section class="content-section sleep">
		<div class="wrap">
			<h2 class="section-content-title"><?php echo $sleep_title; ?></h2>
			<div class="sleep-content three-fourths first">
				<?php echo $sleep_content; ?>
			</div>
			<div class="sleep-image one-fourth">
				<?php
				$prefix = '_cgd_';
				$small_sleep_image = wp_get_attachment_image( get_post_meta( get_the_ID(), $prefix . 'sleep_image_id', true ), 'medium' );
				$big_sleep_image = get_post_meta( get_the_ID(), $prefix . 'sleep_image', true );
				?>
				<a class="fancybox" rel="group" href="<?php echo $big_sleep_image; ?>"><?php echo $small_sleep_image; ?></a>
			</div>
			<div class="sleep-buttons">
				<?php foreach( $sleep_buttons as $button ): ?>
					<a href="<?php echo $button['link']; ?>" class="button"><?php echo $button['text']; ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php }

genesis();
