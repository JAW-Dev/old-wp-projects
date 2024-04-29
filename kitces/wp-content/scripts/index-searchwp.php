<?php

require( '../../wp/wp-load.php' );

// Manually process flagged index updates
if( function_exists( 'SWP' ) ) {
	SWP()->process_updates();
}