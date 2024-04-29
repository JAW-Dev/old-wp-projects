<?php

require( '../../wp/wp-load.php' );

// Regenerate advanced-cache.php for WP Rocket
rocket_generate_advanced_cache_file();

// Regenerate .htaccess WP Rocket rules
flush_rocket_htaccess();