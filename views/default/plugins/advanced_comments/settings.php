<?php

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('advanced_comments:settings:show_login_form'),
	'name' => 'params[show_login_form]',
	'checked' => $plugin->show_login_form === 'yes',
	'switch' => true,
	'default' => 'no',
	'value' => 'yes',
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('advanced_comments:settings:allow_group_comments'),
	'#help' => elgg_echo('advanced_comments:settings:allow_group_comments:help'),
	'name' => 'params[allow_group_comments]',
	'checked' => (bool) $plugin->allow_group_comments,
	'switch' => true,
	'value' => 1,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('advanced_comments:settings:threaded_comments'),
	'#help' => elgg_echo('advanced_comments:settings:threaded_comments:help'),
	'name' => 'params[threaded_comments]',
	'value' => $plugin->threaded_comments,
	'options_values' => [
		0 => elgg_echo('advanced_comments:settings:threaded_comments:none'),
		2 => 2,
		3 => 3,
		4 => 4,
	],
]);
