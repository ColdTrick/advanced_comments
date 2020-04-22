<?php

namespace ColdTrick\AdvancedComments;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {

	/**
	 * {@inheritDoc}
	 * @see \Elgg\DefaultPluginBootstrap::ready()
	 */
	public function ready() {
		$plugin = $this->plugin();
		
		if ((bool) $plugin->getSetting('allow_group_comments')) {
			$this->elgg()->hooks->unregisterHandler('permissions_check:comment', 'object', '_elgg_groups_comment_permissions_override');
		}
	}
}
