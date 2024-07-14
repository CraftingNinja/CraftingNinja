<?php

namespace App\Services\Aspir;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

abstract class AspirAbstract
{
    // Common functionality that all AspirServices have

    public array $data = [
        'achievement'               => [],
        'location'                  => [],
        'node'                      => [],
        'item_node'                 => [],
        'fishing'                   => [],
        'fishing_item'              => [],
        'mob'                       => [],
        'item_mob'                  => [],
        'npc'                       => [],
        'npc_quest'                 => [],
        'shop'                      => [],
        'npc_shop'                  => [],
        'quest'                     => [],
        'quest_reward'              => [],
        'quest_required'            => [],
        'instance'                  => [],
        'instance_item'             => [],
        'instance_mob'              => [],
        'job'                       => [],
        'job_category'              => [],
        'job_job_category'          => [],
        'venture'                   => [],
        'item_venture'              => [],
        'leve'                      => [],
        'leve_reward'               => [],
        'leve_required'             => [],
        'item_category'             => [],
        'item'                      => [],
        'item_attribute'            => [],
        'item_shop'                 => [],
        'recipe'                    => [],
        'recipe_reagents'           => [],
        'notebook'                  => [],
        'notebook_recipe'           => [],
        'notebookdivision'          => [],
        'notebook_notebookdivision' => [],
        'notebookdivision_category' => [],
    ];

    public string $assetsDir = "";
    public string $dataDir = "";
    public string $compiledDir = "";
    public Repository $cache;

    public function setCache(string $slug): void
    {
        $aspirStore = config('cache.stores.aspir');

        config([
            'cache.stores.aspir' => [
                ...$aspirStore,
                'path' => $aspirStore['path'] . '/' . $slug,
                'lock_path' => $aspirStore['lock_path'] . '/' . $slug,
            ]
        ]);

        $this->cache = Cache::store('aspir');
    }

    public function clearCache(): void
    {
        $this->cache->clear();
    }

    public function setData(string $table, array $row, ?int $id = null, bool $mergeOnly = false): int
    {
        // If id is null, use the length of the existing data, or check in the $row for it
        $id = $id ?: ($row['id'] ?? count($this->data[$table]) + 1);

        if (isset($this->data[$table][$id])) {
            $this->data[$table][$id] = array_merge($this->data[$table][$id], $row);
        } elseif ( ! $mergeOnly) {
            $this->data[$table][$id] = $row;
        }

        return $id;
    }

    public function saveData(): void
    {
        $this->command->comment('Saving Data');

        foreach ($this->data as $filename => $data) {
            $this->writeToJSON($filename, $data);
        }
    }

    public function writeToJSON(string $filename, array $list): void
    {
        if (empty($list)) {
            $this->command->comment('No data for ' . $filename);
        } else {
            $this->command->info('Saving ' . count($list) . ' records to ' . $filename . '.json');
        }

        file_put_contents(
            $this->getFilePath($filename),
            json_encode($list, JSON_PRETTY_PRINT)
        );
    }

    public function getFilePath($filename): string
    {
        return $this->compiledDir . $filename . '.json';
    }
}
