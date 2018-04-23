<?php

namespace ColdTrick\AdvancedComments;

class Views {
	
	/**
	 * Controls existence of special list vars
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'page/components/list'
	 *
	 * @return []
	 */
	public static function checkCommentsListing(\Elgg\Hook $hook) {
		
		$result = $hook->getValue();
		
		if (elgg_extract('list_class', $result) !== 'comments-list') {
			return;
		}
		
		if (count((array) elgg_extract('items', $result, [])) < 2) {
			return;
		}
		
		$entity = elgg_get_session()->get('advanced_comments_entity');
		if (!$entity) {
			return;
		}
		
		$result['entity'] = $entity;
	
		if (elgg_get_plugin_setting('user_preference', 'advanced_comments') == 'yes') {
			$result['advanced_comments_show_list_header'] = true;
		}
	
		if (elgg_extract('pagination', $result) && elgg_get_plugin_setting('default_auto_load', 'advanced_comments') == 'yes') {
			$result['advanced_comments_show_autoload'] = true;
			$result['pagination'] = false;
		}
		
		return $result;
	}
	
	/**
	 * Tracks the entity the comments are listed for
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'page/elements/comments'
	 *
	 * @return []
	 */
	public static function trackCommentsEntity(\Elgg\Hook $hook) {
		
		$entity = elgg_extract('entity', $hook->getValue());
		if (!$entity instanceof \ElggEntity) {
			return;
		}

		elgg_get_session()->set('advanced_comments_entity', $entity);
	}
	
	/**
	 * Untracks the entity the comments are listed for
	 *
	 * @param \Elgg\Hook $hook 'view', 'page/elements/comments'
	 *
	 * @return []
	 */
	public static function untrackCommentsEntity(\Elgg\Hook $hook) {
		elgg_get_session()->remove('advanced_comments_entity');
	}
}
