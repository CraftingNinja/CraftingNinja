<?php

/**
 * GarlandTools
 * 	Parse data manually provided from GarlandTools
 * 	Legitimate data ends with v5.0, but historically it should be accurate for a good while
 */

namespace App\Services\Aspir\FFXIV;

use App\Services\Aspir\FFXIVService;

class GarlandTools
{
    private ?string $path;

	public function __construct(public FFXIVService $service)
	{
		$this->path = $this->service->dataDir . 'garlandTools/';
	}

	public function mobs()
	{
		$this->loopEndpoint('mob', function($data) {
			$mobId = $this->translateMobID($data->mob->id);

			$this->service->setData('mob', [
				'quest'   => $data->mob->quest ?? null,
				'level'   => $data->mob->lvl,
				'zone_id' => $data->mob->zoneid,
			], $mobId, true);

			// And now for dropped items
			foreach ($data->mob->drops as $itemId)
				$this->service->setData('item_mob', [
					'item_id' => $itemId,
					'mob_id'  => $mobId,
				]);
		});
	}

	public function npcs()
	{
		$this->loopEndpoint('npc', function($data) {
			$this->service->setData('npc', [
				'zone_id' => $data->npc->zoneid ?? null,
				'approx'  => $data->npc->approx ?? null,
			], $data->npc->id, true);

			if (isset($data->npc->coords))
				$this->service->setData('npc', [
					'x' => $data->npc->coords[0],
					'y' => $data->npc->coords[1],
				], $data->npc->id);
		});
	}

	public function instances()
	{
		$this->loopEndpoint('instance', function($data) {
			if (isset($data->instance->fights))
				foreach ($data->instance->fights as $f)
				{
					if (isset($f->coffer))
						foreach ($f->coffer->items as $itemId)
							$this->service->setData('instance_item', [
								'item_id'     => $itemId,
								'instance_id' => $data->instance->id,
							]);

					foreach ($f->mobs as $mobID)
						$this->service->setData('instance_mob', [
							'mob_id'      => $this->translateMobID($mobID),
							'instance_id' => $data->instance->id,
						]);
				}

			if (isset($data->instance->rewards))
				foreach ($data->instance->rewards as $itemId)
					$this->service->setData('instance_item', [
						'item_id'     => $itemId,
						'instance_id' => $data->instance->id,
					]);

			if (isset($data->instance->coffers))
				foreach ($data->instance->coffers as $coffer)
					foreach ($coffer->items as $itemId)
						$this->service->setData('instance_item', [
							'item_id'     => $itemId,
							'instance_id' => $data->instance->id,
						]);
		});
	}

	public function notebookNotebookDivision()
	{
		foreach ($this->service->data['notebookdivision'] as $nd)
		{
			// This row belongs to multiple notebookIds (RecipeNotebookLists)
			$rowId = $nd['id'] - 1; // 0 index'd, undoing artifical +1'ing for the math to work out below

			// https://github.com/ffxiv-teamcraft/ffxiv-teamcraft/blob/0b7c71d86a6fd3d7a315cd869fc13b16ee4259fb/data-extraction/src/index.js#L401-L416 -- Thanks Miu!
			foreach (range(0, 7) as $index)
			{
				$notebookId = $rowId < 1000
					? 40 * $index + $rowId
					: 1000 + 8 * ($rowId - 1000) + $index;

				// Artificially inflate notebookId to avoid the 0 index'd notebook id
				$notebookId++;

				$this->service->setData('notebook_notebookdivision', [
					'notebookdivision_id' => $nd['id'], // Need the real ID here, not the altered RowID
					'notebook_id'         => $notebookId,
				]);

				$this->service->setData('notebook', [
					'id' => $notebookId,
				], $notebookId);
			}
		}
	}

	private function translateMobID($mobId, bool $base = false)
	{
		// The mob id can be split between base and name
		return (int) $base
            ? $mobId / 10000000000
            : $mobId % 10000000000;
	}

	private function loopEndpoint($endpoint, $callback)
	{
		foreach ($this->getFileList($endpoint) as $file) {
            $callback($this->getJSONData($file, $endpoint));
        }
	}

	private function getFileList($endpoint, $language = 'en')
	{
		return array_diff(scandir($this->path . $language . '/' . $endpoint), ['.', '..']);
	}

	private function getJSONData($filename, $endpoint, $language = 'en')
	{
		$file = $this->path . $language . '/' . $endpoint . '/' . $filename;
		return $this->getCleanedJson($file);
	}

	private function getCleanedJson($path)
	{
		$content = file_get_contents($path);

		// http://stackoverflow.com/questions/17219916/json-decode-returns-json-error-syntax-but-online-formatter-says-the-json-is-ok
		for ($i = 0; $i <= 31; ++$i) {
			$content = str_replace(chr($i), "", $content);
        }

		$content = str_replace(chr(127), "", $content);

		// This is the most common part
		$content = $this->binaryFix($content);

		return json_decode($content);
	}

	private function binaryFix($string)
	{
		// Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
		// here we detect it and we remove it, basically it's the first 3 characters
		if (str_starts_with(bin2hex($string), 'efbbbf')) {
		   $string = substr($string, 3);
        }

		return $string;
	}

}
