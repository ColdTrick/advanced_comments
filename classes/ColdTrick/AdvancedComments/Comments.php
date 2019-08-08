<?php

namespace ColdTrick\AdvancedComments;

class Comments {
	/**
	 * Gets the comments per page
	 *
	 * @param \Elgg\Hook $hook 'config', 'comments_per_page'
	 *
	 * @retrun void|false
	 */
	public static function getCommentsPerPage(\Elgg\Hook $hook) {
		
		$setting = elgg_get_plugin_setting('default_limit', 'advanced_comments', $hook->getValue());
		
		if (elgg_get_plugin_setting('user_preference', 'advanced_comments') == 'yes') {
		
		}
		
		if (($setting < 5) || ($setting > 100)) {
			return;
		}
		
		return $setting;
	}

	/**
	 * Are comments ordered latest first?
	 *
	 * @param \Elgg\Hook $hook 'config', 'comments_latest_first'
	 *
	 * @retrun void|false
	 */
	public static function getCommentsLatestFirst(\Elgg\Hook $hook) {
		
		$latest_first = (bool) (elgg_get_plugin_setting('default_order', 'advanced_comments') === 'desc');
		
		return $latest_first;
	}
	
	/**
	 * Redo the comment forwarding
	 *
	 * @param \Elgg\Hook $hook 'route', 'comment'
	 *
	 * @todo check this, is this needed?
	 * @return void
	 */
	public static function route(\Elgg\Hook $hook) {
		
		$return = $hook->getValue();
		$segments = elgg_extract('segments', $return);
		switch (elgg_extract(0, $segments)) {
			case 'view':
				
				self::commentRedirect(elgg_extract(1, $segments), elgg_extract(2, $segments));
				break;
		}
	}
	
	/**
	 * Redirect to the comment in context of the containing page
	 *
	 * @param int $comment_guid  GUID of the comment
	 * @param int $fallback_guid GUID of the containing entity
	 *
	 * @return void
	 */
	protected static function commentRedirect($comment_guid, $container_guid) {
		
		$fail = function () {
			register_error(elgg_echo('generic_comment:notfound'));
			forward(REFERER);
		};
	
		$comment = get_entity($comment_guid);
		if (!$comment) {
			// try fallback if given
			$fallback = get_entity($container_guid);
			if (!$fallback) {
				$fail();
			}
	
			register_error(elgg_echo('generic_comment:notfound_fallback'));
			forward($fallback->getURL());
		}
	
		if (!($comment instanceof \ElggComment)) {
			$fail();
		}
	
		$container = $comment->getContainerEntity();
		if (!$container) {
			$fail();
		}
		
		$comment_settings = advanced_comments_get_comment_settings($container);
		
		$reverse_order_by = false;
		$wheres = ['e.guid > ' . (int) $comment->guid];
		if (elgg_extract('order', $comment_settings) === 'asc') {
			$reverse_order_by = true;
			$wheres = ['e.guid < ' . (int) $comment->guid];
		}
		
		// this won't work with threaded comments, but core doesn't support that yet
		$count = elgg_get_entities([
			'type' => 'object',
			'subtype' => $comment->getSubtype(),
			'container_guid' => $container->guid,
			'reverse_order_by' => $reverse_order_by,
			'count' => true,
			'wheres' => $wheres,
		]);
		$limit = (int) get_input('limit');
		if (!$limit) {
			$limit = (int) elgg_trigger_plugin_hook('config', 'comments_per_page', [], elgg_extract('limit', $comment_settings));
		}
		$offset = floor($count / $limit) * $limit;
		if (!$offset) {
			$offset = null;
		}
	
		$url = elgg_http_add_url_query_elements($container->getURL(), [
			'offset' => $offset,
		]);
		
		// make sure there's only one fragment (#)
		$parts = parse_url($url);
		$parts['fragment'] = "elgg-object-{$comment->guid}";
		$url = elgg_http_build_url($parts, false);
		
		forward($url);
	}
}
