<?php

declare(strict_types=1);

// Autoload
require __DIR__ . '/../vendor/autoload.php';

$seedPath   = __DIR__ . '/resources/sqlite_seed.sql';
$template = __DIR__ . '/resources/argondb.template.sqlite';
$target   = __DIR__ . '/resources/argondb.sqlite';

if (!file_exists($template)) {
    echo "Creating SQLite template DB...\n";
    $pdo = new PDO('sqlite:' . $template);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!is_readable($seedPath)) {
        throw new RuntimeException("Seed file missing or unreadable: $seedPath");
    }

    $sql = (string) file_get_contents($seedPath);
    $pdo->exec($sql);
    echo "SQLite template DB created and seeded.\n";
}

// Always copy to working DB before test run
copy($template, $target);
