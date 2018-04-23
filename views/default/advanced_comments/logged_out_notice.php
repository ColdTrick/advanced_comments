<?php

if (elgg_is_logged_in()) {
	return;
}

if (!elgg_extract('show_add_form', $vars)) {
	return;
}

if (elgg_get_plugin_setting('show_login_form', 'advanced_comments') == 'no') {
	return;
}

$login_form = elgg_view_form('login', [
	'id' => 'advanced-comments-logged-out',
	'class' => 'hidden',
], ['returntoreferer' => true]);

$title_text = elgg_echo('messages:title:notice');
$title_menu = elgg_format_element('span', ['class' => 'float-alt'], elgg_view_menu('comments_logged_out', [
	'items' => [
		[
			'name' => 'show_form',
			'text' => elgg_echo('login'),
			'href' => false,
			'rel' => 'toggle',
			'data-toggle-selector' => '#advanced-comments-logged-out',
		],
	],
]));

$title = elgg_format_element('span', [], $title_menu . $title_text);

$content = elgg_view('output/longtext', [
	'value' => elgg_echo('advanced_comments:comment:logged_out'),
	'class' => 'center',
]);
$content .= $login_form;

echo elgg_view_message('notice', $content, [
	'title' => $title,
	'class' => 'mtl',
]);
