<?php

namespace ColdTrick\AdvancedComments;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		// extend css
		elgg_extend_view('css/elgg', 'css/advanced_comments.css');
		
		elgg_extend_view('page/elements/comments', 'advanced_comments/logged_out_notice', 400);
		
		elgg_extend_view('page/components/list', 'advanced_comments/header', 400);
		elgg_extend_view('page/components/list', 'advanced_comments/loader', 600);
		
		
		elgg_register_plugin_hook_handler('config', 'comments_latest_first', '\ColdTrick\AdvancedComments\Comments::getCommentsLatestFirst');
		elgg_register_plugin_hook_handler('config', 'comments_per_page', '\ColdTrick\AdvancedComments\Comments::getCommentsPerPage');
		elgg_register_plugin_hook_handler('view', 'page/elements/comments', '\ColdTrick\AdvancedComments\Views::untrackCommentsEntity');
		elgg_register_plugin_hook_handler('view_vars', 'page/components/list', '\ColdTrick\AdvancedComments\Views::checkCommentsListing');
		elgg_register_plugin_hook_handler('view_vars', 'page/elements/comments', '\ColdTrick\AdvancedComments\Views::trackCommentsEntity');
		
		
		elgg_register_ajax_view('advanced_comments/comments');
		
		// register plugin hooks
// 		elgg_register_plugin_hook_handler('route', 'comment', '\ColdTrick\AdvancedComments\Comments::route');
	}
}
