<?php

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$yesno_options = [
	'yes' => elgg_echo('option:yes'),
	'no' => elgg_echo('option:no'),
];

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('advanced_comments:settings:show_login_form'),
	'name' => 'params[show_login_form]',
	'value' => $plugin->show_login_form,
	'options_values' => $yesno_options,
]);
