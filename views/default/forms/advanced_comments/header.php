<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

// $comment_settings = elgg_extract('advanced_comments', $vars);

// $auto_load = elgg_extract('auto_load', $comment_settings);

// load js
elgg_require_js('forms/advanced_comments/header');

$fields = [
	[
		'#type' => 'checkbox',
		'#label' => elgg_echo('advanced_comments:header:order:desc'),
		'name' => 'order',
		'checked' => elgg_comments_are_latest_first($entity),
		'switch' => true,
		'default' => 'asc',
		'value' => 'desc',
	], [
		'#type' => 'checkbox',
		'#label' => elgg_echo('advanced_comments:header:auto_load'),
		'name' => 'auto_load',
		'checked' => elgg_get_plugin_setting('auto_load', 'advanced_comments') === 'yes',
		'switch' => true,
		'default' => 'no',
		'value' => 'yes',
	], [
		'#type' => 'select',
		'#label' => elgg_echo('advanced_comments:header:limit'),
		'name' => 'limit',
		'value' => elgg_comments_per_page($entity),
		'options' => [5, 10, 25, 50, 100],
	], [
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => $entity->guid,
	], [
		'#type' => 'hidden',
		'name' => 'offset',
		'value' => 0,
	], [
		'#type' => 'hidden',
		'name' => 'save_settings',
		'value' => 'yes',
	], [
		'#type' => 'hidden',
		'name' => 'subtype',
		'value' => $entity->getSubtype(),
	],
];

echo elgg_view('input/fieldset', [
	'fields' => $fields,
	'align' => 'horizontal',
]);
