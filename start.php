<?php

// register default elgg events
elgg_register_event_handler('init', 'system', 'advanced_comments_init');

/**
 * Called during system init
 */
function advanced_comments_init() {
	
	// extend css
	elgg_extend_view('css/elgg', 'css/advanced_comments/site.css');
	
	elgg_register_ajax_view('advanced_comments/load');
}
