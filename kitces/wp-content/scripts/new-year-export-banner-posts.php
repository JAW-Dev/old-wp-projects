<?php

require '../../wp/wp-load.php';

// This script will get all blog posts that currently have any setting
// set related to ce banners and the downloads a CSV file with them.
// I build this because AIO Export wasn't getting some of them.

if (is_user_logged_in() && current_user_can('manage_options') ) {
    $show_banner_args = array(
    'posts_per_page' => -1,
    'post_type'      => 'post',
    'status'         => 'publish',
    'meta_query'     => array(
    array(
                'key'     => '_kitces_show_ce_banner',
                'value'   => '',
                'compare' => '!=',
    ),
    ),
    );

    $show_old_banner_args = array(
    'posts_per_page' => -1,
    'post_type'      => 'post',
    'status'         => 'publish',
    'meta_query'     => array(
    array(
                'key'     => '_kitces_show_ce_old_banner',
                'value'   => '',
                'compare' => '!=',
    ),
    ),
    );

    $show_ethics_banner_args = array(
    'posts_per_page' => -1,
    'post_type'      => 'post',
    'status'         => 'publish',
    'meta_query'     => array(
    array(
                'key'     => '_kitces_show_ce_ethics_banner',
                'value'   => '',
                'compare' => '!=',
    ),
    ),
    );

    $show_ce_banner_posts        = get_posts($show_banner_args);
    $show_ce_old_banner_posts    = get_posts($show_old_banner_args);
    $show_ce_ethics_banner_posts = get_posts($show_ethics_banner_args);

    $all_banner_posts_clean            = array();
    $show_ce_banner_posts_clean        = array();
    $show_ce_old_banner_posts_clean    = array();
    $show_ce_ethics_banner_posts_clean = array();

    if (! empty($show_ce_banner_posts) ) {
        foreach ( $show_ce_banner_posts as $post ) {
            $show_ce_banner_posts_clean[ $post->ID ]['ID']                            = $post->ID;
            $show_ce_banner_posts_clean[ $post->ID ]['post_date']                     = $post->post_date;
            $show_ce_banner_posts_clean[ $post->ID ]['post_title']                    = $post->post_title;
            $show_ce_banner_posts_clean[ $post->ID ]['_kitces_show_ce_banner']        = get_post_meta($post->ID, '_kitces_show_ce_banner', true);
            $show_ce_banner_posts_clean[ $post->ID ]['_kitces_show_ce_old_banner']    = get_post_meta($post->ID, '_kitces_show_ce_old_banner', true);
            $show_ce_banner_posts_clean[ $post->ID ]['_kitces_show_ce_ethics_banner'] = get_post_meta($post->ID, '_kitces_show_ce_ethics_banner', true);

            $all_banner_posts_clean[ $post->ID ]['ID']                            = $post->ID;
            $all_banner_posts_clean[ $post->ID ]['post_date']                     = $post->post_date;
            $all_banner_posts_clean[ $post->ID ]['post_title']                    = $post->post_title;
            $all_banner_posts_clean[ $post->ID ]['_kitces_show_ce_banner']        = get_post_meta($post->ID, '_kitces_show_ce_banner', true);
            $all_banner_posts_clean[ $post->ID ]['_kitces_show_ce_old_banner']    = get_post_meta($post->ID, '_kitces_show_ce_old_banner', true);
            $all_banner_posts_clean[ $post->ID ]['_kitces_show_ce_ethics_banner'] = get_post_meta($post->ID, '_kitces_show_ce_ethics_banner', true);
        }
    }

    if (! empty($show_ce_old_banner_posts) ) {
        foreach ( $show_ce_old_banner_posts as $post ) {
            $show_ce_old_banner_posts_clean[ $post->ID ]['ID']                            = $post->ID;
            $show_ce_old_banner_posts_clean[ $post->ID ]['post_date']                     = $post->post_date;
            $show_ce_old_banner_posts_clean[ $post->ID ]['post_title']                    = $post->post_title;
            $show_ce_old_banner_posts_clean[ $post->ID ]['_kitces_show_ce_banner']        = get_post_meta($post->ID, '_kitces_show_ce_banner', true);
            $show_ce_old_banner_posts_clean[ $post->ID ]['_kitces_show_ce_old_banner']    = get_post_meta($post->ID, '_kitces_show_ce_old_banner', true);
            $show_ce_old_banner_posts_clean[ $post->ID ]['_kitces_show_ce_ethics_banner'] = get_post_meta($post->ID, '_kitces_show_ce_ethics_banner', true);

            $all_banner_posts_clean[ $post->ID ]['ID']                            = $post->ID;
            $all_banner_posts_clean[ $post->ID ]['post_date']                     = $post->post_date;
            $all_banner_posts_clean[ $post->ID ]['post_title']                    = $post->post_title;
            $all_banner_posts_clean[ $post->ID ]['_kitces_show_ce_banner']        = get_post_meta($post->ID, '_kitces_show_ce_banner', true);
            $all_banner_posts_clean[ $post->ID ]['_kitces_show_ce_old_banner']    = get_post_meta($post->ID, '_kitces_show_ce_old_banner', true);
            $all_banner_posts_clean[ $post->ID ]['_kitces_show_ce_ethics_banner'] = get_post_meta($post->ID, '_kitces_show_ce_ethics_banner', true);
        }
    }

    if (! empty($show_ce_ethics_banner_posts) ) {
        foreach ( $show_ce_ethics_banner_posts as $post ) {
            $show_ce_ethics_banner_posts_clean[ $post->ID ]['ID']                            = $post->ID;
            $show_ce_ethics_banner_posts_clean[ $post->ID ]['post_date']                     = $post->post_date;
            $show_ce_ethics_banner_posts_clean[ $post->ID ]['post_title']                    = $post->post_title;
            $show_ce_ethics_banner_posts_clean[ $post->ID ]['_kitces_show_ce_banner']        = get_post_meta($post->ID, '_kitces_show_ce_banner', true);
            $show_ce_ethics_banner_posts_clean[ $post->ID ]['_kitces_show_ce_old_banner']    = get_post_meta($post->ID, '_kitces_show_ce_old_banner', true);
            $show_ce_ethics_banner_posts_clean[ $post->ID ]['_kitces_show_ce_ethics_banner'] = get_post_meta($post->ID, '_kitces_show_ce_ethics_banner', true);

            $all_banner_posts_clean[ $post->ID ]['ID']                            = $post->ID;
            $all_banner_posts_clean[ $post->ID ]['post_date']                     = $post->post_date;
            $all_banner_posts_clean[ $post->ID ]['post_title']                    = $post->post_title;
            $all_banner_posts_clean[ $post->ID ]['_kitces_show_ce_banner']        = get_post_meta($post->ID, '_kitces_show_ce_banner', true);
            $all_banner_posts_clean[ $post->ID ]['_kitces_show_ce_old_banner']    = get_post_meta($post->ID, '_kitces_show_ce_old_banner', true);
            $all_banner_posts_clean[ $post->ID ]['_kitces_show_ce_ethics_banner'] = get_post_meta($post->ID, '_kitces_show_ce_ethics_banner', true);
        }
    }

    ob_start();

    $current_time = current_time('timestamp');
    $filename     = 'ce-banner-posts-' . $current_time . '.csv';

    $header_row = array(
    'ID',
    'Post Date',
    'Post Title',
    '_kitces_show_ce_banner',
    '_kitces_show_ce_old_banner',
    '_kitces_show_ce_ethics_banner',
    );

    $fh = fopen('php://output', 'w');

    header('Content-type: text/csv');
    header("Content-Disposition: attachment; filename={$filename}");

    fputcsv($fh, $header_row);

    foreach ( $all_banner_posts_clean as $data_row ) {
        fputcsv($fh, $data_row, ',');
    }

    fclose($fh);

    ob_end_flush();

} else {
    echo 'There once was a chicken nugget.';
}
