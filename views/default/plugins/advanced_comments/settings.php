<?php

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$yesno_options = [
	'yes' => elgg_echo('option:yes'),
	'no' => elgg_echo('option:no'),
];


// default settings
$defaults = elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('advanced_comments:header:order'),
	'name' => 'params[default_order]',
	'value' => $plugin->default_order,
	'options_values' => [
		'desc' => elgg_echo('advanced_comments:header:order:desc'),
		'asc' => elgg_echo('advanced_comments:header:order:asc'),
	],
]);
$defaults .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('advanced_comments:header:limit'),
	'name' => 'params[default_limit]',
	'value' => ($plugin->default_limit) ? $plugin->default_limit : 25,
	'options' => [5, 10, 25, 50, 100],
]);
$defaults .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('advanced_comments:header:auto_load'),
	'#help' => elgg_echo('advanced_comments:settings:defaults:auto_load:help'),
	'name' => 'params[default_auto_load]',
	'value' => $plugin->default_auto_load,
	'options_values' => array_reverse($yesno_options),
]);
$defaults .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('advanced_comments:settings:defaults:user_preference'),
	'name' => 'params[user_preference]',
	'value' => $plugin->user_preference,
	'options_values' => $yesno_options,
]);

echo elgg_view_module('inline', elgg_echo('advanced_comments:settings:defaults'), $defaults);

// form helper
$helper = elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('advanced_comments:settings:show_login_form'),
	'name' => 'params[show_login_form]',
	'value' => $plugin->show_login_form,
	'options_values' => $yesno_options,
]);

echo elgg_view_module('inline', elgg_echo('advanced_comments:settings:helper'), $helper);