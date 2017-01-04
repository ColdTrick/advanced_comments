<?php
/**
 * List comments with optional add form
 *
 * @uses $vars['entity']        ElggEntity
 * @uses $vars['show_add_form'] Display add form or not
 * @uses $vars['id']            Optional id for the div
 * @uses $vars['class']         Optional additional class for the div
 * @uses $vars['limit']         Optional limit value (default is 25)
 *
 * @todo look into restructuring this so we are not calling elgg_list_entities()
 * in this view
 */

$show_add_form = (bool) elgg_extract('show_add_form', $vars, true);
$full_view = (bool) elgg_extract('full_view', $vars, true);
$limit = (int) elgg_extract('limit', $vars, get_input('limit', 0));
if (!$limit) {
	$limit = (int) elgg_trigger_plugin_hook('config', 'comments_per_page', [], 25);
}
/* @var $entity ElggEntity */
$entity = elgg_extract('entity', $vars);

$attr = [
	'id' => elgg_extract('id', $vars, 'comments'),
	'class' => elgg_extract_class($vars, 'elgg-comments'),
];

// work around for deprecation code in elgg_view()
unset($vars['internalid']);

// set options for advanced comments
$session = elgg_get_session();
$all_comment_settings = (array) $session->get('advanced_comments', []);

$setting_name = implode(':', [
	'comment_settings',
	$entity->getType(),
	$entity->getSubtype(),
]);

$default_settings = [
	'desc', // order
	max(0, $limit), // limit
	'no', // auto load next comments
];
$comment_settings = (array) elgg_extract($setting_name, $all_comment_settings);
if (empty($comment_settings)) {
	// get settings from DB
	$settings = elgg_get_plugin_user_setting($setting_name, 0, 'advanced_comments');
	if (!empty($settings)) {
		$comment_settings = explode('|', $settings);
		
		$all_comment_settings[$setting_name] = $comment_settings;
		$session->set('advanced_comments', $all_comment_settings);
	}
}

$reverse_order_by = false;
if (elgg_extract(0, $comment_settings, $default_settings[0]) === 'asc') {
	$reverse_order_by = true;
}

$limit = (int) elgg_extract(1, $comment_settings, elgg_extract(1, $default_settings));
$auto_load = elgg_extract(2, $comment_settings, $default_settings[2]);

$vars['advanced_comments_order'] = $reverse_order_by ? 'asc' : 'desc';
$vars['advanced_comments_limit'] = $limit;
$vars['advanced_comments_auto_load'] = $auto_load;

$content = '';
if ($show_add_form) {
	$content .= elgg_view_form('comment/save', array(), $vars);
}

$comments = elgg_view('advanced_comments/load', $vars);
if (!empty($comments)) {
	$form_vars = [
		'action' => 'ajax/view/advanced_comments/load',
		'id' => 'advanced-comments-form',
	];
	$body_vars = array_merge($vars, $attr);
	
	$content .= elgg_view_form('advanced_comments/header', $form_vars, $body_vars);
	$content .= elgg_format_element('div', ['id' => 'advanced-comment-list'], $comments);
}

echo elgg_format_element('div', $attr, $content);

if (!elgg_is_logged_in() && $show_add_form) {
	if (elgg_get_plugin_setting('show_login_form', 'advanced_comments') !== 'no') {
		$login_form = elgg_view_form('login', [], ['returntoreferer' => true]);
		
		echo elgg_view_module('info', elgg_echo('advanced_comments:comment:logged_out'), $login_form, ['class' => 'mtl']);
	}
}
