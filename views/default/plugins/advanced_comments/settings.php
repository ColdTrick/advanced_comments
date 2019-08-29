<?php

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('advanced_comments:header:order'),
	'name' => 'params[default_order]',
	'value' => $plugin->default_order,
	'options_values' => [
		'desc' => elgg_echo('advanced_comments:header:order:desc'),
		'asc' => elgg_echo('advanced_comments:header:order:asc'),
	],
]);

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('advanced_comments:header:limit'),
	'name' => 'params[default_limit]',
	'value' => ($plugin->default_limit) ? $plugin->default_limit : 25,
	'min' => 5,
	'max' => 100,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('advanced_comments:header:auto_load'),
	'#help' => elgg_echo('advanced_comments:settings:auto_load:help'),
	'name' => 'params[default_auto_load]',
	'checked' => $plugin->default_auto_load === 'yes',
	'switch' => true,
	'default' => 'no',
	'value' => 'yes',
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('advanced_comments:settings:user_preference'),
	'name' => 'params[user_preference]',
	'checked' => $plugin->user_preference === 'yes',
	'switch' => true,
	'default' => 'no',
	'value' => 'yes',
]);

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
