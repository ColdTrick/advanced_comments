<?php

/* @var $entity ElggEntity */
$entity = elgg_extract('entity', $vars);
$subtype = elgg_extract('subtype', $vars, 'comment');

$comment_settings = elgg_extract('advanced_comments', $vars);
$comments_order = elgg_extract('order', $comment_settings);
$comments_limit = elgg_extract('limit', $comment_settings);
$auto_load = elgg_extract('auto_load', $comment_settings);

// load js
elgg_require_js('advanced_comments/header');

$type = 'select';
if (elgg_get_plugin_setting('user_preference', 'advanced_comments', 'yes') === 'no') {
	$type = 'hidden';
}

echo elgg_view_field([
	'#type' => $type,
	'#label' => elgg_echo('advanced_comments:header:order'),
	'name' => 'order',
	'value' => $comments_order,
	'options_values' => [
		'desc' => elgg_echo('advanced_comments:header:order:desc'),
		'asc' => elgg_echo('advanced_comments:header:order:asc'),
	],
]);

echo elgg_view_field([
	'#type' => $type,
	'#label' => elgg_echo('advanced_comments:header:limit'),
	'name' => 'limit',
	'value' => $comments_limit,
	'options' => [5, 10, 25, 50, 100],
]);

echo elgg_view_field([
	'#type' => $type,
	'#label' => elgg_echo('advanced_comments:header:auto_load'),
	'name' => 'auto_load',
	'value' => $auto_load,
	'options_values' => [
		'no' => elgg_echo('option:no'),
		'yes' => elgg_echo('option:yes'),
	],
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'offset',
	'value' => 0,
]);
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'save_settings',
	'value' => 'yes',
]);
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'subtype',
	'value' => $subtype,
]);
