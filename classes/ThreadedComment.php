<?php

/**
 * Make threaded comments possible
 *
 * @property int $level       the depth of this comment in a thread
 * @property int $parent_guid the parent of this comment
 * @property int thread_guid  the thread guid
 */
class ThreadedComment extends \ElggComment {

	/**
	 * {@inheritDoc}
	 */
	public function canComment($user_guid = 0, $default = null) {
		if ($this->getLevel() >= (int) elgg_get_plugin_setting('threaded_comments', 'advanced_comments')) {
			return false;
		}
		
		$container = $this->getContainerEntity();
		if (!$container instanceof ElggEntity) {
			return false;
		}
		
		return $container->canComment($user_guid, $default);
	}
	
	/**
	 * Get the depth level of the comment
	 *
	 * @return int 1: toplevel, 2: first level, etc
	 */
	public function getLevel() {
		return isset($this->level) ? (int) $this->level : 1;
	}
	
	/**
	 * Return the thread GUID this comment is a part of
	 *
	 * @return int
	 */
	public function getThreadGUID() {
		if (isset($this->thread_guid)) {
			return (int) $this->thread_guid;
		}
		
		return $this->guid;
	}
	
	/**
	 * Return the thread (top-level) comment
	 *
	 * @return ThreadedComment
	 */
	public function getThreadEntity() {
		return get_entity($this->getThreadGUID());
	}
	
	/**
	 * Return the parent GUID of this comment
	 *
	 * @return int
	 */
	public function getParentGUID() {
		if (isset($this->parent_guid)) {
			return (int) $this->parent_guid;
		}
		
		return $this->guid;
	}
	
	/**
	 * Return the parent comment
	 *
	 * @return ThreadedComment
	 */
	public function getParentEntity() {
		return get_entity($this->getParentGUID());
	}
}
