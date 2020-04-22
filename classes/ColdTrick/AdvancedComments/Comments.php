<?php

namespace ColdTrick\AdvancedComments;

class Comments {
	/**
	 * Gets the comments per page
	 *
	 * @param \Elgg\Hook $hook 'config', 'comments_per_page'
	 *
	 * @return void|false
	 */
	public static function getCommentsPerPage(\Elgg\Hook $hook) {
		
		$setting = elgg_get_plugin_setting('default_limit', 'advanced_comments', $hook->getValue());
		
		if (($setting < 5) || ($setting > 100)) {
			return;
		}
		
		return $setting;
	}
}
