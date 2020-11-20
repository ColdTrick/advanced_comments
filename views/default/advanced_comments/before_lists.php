<?php
/**
 * Elgg comments count preloader (extends page/components/list)
 *
 * @uses $vars['preload_comments_count'] Set to true if you want to try to preload comments count. If not set it will try to determine automatically if it is needed.
 * @uses $vars['items']                  Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['list_class']             Additional CSS class for the <ul> element
 */

$items = (array) elgg_extract('items', $vars, []);
$entities = array_filter($items, function ($e) {
	return $e instanceof \ElggEntity;
});

if (count($entities) < 3) {
	return;
}

$preload = elgg_extract('preload_comments_count', $vars);
if (!isset($preload)) {
	$list_class = elgg_extract('list_class', $vars);
	$preload = in_array($list_class, ['elgg-list-entity', 'comments-list']);
}

if (!$preload) {
	return;
}

$preloader = new \ColdTrick\AdvancedComments\Preloader(\ColdTrick\AdvancedComments\DataService::instance());
$preloader->preloadForList($entities);
