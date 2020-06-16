<?php

namespace ColdTrick\AdvancedComments;

use Elgg\Database\QueryBuilder;

class Comments {
	/**
	 * Gets the comments per page
	 *
	 * @param \Elgg\Hook $hook 'config', 'comments_per_page'
	 *
	 * @return void|false
	 */
	public static function getCommentsPerPage(\Elgg\Hook $hook) {
		
		$setting = elgg_get_plugin_setting('default_limit', 'advanced_comments', $hook->getValue());
		
		if (($setting < 5) || ($setting > 100)) {
			return;
		}
		
		return $setting;
	}
	
	/**
	 * Remove all the children comments of the given comment
	 *
	 * @param \Elgg\Event $event 'delete:after', 'object'
	 *
	 * @return void
	 */
	public static function deleteChildrenComments(\Elgg\Event $event) {
		
		$entity = $event->getObject();
		if (!$entity instanceof \ThreadedComment) {
			return;
		}
		
		elgg_call(ELGG_IGNORE_ACCESS & ELGG_SHOW_DISABLED_ENTITIES, function() use($entity) {
			$children = elgg_get_entities([
				'type' => 'object',
				'subtype' => 'comment',
				'limit' => false,
				'batch' => true,
				'batch_inc_offset' => false,
				'metadata_name_value_pairs' => [
					'name' => 'parent_guid',
					'value' => $entity->guid,
				],
			]);
			
			/* @var $child \ThreadedComment */
			foreach ($children as $child) {
				$child->delete();
			}
		});
	}
	
	/**
	 * Get the where clause to only return top level comments
	 *
	 * @param \ElggEntity $entity for which entity to return top level comments
	 *
	 * @return callable
	 */
	public static function getToplevelCommentsWhere(\ElggEntity $entity) {
		return function (QueryBuilder $qb, $main_alias) use ($entity) {
			$thread_md = $qb->subquery('metadata', 'thread_md');
			
			$thread_md->select('entity_guid');
			$thread_entity = $thread_md->joinEntitiesTable('thread_md', 'entity_guid');
			
			$thread_md->where($qb->compare("{$thread_entity}.type", '=', 'object', ELGG_VALUE_STRING))
				->andWhere($qb->compare("{$thread_entity}.subtype", '=', 'comment', ELGG_VALUE_STRING))
				->andWhere($qb->compare("{$thread_entity}.container_guid", '=', $entity->guid, ELGG_VALUE_GUID))
				->andWhere($qb->compare('thread_md.name', '=', 'thread_guid', ELGG_VALUE_STRING));
			
			return $qb->compare("{$main_alias}.guid", 'not in', $thread_md->getSQL());
		};
	}
}
