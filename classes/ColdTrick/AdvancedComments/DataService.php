<?php

namespace ColdTrick\AdvancedComments;

/**
 * AdvancedComments dataservice
 *
 * @internal
 */
class DataService {

	/**
	 * @var array [GUID => int]
	 */
	protected $counts = [];

	/**
	 * Set number of comments
	 *
	 * @param int $guid for guid
	 * @param int $num  number of comments
	 *
	 * @return void
	 */
	public function setCommentsCount($guid, $num) {
		$this->counts[$guid] = (int) $num;
	}

	/**
	 * Get the number of comments for an entity
	 *
	 * @param \ElggEntity $entity the entity to fetch for
	 *
	 * @return void|int
	 */
	public function getCommentsCount(\ElggEntity $entity) {
		$guid = $entity->guid;
		if (!isset($this->counts[$guid])) {
			return;
		}
		
		return $this->counts[$guid];
	}

	/**
	 * Removes already counted comments from list of guids
	 *
	 * @param array $guids array of guids
	 *
	 * @return []
	 */
	public function filterGuids(array $guids) {
		foreach ($guids as $key => $guid) {
			if (!isset($this->counts[$guid])) {
				continue;
			}
			
			unset($guids[$key]);
		}
		
		return array_values($guids);
	}

	/**
	 * Get a DataService instance
	 *
	 * @return \ColdTrick\AdvancedComments\DataService
	 */
	public static function instance() {
		static $inst;
		if ($inst === null) {
			$inst = new self();
		}
		return $inst;
	}
}
