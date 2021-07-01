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
	 * @see Elgg\Menus\Social::registerComments()
	 */
	public static function registerCommentItems(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggEntity || $entity instanceof \ThreadedComment) {
			return;
		}
		
		return \Elgg\Menus\Social::registerComments($hook);
	}
}
