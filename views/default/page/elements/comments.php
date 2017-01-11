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

/* @var $entity ElggEntity */
$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ElggEntity)) {
	return;
}

$vars['guid'] = $entity->getGUID();

$attr = [
	'id' => elgg_extract('id', $vars, 'comments'),
	'class' => elgg_extract_class($vars, 'elgg-comments'),
];

// work around for deprecation code in elgg_view()
unset($vars['internalid']);

// get options for advanced comments
$comment_settings = advanced_comments_get_comment_settings($entity);

$limit = (int) elgg_extract('limit', $vars, get_input('limit', 0));
if (!$limit) {
	$limit = (int) elgg_trigger_plugin_hook('config', 'comments_per_page', [], elgg_extract('limit', $comment_settings));
}
$comment_settings['limit'] = $limit;

$vars['advanced_comments'] = $comment_settings;

$content = '';
if ($show_add_form) {
	$content .= elgg_view_form('comment/save', array(), $vars);
}

$comments = elgg_view('advanced_comments/load', $vars);
if (!empty($comments)) {
	// form to allow preference change
	$form_vars = [
		'action' => 'ajax/view/advanced_comments/load',
		'id' => 'advanced-comments-form',
	];
	$body_vars = array_merge($vars, $attr);
	
	$content .= elgg_view_form('advanced_comments/header', $form_vars, $body_vars);
	// show comments
	$content .= elgg_format_element('div', ['id' => 'advanced-comment-list'], $comments);
}

echo elgg_format_element('div', $attr, $content);

if (!elgg_is_logged_in() && $show_add_form) {
	if (elgg_get_plugin_setting('show_login_form', 'advanced_comments') !== 'no') {
		$login_form = elgg_view_form('login', [], ['returntoreferer' => true]);
		
		echo elgg_view_module('info', elgg_echo('advanced_comments:comment:logged_out'), $login_form, ['class' => 'mtl']);
	}
}
