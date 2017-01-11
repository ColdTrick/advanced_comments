<?php

namespace ColdTrick\AdvancedComments;

class Comments {
	
	/**
	 * Redo the comment forwarding
	 *
	 * @param string     $hook         the name of the hook
	 * @param string     $type         the type of the hook
	 * @param bool|array $return_value current return value
	 * @param mixed      $params       supplied params
	 *
	 * @retrun void|false
	 */
	public static function route($hook, $type, $return_value, $params) {
		
		if (!is_array($return_value)) {
			return;
		}
		
		$segments = elgg_extract('segments', $return_value);
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
	 * @access private
	 */
	protected static function commentRedirect($comment_guid, $container_guid) {
		
		$fail = function () {
			register_error(elgg_echo('generic_comment:notfound'));
			forward(REFERER);
		};
	
		$comment = get_entity($comment_guid);
		if (!$comment) {
			// try fallback if given
			$fallback = get_entity($fallback_guid);
			if (!$fallback) {
				$fail();
			}
	
			register_error(elgg_echo('generic_comment:notfound_fallback'));
			forward($fallback->getURL());
		}
	
		if (!elgg_instanceof($comment, 'object', 'comment')) {
			$fail();
		}
	
		$container = $comment->getContainerEntity();
		if (!$container) {
			$fail();
		}
		
		$comment_settings = advanced_comments_get_comment_settings($comment);
		
		$reverse_order_by = false;
		$wheres = ['e.guid > ' . (int) $comment->guid];
		if (elgg_extract('order', $comment_settings) === 'asc') {
			$reverse_order_by = true;
			$wheres = ['e.guid < ' . (int) $comment->guid];
		}
		
		// this won't work with threaded comments, but core doesn't support that yet
		$count = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'comment',
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
