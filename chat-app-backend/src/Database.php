<?php

use Medoo\Medoo;

function db(): Medoo
{
    static $database = null;

    if ($database === null) {
        $database = new Medoo([
            'type' => 'sqlite',
            'database' => __DIR__ . '/../storage/database.sqlite',
            'error' => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    return $database;
}
