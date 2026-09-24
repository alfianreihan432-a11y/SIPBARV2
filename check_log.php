<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $content = file_get_contents($logFile);
    $lines = explode(PHP_EOL, $content);
    
    echo "=== LAST 20 LOG ENTRIES ===" . PHP_EOL;
    $lastLines = array_slice($lines, -20, 20);
    foreach ($lastLines as $line) {
        echo $line . PHP_EOL;
    }
    
    echo PHP_EOL . "=== SEARCHING FOR 'graduated student' ===" . PHP_EOL;
    $graduatedLines = array_filter($lines, function($line) {
        return strpos($line, 'graduated student') !== false;
    });
    echo "Found " . count($graduatedLines) . " entries" . PHP_EOL;
    foreach (array_slice($graduatedLines, -10, 10) as $line) {
        echo $line . PHP_EOL;
    }
    
    echo PHP_EOL . "=== SEARCHING FOR 'clearing classroom' ===" . PHP_EOL;
    $clearingLines = array_filter($lines, function($line) {
        return strpos($line, 'clearing classroom') !== false;
    });
    echo "Found " . count($clearingLines) . " entries" . PHP_EOL;
    foreach (array_slice($clearingLines, -10, 10) as $line) {
        echo $line . PHP_EOL;
    }
} else {
    echo "Log file not found" . PHP_EOL;
}
