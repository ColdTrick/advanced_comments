<?php
/**
 * List comments with optional add form
 *
 * @uses $vars['entity']        ElggEntity
 * @uses $vars['show_add_form'] Display add form or not
 * @uses $vars['id']            Optional id for the div
 * @uses $vars['class']         Optional additional class for the div
 * @uses $vars['limit']         Optional limit value (default is 25)
 */

use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\OrderByClause;
use ColdTrick\AdvancedComments\DI\ThreadPreloader;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$show_add_form = elgg_extract('show_add_form', $vars, true);

$latest_first = elgg_comments_are_latest_first($entity);

$limit = elgg_extract('limit', $vars, get_input('limit', 0));
if (!$limit) {
	$limit = elgg_comments_per_page($entity);
}

$module_vars = [
	'id' => elgg_extract('id', $vars, 'comments'),
	'class' => elgg_extract_class($vars, 'elgg-comments'),
];

$options = [
	'type' => 'object',
	'subtype' => 'comment',
	'container_guid' => $entity->guid,
	'full_view' => true,
	'limit' => $limit,
	'offset' => (int) get_input('offset'),
	'distinct' => false,
	'url_fragment' => $module_vars['id'],
	'order_by' => [new OrderByClause('e.guid', $latest_first ? 'DESC' : 'ASC')],
	'list_class' => 'comments-list',
	'wheres' => [],
	'pagination' => true,
];

if (!$entity instanceof ThreadedComment) {
	// only show top level comments
	$top_comments_where = function (QueryBuilder $qb, $main_alias) use ($entity) {
		$thread_md = $qb->subquery('metadata', 'thread_md');
		
		$thread_md->select('entity_guid');
		$thread_entity = $thread_md->joinEntitiesTable('thread_md', 'entity_guid');
		
		$thread_md->where($qb->compare("{$thread_entity}.type", '=', 'object', ELGG_VALUE_STRING))
			->andWhere($qb->compare("{$thread_entity}.subtype", '=', 'comment', ELGG_VALUE_STRING))
			->andWhere($qb->compare("{$thread_entity}.container_guid", '=', $entity->guid, ELGG_VALUE_GUID))
			->andWhere($qb->compare('thread_md.name', '=', 'thread_guid', ELGG_VALUE_STRING));
		
		return $qb->compare("{$main_alias}.guid", 'not in', $thread_md->getSQL());
	};
	$options['wheres'][] = $top_comments_where;
	
	$show_guid = (int) elgg_extract('show_guid', $vars);
	if ($show_guid && $limit) {
		// show the offset that includes the comment
		// this won't work with threaded comments, but core doesn't support that yet
		$operator = $latest_first ? '>' : '<';
		$condition = function(QueryBuilder $qb) use ($show_guid, $operator) {
			return $qb->compare('e.guid', $operator, $show_guid, ELGG_VALUE_INTEGER);
		};
		$count = elgg_count_entities([
			'type' => 'object',
			'subtype' => 'comment',
			'container_guid' => $entity->guid,
			'wheres' => [
				$condition,
				$top_comments_where,
			],
		]);
		$options['offset'] = (int) floor($count / $limit) * $limit;
	}
	
	$comments = elgg_get_entities($options);
	
	$count_options = $options;
	unset($count_options['offset']);
	$options['count'] = elgg_count_entities($count_options);
	
	// preload comment threads
	ThreadPreloader::instance()->preloadThreads($comments);
} else {
	$comments = ThreadPreloader::instance()->getChildren($entity->guid);
	
	// load children of thread
	$options['limit'] = false;
	$options['pagination'] = false;
	$options['count'] = count($comments);
}

$comments_list = elgg_view_entity_list($comments, $options);

$content = $comments_list;
if ($show_add_form && $entity->canComment()) {
	$form_vars = [];
	if ($latest_first && $comments_list && elgg_get_config('comment_box_collapses')) {
		$form_vars['class'] = 'hidden';
		$form_vars['id'] = "elgg-form-comment-save-{$entity->guid}";
		
		$module_vars['menu'] = elgg_view_menu('comments', [
			'items' => [
				[
					'name' => 'add',
					'text' => elgg_echo('generic_comments:add'),
					'href' => '#' . $form_vars['id'],
					'icon' => 'plus',
					'class' => ['elgg-button', 'elgg-button-action'],
					'rel' => 'toggle',
				],
			],
		]);
	}
	
	$form = elgg_view_form('comment/save', $form_vars, $vars);
	if ($latest_first) {
		$content = $form . $content;
	} else {
		$content .= $form;
	}
}

if (empty($content)) {
	return;
}

echo elgg_view_module('comments', elgg_echo('comments'), $content, $module_vars);
