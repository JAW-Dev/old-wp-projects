<?php

add_action(
	'wp_enqueue_scripts',
	function() {
		wp_register_script( 'fp-interactive-checklists', plugins_url( '/', __DIR__ ) . 'js/main.js', array( 'jquery' ), 0, true );
		wp_enqueue_script( 'fp-interactive-checklists' );

	}
);
