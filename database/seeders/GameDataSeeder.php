<?php

namespace Database\Seeders;

use App\Services\Aspir\AspirService;
use Illuminate\Console\Command;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameDataSeeder extends Seeder
{
    private Connection $connection;

    public function __construct(Command $command, string $connection, public AspirService $aspirService)
    {
        $this->command = $command;
        $this->connection = DB::connection($connection);
    }

    public function run()
    {
        // Open the floodgates
        set_time_limit(0);
        Model::unguard();

        $this->connection->disableQueryLog();

        $this->connection->transaction(function () {
            // $this->connection->statement('SET FOREIGN_KEY_CHECKS=0;'); // MySQL
            $this->connection->statement("SET session_replication_role = 'replica';"); // Postgres

            // Run List is the same as what exists in the Aspir model's $data variable as keys
            $runList = array_keys($this->aspirService->data);

            foreach ($runList as $table) {
                $data = json_decode(file_get_contents($this->aspirService->getFilePath($table)), true);
                if (!empty($data)) {
                    $this->batchInsert($table, $data);
                }
            }

            // Cleanup
            // $this->connection->statement('SET FOREIGN_KEY_CHECKS=1;'); // MySQL
            $this->connection->statement("SET session_replication_role = 'origin';"); // Postgres
        });
    }

    private function batchInsert($table, $rows)
    {
        $keys = array_keys(reset($rows));

        $updateKeys = [];
        foreach (array_slice($keys, 1) as $field)
            // $updateKeys[] = '`' . $field . '`=VALUES(`' . $field . '`)'; // MySQL
            $updateKeys[] = '"' . $field . '"=EXCLUDED.' . $field; // Postgres

        // $keys = '(`' . implode('`,`', $keys) . '`)'; // MySQL
        $keys = '("' . implode('","', $keys) . '")'; // Postgres
        $updateKeys = implode(', ', $updateKeys);
        $isIgnored = !$updateKeys;
        $ignore = $isIgnored ? 'IGNORE' : ''; // MySQL
        $ignore = ''; // MySQL

        foreach (array_chunk($rows, 500) as $batchID => $data)
        {
            $this->command->comment('Inserting ' . count($data) . ' rows for ' . $table . ' (' . ($batchID + 1) . ')');

            $values = $pdo = [];
            foreach ($data as $row)
            {
                $values[] = '(' . str_pad('', count($row) * 2 - 1, '?,') . ')';

                // Cleanup value, if FALSE set to NULL
                foreach ($row as $value)
                    $pdo[] = $value === FALSE ? NULL : $value;
            }

            // $duplicateHandling = ' ON DUPLICATE KEY UPDATE ' . $updateKeys; // MySQL
            $duplicateHandling = ' ON CONFLICT (id) DO UPDATE SET ' . $updateKeys; // Postgres

            $this->connection->insert(
                'INSERT ' . $ignore . ' INTO ' . $table . ' ' . $keys .
                ' VALUES ' . implode(',', $values) .
                ($isIgnored ? ' ON CONFLICT (id) DO NOTHING' : $duplicateHandling)
                , $pdo);
        }
    }

}
