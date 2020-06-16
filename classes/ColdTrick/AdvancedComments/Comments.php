<?php

namespace ColdTrick\AdvancedComments;

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
}
