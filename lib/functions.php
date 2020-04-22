<?php
/**
 * All helper functions can be found here
 */

/**
 * Get the comment settings for an entity
 *
 * @param ElggEntity $entity    the entity to get for
 * @param int        $user_guid the user to get for (default: current logged in user)
 *
 * @return array
 */
function advanced_comments_get_comment_settings(ElggEntity $entity = null, $user_guid = 0) {
	
	// load the plugin settings
	$settings = [
		'order' => elgg_get_plugin_setting('default_order', 'advanced_comments', 'desc'),
		'limit' => (int) elgg_get_plugin_setting('default_limit', 'advanced_comments', 25),
		'auto_load' => elgg_get_plugin_setting('default_auto_load', 'advanced_comments', 'no'),
	];
	
	if (!$entity instanceof ElggEntity) {
		return $settings;
	}
	
	// get user settings?
	$user_preference = elgg_get_plugin_setting('user_preference', 'advanced_comments', 'yes');
	if ($user_preference !== 'yes') {
		return $settings;
	}
	
	$user_guid = (int) $user_guid;
	if (empty($user_guid)) {
		$user_guid = elgg_get_logged_in_user_guid();
	}
	
	$session = elgg_get_session();
	$all_comment_settings = (array) $session->get('advanced_comments', []);
	
	$setting_name = implode(':', [
		'comment_settings',
		$entity->getType(),
		$entity->getSubtype(),
	]);
	$user_settings = (array) elgg_extract($setting_name, $all_comment_settings, []);
	if (empty($user_settings) && !empty($user_guid)) {
		// not loaded yet in session, load from plugin settings
		$plugin_settings = elgg_get_plugin_user_setting($setting_name, $user_guid, 'advanced_comments');
		if (!empty($plugin_settings)) {
			$comment_settings = explode('|', $plugin_settings);
			$user_settings = [
				'order' => elgg_extract(0, $comment_settings, 'desc'),
				'limit' => (int) elgg_extract(1, $comment_settings, 25),
				'auto_load' => elgg_extract(2, $comment_settings, 'no'),
			];
			
			// save to session for easy access next time
			$all_comment_settings[$setting_name] = $user_settings;
			$session->set('advanced_comments', $all_comment_settings);
		}
	}
	
	return array_merge($settings, $user_settings);
}
