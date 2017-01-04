<?php

$comments_order = elgg_extract('advanced_comments_order', $vars);
$comments_limit = elgg_extract('advanced_comments_limit', $vars);
$auto_load = elgg_extract('advanced_comments_auto_load', $vars);
$entity = elgg_extract('entity', $vars);

elgg_require_js('advanced_comments/header');

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('advanced_comments:header:order'),
	'name' => 'order',
	'value' => $comments_order,
	'options_values' => [
		'desc' => elgg_echo('advanced_comments:header:order:desc'),
		'asc' => elgg_echo('advanced_comments:header:order:asc'),
	],
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('advanced_comments:header:limit'),
	'name' => 'limit',
	'value' => $comments_limit,
	'options' => [5, 10, 25, 50, 100],
]);

echo elgg_view_field([
	'#type' => 'select',
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
