<?php

namespace App\Services\Aspir;

interface AspirService
{
    public function collectData(): void;

    public function collectAssets(): void;

    public function setCache(string $slug): void;

    public function clearCache(): void;

    public function setData(string $table, array $row, ?int $id, bool $mergeOnly): int;

    public function saveData(): void;

    public function writeToJSON(string $filename, array $list): void;
}
