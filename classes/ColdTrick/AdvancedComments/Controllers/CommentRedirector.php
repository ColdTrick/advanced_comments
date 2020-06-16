<?php

namespace ColdTrick\AdvancedComments\Controllers;

use Elgg\Http\ResponseBuilder;
use Elgg\Database\QueryBuilder;

/**
 * Controller to redirect comment URL's to the entity URL with pagination
 */
class CommentRedirector {
	
	/**
	 * Redirect a comment to the correct entity
	 *
	 * @see _elgg_comment_redirect()
	 *
	 * @param \Elgg\Http\Request $request current HTTP request
	 *
	 * @return ResponseBuilder
	 */
	public function __invoke(\Elgg\Request $request) {
		$comment_guid = (int) $request->getParam('guid');
		$container_guid = (int) $request->getParam('container_guid');
		
		$fail = function () {
			register_error(elgg_echo('generic_comment:notfound'));
			return elgg_redirect_response();
		};
		
		$comment = get_entity($comment_guid);
		if (!$comment instanceof \ThreadedComment) {
			// try fallback if given
			$fallback = get_entity($container_guid);
			if (!$fallback instanceof \ElggEntity) {
				return $fail();
			}
			
			register_error(elgg_echo('generic_comment:notfound_fallback'));
			return elgg_redirect_response($fallback->getURL());
		}
		
		if (!$comment instanceof \ThreadedComment) {
			return $fail();
		}
		
		$top_comment = $comment->getThreadEntity();
		if (!$top_comment instanceof \ThreadedComment) {
			return $fail();
		}
		
		$container = $comment->getContainerEntity();
		if (!$container instanceof \ElggEntity) {
			return $fail();
		}
		
		$operator = elgg_comments_are_latest_first($container) ? '>' : '<';
		
		// this won't work with threaded comments, but core doesn't support that yet
		$count = elgg_count_entities([
			'type' => 'object',
			'subtype' => 'comment',
			'container_guid' => $container->guid,
			'wheres' => [
				function(QueryBuilder $qb, $main_alias) use ($top_comment, $operator) {
					return $qb->compare("{$main_alias}.guid", $operator, $top_comment->guid, ELGG_VALUE_GUID);
				},
				function (QueryBuilder $qb, $main_alias) use ($container) {
					$thread_md = $qb->subquery('metadata', 'thread_md');
					
					$thread_md->select('entity_guid');
					$thread_entity = $thread_md->joinEntitiesTable('thread_md', 'entity_guid');
					
					$thread_md->where($qb->compare("{$thread_entity}.type", '=', 'object', ELGG_VALUE_STRING))
						->andWhere($qb->compare("{$thread_entity}.subtype", '=', 'comment', ELGG_VALUE_STRING))
						->andWhere($qb->compare("{$thread_entity}.container_guid", '=', $container->guid, ELGG_VALUE_GUID))
						->andWhere($qb->compare('thread_md.name', '=', 'thread_guid', ELGG_VALUE_STRING));
					
					return $qb->compare("{$main_alias}.guid", 'not in', $thread_md->getSQL());
				},
			],
		]);
		$limit = (int) get_input('limit');
		if (!$limit) {
			$limit = elgg_comments_per_page($container);
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
