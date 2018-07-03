<?php

if (!elgg_extract('advanced_comments_show_autoload', $vars)) {
	return;
}

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$total = (int) elgg_extract('count', $vars);
$limit = (int) elgg_extract('limit', $vars);
$count = (int) count(elgg_extract('items', $vars, []));
$offset = (int) elgg_extract('offset', $vars);

$remaining = $total - ($offset + $count);
if (!$remaining) {
	return;
}

echo elgg_format_element('div', [
	'id' => 'advanced-comments-more',
	'class' => 'mtl center',
], elgg_view('output/url', [
	'text' => elgg_echo('river:comments:more', [$remaining]),
	'href' => elgg_http_add_url_query_elements('ajax/view/advanced_comments/comments', [
		'limit' => $limit,
		'offset' => $offset + $limit,
		'guid' => $entity->guid,
	]),
	'class' => [
		'elgg-button',
		'elgg-button-outline',
	],
]));
