<?php
/**
 * Load a new set of comments
 */

$guid = (int) elgg_extract('guid', $vars, get_input('guid'));
$limit = (int) elgg_extract('advanced_comments_limit', $vars, get_input('limit'));
$offset = (int) elgg_extract('advanced_comments_offset', $vars, get_input('offset'));
$order = elgg_extract('advanced_comments_order', $vars, get_input('order'));
$auto_load = elgg_extract('advanced_comments_auto_load', $vars, get_input('auto_load'));
$save_settings = elgg_extract('save_settings', $vars, get_input('save_settings'));

elgg_entity_gatekeeper($guid);
$entity = get_entity($guid);

// save settings
if ($save_settings === 'yes') {
	$setting_name = implode(':', [
		'comment_settings',
		$entity->getType(),
		$entity->getSubtype(),
	]);
	
	$settings = [
		$order,
		$limit,
		$auto_load,
	];
	// store in session for easy reuse
	$session = elgg_get_session();
	$all_settings = (array) $session->get('advanced_comments', []);
	$all_settings[$setting_name] = $settings;
	$session->set('advanced_comments', $all_settings);
	
	if (elgg_is_logged_in()) {
		elgg_set_plugin_user_setting($setting_name, implode('|', $settings), 0, 'advanced_comments');
	}
}

// show comments
$reverse_order_by = false;
if ($order === 'asc') {
	$reverse_order_by = true;
}

$pagination = true;
if ($auto_load === 'yes') {
	$pagination = false;
}

$comment_options = [
	'type' => 'object',
	'subtype' => 'comment',
	'container_guid' => $entity->guid,
	'reverse_order_by' => $reverse_order_by,
	'full_view' => true,
	'limit' => $limit,
	'offset' => $offset,
	'preload_owners' => true,
	'distinct' => false,
	'pagination' => $pagination,
];
echo elgg_list_entities($comment_options);

if ($pagination) {
	// not using autoload
	return;
}

$comment_options['count'] = true;
$count = elgg_get_entities($comment_options);
if ($count <= ($offset + $limit)) {
	// no need to load more
	return;
}

$remaining = $count - ($offset + $limit);
echo elgg_format_element('div', [
	'id' => 'advanced-comments-more',
	'class' => 'center',
], elgg_view('output/url', [
	'text' => elgg_echo('river:comments:more', [$remaining]),
	'href' => elgg_http_add_url_query_elements('ajax/view/advanced_comments/load', [
		'limit' => $limit,
		'offset' => $offset + $limit,
		'auto_load' => $auto_load,
		'order' => $order,
		'guid' => $guid,
	]),
]));
