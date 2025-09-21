<?php

// Debug info first
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple test first
if (isset($_GET['test'])) {
    echo "✅ PHP is working on Vercel!\n";
    echo "Working directory: " . getcwd() . "\n";
    echo "Files in directory: " . implode(', ', scandir('.')) . "\n";
    exit;
}

use App\Kernel;

// Set up cache directories for Vercel
$tmpCache = '/tmp/cache';
$tmpLogs = '/tmp/logs';
@mkdir($tmpCache, 0777, true);
@mkdir($tmpLogs, 0777, true);

// Override Symfony cache paths
$_ENV['APP_CACHE_DIR'] = $tmpCache;
$_ENV['APP_LOG_DIR'] = $tmpLogs;

try {
    require_once dirname(__DIR__).'/vendor/autoload_runtime.php';
    
    return function (array $context) use ($tmpCache, $tmpLogs) {
        // Force cache directories
        $context['APP_CACHE_DIR'] = $tmpCache;
        $context['APP_LOG_DIR'] = $tmpLogs;
        
        return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
    };
    
} catch (Throwable $e) {
    // Detailed error for debugging
    echo "❌ Symfony Error: " . $e->getMessage() . "\n";
    echo "❌ File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "❌ Stack trace: " . $e->getTraceAsString() . "\n";
    http_response_code(500);
}
