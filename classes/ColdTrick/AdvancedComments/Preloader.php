<?php

namespace ColdTrick\AdvancedComments;

/**
 * AdvancedComments preloader
 */
class Preloader {

	/**
	 * @var \ColdTrick\AdvancedComments\DataService
	 */
	protected $data;

	/**
	 * Create a preloader
	 *
	 * @param \ColdTrick\AdvancedComments\DataService $data a dataservice
	 */
	public function __construct(DataService $data) {
		$this->data = $data;
	}

	/**
	 * Preload comments count for a set of items
	 *
	 * @param \ElggEntity[]|\ElggRiverItem[] $items the items to preload for
	 *
	 * @return void
	 */
	public function preloadForList(array $items) {
		$guids = $this->getGuidsToPreload($items);
	
		$this->preloadCountsFromQuery($guids);
	}

	/**
	 * Preload comments count based on guids
	 *
	 * @param int[] $guids the guids to preload
	 *
	 * @return void
	 */
	protected function preloadCountsFromQuery(array $guids) {
		$count_rows = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'comment',
			'container_guids' => $guids,
			'selects' => ['e.container_guid', 'COUNT(*) AS cnt'],
			'group_by' => 'e.container_guid',
			'limit' => false,
			'callback' => false,
		]);
		
		foreach ($guids as $guid) {
			$this->data->setCommentsCount($guid, 0);
		}
		foreach ($count_rows as $row) {
			$this->data->setCommentsCount($row->container_guid, $row->cnt);
		}
	}

	/**
	 * Convert entities to guids
	 *
	 * @param \ElggEntity[]|\ElggRiverItem[] $items the entities to process
	 *
	 * @return int[]
	 */
	protected function getGuidsToPreload(array $items) {
		$guids = [];

		foreach ($items as $item) {
			if ($item instanceof \ElggEntity) {
				$guids[$item->guid] = true;
			} elseif ($item instanceof \ElggRiverItem) {
				$guids[$item->object_guid] = true;
			}
		}
		
		return array_keys($guids);
	}
}
