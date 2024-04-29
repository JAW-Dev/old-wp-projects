<?php

/*
Template Name: Full Width
*/

// full width layout
add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

genesis();
