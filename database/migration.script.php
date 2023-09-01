<?php

/**
 *
 * supposed that we have all created tables (as migration ) in migrations/directory
 * each file have a convention name: {{date: 'YYYY-MM-DD-HH-MM-SS}.{tableName:snack_case}.table.php
 * example: the script name that creates users table should be like: 2023_06_26_02_12_00.users. table. php
 *
 */

require '../bootstrap.php';

use Illuminate\Database\Capsule\Manager;

$migrations = glob(__DIR__ . "/migration/*");


foreach ($migrations as $migration) {

    $exploded_migration = explode("/", $migration);
    $script_name = array_pop($exploded_migration);
    $table_name = explode(".", $script_name)[1];

    if (Manager::schema()->hasTable($table_name)) {

        echo "[SKIPPED] $table_name table already existed .\n";
        continue;
    }

    require "$migration";
    echo "$table_name has been created .\n";

}
