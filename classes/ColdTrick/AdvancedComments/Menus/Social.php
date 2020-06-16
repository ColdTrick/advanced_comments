<?php

namespace ColdTrick\AdvancedComments\Menus;

use Elgg\Menu\MenuItems;

/**
 * Change the social menu items
 */
class Social {
	
	/**
	 * Only register comment menu items on non comments
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:social'
	 *
	 * @return MenuItems
	 * @see _elgg_comments_social_menu_setup()
	 */
	public static function registerCommentItems(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggEntity || $entity instanceof \ThreadedComment) {
			return;
		}
		
		return _elgg_comments_social_menu_setup($hook);
	}
}
