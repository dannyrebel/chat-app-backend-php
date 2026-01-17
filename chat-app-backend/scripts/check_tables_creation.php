<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/Database.php';

$db = db();

// List all tables in SQLite
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table';")->fetchAll(PDO::FETCH_COLUMN);

echo "Tables in the database:\n";
print_r($tables);
