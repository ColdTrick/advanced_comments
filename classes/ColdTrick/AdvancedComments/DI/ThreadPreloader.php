<?php

namespace ColdTrick\AdvancedComments\DI;

use Elgg\Database\Clauses\OrderByClause;
use Elgg\Traits\Di\ServiceFacade;

class ThreadPreloader {

	use ServiceFacade;
	
	/**
	 * @var \ThreadedComment[]
	 */
	protected $children;
	
	/**
	 * {@inheritdoc}
	 */
	public static function name() {
		return 'advanced_comments.thread_preloader';
	}
	
	/**
	 * Preload the comment threads for the given comments
	 *
	 * @param \ThreadedComment[] $comments top level comments
	 *
	 * @return void
	 */
	public function preloadThreads(array $comments) {
		if (empty($comments)) {
			return;
		}
		
		$this->children = [];
		
		$guids = [];
		$container_guid = 0;
		/* @var $comment \ThreadedComment */
		foreach ($comments as $comment) {
			$guids[] = $comment->guid;
			$container_guid = $comment->container_guid;
		}
		
		$batch = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'comment',
			'limit' => false,
			'batch' => true,
			'container_guid' => $container_guid,
			'metadata_name_value_pairs' => [
				'name' => 'thread_guid',
				'value' => $guids,
			],
			'order_by' => new OrderByClause('e.time_created', 'ASC'),
		]);
		
		/* @var $comment \ThreadedComment */
		foreach ($batch as $comment) {
			$parent_guid = (int) $comment->parent_guid;
			if (!isset($this->children[$parent_guid])) {
				$this->children[$parent_guid] = [];
			}
			
			$this->children[$parent_guid][] = $comment;
		}
	}
	
	/**
	 * Get the children of a comment
	 *
	 * @param int $comment_guid the parent comment
	 *
	 * @return \ThreadedComment[]
	 */
	public function getChildren(int $comment_guid) {
		if (!isset($this->children)) {
			$comment = get_entity($comment_guid);
			if ($comment instanceof \ThreadedComment) {
				$this->preloadThreads([$comment->getThreadEntity()]);
			}
		}
		
		return elgg_extract($comment_guid, $this->children, []);
	}
}
