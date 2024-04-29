<?php

/*
Template Name: Contact Template - 2016
*/


add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

	$classes[] = 'contact-2016';
	return $classes;

}

add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_after_header', 'cgd_contact_content' );
function cgd_contact_content() { ?>
	<?php
    $post_id = get_the_ID();
    $post = get_post($post_id);
	$content = $post->post_content;
	?>
    <section class="content-section">
        <div class="wrap">
            <?php echo $content ?>
        </div>
    </section>
    <?php
}

add_action( 'genesis_after_header', 'cgd_itty_bitty' );
function cgd_itty_bitty() {
	$prefix = '_cgd_';
	$image = get_post_meta( get_the_ID(), $prefix . 'tweet_me_img', true );
	if ( ! empty( $image ) ) {
		$bg_img = $image;
	} else {
		$bg_img = '';
	}
	?>
    <section class="content-section tweet-at-me" style="background: url('<?php echo $bg_img ?>');">
        <div class="wrap">
            <div id="tweet-box" class="tweet-box">
                <h3>Have A Quick Question?</h3>
                <h2>Ask Me Via Twitter!</h2>
                <form>
                    <div class="form-left">
						<label for="tweet">@MichaelKitces</label>
                        <input type="text" id="tweet" name="tweet"/>
						<div class="tweet-count-div">
							<span class="tweet-count"></span>
						</div>
                    </div>
                    <div class="form-right">
						<div class="button-wrap">
                        	<a id="tweet-button" class="button" href="https://twitter.com/intent/tweet?text=@MichaelKitces" rel="nofollow" target="_blank">Tweet My Question!</a>
						</div>
                    </div>
                </form>
            </div>
            <div id="follow-me">
                <h3>And Don't Forget To Follow Me:</h3>
                <ul>
				<li><a target="_blank" class="twitter" href="http://twitter.com/MichaelKitces"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/lib/images/icon-twitter.png"></a></li>
				<li><a target="_blank" class="facebook" href="http://www.facebook.com/Kitces"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/lib/images/icon-facebook.png"></a></li>
                <li><a target="_blank" class="linkedin" href="http://www.linkedin.com/in/michaelkitces"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/lib/images/icon-linkedin.png"></a></li>
                <li><a target="_blank" class="youtube" href="http://www.youtube.com/user/MichaelKitces?feature=watch"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/lib/images/icon-youtube.png"></a></li>
                </ul>
            </div>
        </div>
    </section>
    <?php
}

add_action( 'genesis_after_header', 'cgd_still_email_me' );
function cgd_still_email_me() {

	$prefix = '_cgd_';
	$left_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'left_of_form_content', true ) );
	$gf_content = get_post_meta( get_the_ID(), $prefix . 'the_gravity_form_content', true );

	?>
    <section class="content-section email-me">
        <div class="wrap">
            <div class="left-side">
				<?php echo $left_content; ?>
            </div>
            <div id="right-side">
				<?php echo do_shortcode( $gf_content ); ?>
            </div>
        </div>
    </section>
    <?php
}

add_action( 'genesis_after_header', 'cgd_other_contact_types' );
function cgd_other_contact_types() {

	$prefix = '_cgd_';
	$first_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'other_contact_first', true ) );
	$second_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'other_contact_second', true ) );
	$third_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'other_contact_third', true ) );

	?>

    <section class="content-section other-contact-methods">
        <div class="wrap">
			<h2 class="other-contact-title">Other Ways To Reach Us...</h2>
            <div class="other-contact">
				<?php echo $first_content; ?>
            </div>
			<div class="other-contact">
				<?php echo $second_content; ?>
			</div>
			<div class="other-contact third">
				<?php echo $third_content; ?>
			</div>
        </div>
    </section>
    <?php
}

genesis();
