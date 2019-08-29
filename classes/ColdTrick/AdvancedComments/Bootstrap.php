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
		
		// extend views
		elgg_extend_view('page/components/list', 'advanced_comments/header', 400);
		elgg_extend_view('page/components/list', 'advanced_comments/loader', 600);
		elgg_extend_view('page/elements/comments', 'advanced_comments/logged_out_notice', 400);
		
		// register ajax views
		elgg_register_ajax_view('advanced_comments/comments');
		
		// register plugin hooks
		$this->registerHooks();
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\DefaultPluginBootstrap::ready()
	 */
	public function ready() {
		$plugin = $this->plugin();
		$hooks = $this->elgg()->hooks;
		
		if ((bool) $plugin->getSetting('allow_group_comments')) {
			$hooks->unregisterHandler('permissions_check:comment', 'object', '_elgg_groups_comment_permissions_override');
		}
	}
	
	/**
	 * Register plugin hook handlers
	 *
	 * @return void
	 */
	protected function registerHooks() {
		$hooks = $this->elgg()->hooks;
		
		$hooks->registerHandler('config', 'comments_latest_first', __NAMESPACE__ . '\Comments::getCommentsLatestFirst');
		$hooks->registerHandler('config', 'comments_per_page', __NAMESPACE__ . '\Comments::getCommentsPerPage');
		$hooks->registerHandler('view', 'page/elements/comments', __NAMESPACE__ . '\Views::untrackCommentsEntity');
		$hooks->registerHandler('view_vars', 'page/components/list', __NAMESPACE__ . '\Views::checkCommentsListing');
		$hooks->registerHandler('view_vars', 'page/elements/comments', __NAMESPACE__ . '\Views::trackCommentsEntity');
		
// 		$hooks->registerHandler('route', 'comment', __NAMESPACE__ . '\Comments::route');
	}
}
