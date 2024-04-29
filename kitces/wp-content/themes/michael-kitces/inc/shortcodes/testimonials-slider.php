<?php

function testimonial_slider_func( $atts ) {
    $query_args = array(
        'post_type' => 'testimonials',
        'posts_per_page' => '-1',
    );

    $testimonials = new WP_Query( $query_args );
    ob_start();
    ?>
    <?php if ($testimonials->have_posts() ): ?>
        <div class="nerds-eye-view-testimonials">
            <?php while( $testimonials->have_posts() ): $testimonials->the_post(); ?>
                <?php
                $testimonials->the_post();
                $prefix = '_cgd_';
                $link = get_post_meta( get_the_ID(), $prefix . 'testimonial_posttype_link', true );
                $text = get_post_meta( get_the_ID(), $prefix . 'testmionial_posttype_text', true );
                $image = get_post_meta( get_the_ID(), $prefix . 'testimonial_posttype_sidebar_image', true );
                ?>
                <div class="nerds-eye-view-testimonial">
                    <?php if ( ! empty( $link ) ): ?>
                        <a href="<?php echo $link; ?>" target="_blank">
                            <div class="nerds-eye-view-testimonial-image">
                                <img src="<?php echo $image; ?>" alt="<?php the_title(); ?>">
                            </div>
                            <p class="nerds-eye-view-testimonial-text">&ldquo;<?php echo $text; ?>&rdquo;</p>
                        </a>
                    <?php else: ?>
                        <div class="nerds-eye-view-testimonial-image">
                            <img src="<?php echo $image; ?>" alt="<?php the_title(); ?>">
                        </div>
                        <p class="nerds-eye-view-testimonial-text">&ldquo;<?php echo $text; ?>&rdquo;</p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}

add_shortcode( 'testimonials', 'testimonial_slider_func' );
