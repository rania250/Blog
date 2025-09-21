<?php
// Debug pour Vercel - index.php simple
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php_errors.log');

echo "🐘 PHP Debug - Starting...\n";
echo "Working directory: " . getcwd() . "\n";
echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] ?? 'Not set' . "\n";
echo "Script name: " . $_SERVER['SCRIPT_NAME'] ?? 'Not set' . "\n";

// Vérifier si Symfony autoload existe
$autoloadPath = dirname(__DIR__).'/vendor/autoload_runtime.php';
echo "Autoload path: $autoloadPath\n";
echo "Autoload exists: " . (file_exists($autoloadPath) ? 'YES' : 'NO') . "\n";

try {
    if (!file_exists($autoloadPath)) {
        throw new Exception("Autoload file not found: $autoloadPath");
    }
    
    require_once $autoloadPath;
    echo "✅ Autoload loaded successfully\n";
    
    // Test Kernel creation
    echo "✅ Kernel class found\n";
    
    $context = [
        'APP_ENV' => $_ENV['APP_ENV'] ?? 'prod',
        'APP_DEBUG' => false,
        'APP_CACHE_DIR' => '/tmp/cache',
        'APP_LOG_DIR' => '/tmp/logs'
    ];
    
    // Créer les dossiers
    @mkdir('/tmp/cache', 0777, true);
    @mkdir('/tmp/logs', 0777, true);
    
    echo "✅ Cache directories created\n";
    echo "Cache dir exists: " . (is_dir('/tmp/cache') ? 'YES' : 'NO') . "\n";
    echo "Cache dir writable: " . (is_writable('/tmp/cache') ? 'YES' : 'NO') . "\n";
    
    $kernelFactory = function() use ($context) {
        return new \App\Kernel($context['APP_ENV'], $context['APP_DEBUG']);
    };
    
    $kernel = $kernelFactory();
    echo "✅ Kernel created successfully\n";
    
    // Test simple response
    echo "🎉 Symfony application ready!\n";
    echo "Environment: " . $context['APP_ENV'] . "\n";
    echo "Debug: " . ($context['APP_DEBUG'] ? 'true' : 'false') . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "❌ File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "❌ Trace: " . $e->getTraceAsString() . "\n";
    http_response_code(500);
}
?>