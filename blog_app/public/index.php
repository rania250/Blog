<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    // Configuration spéciale pour Vercel - cache en /tmp
    if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || strpos(__DIR__, '/var/task') !== false) {
        $context['APP_CACHE_DIR'] = '/tmp/cache';
        $context['APP_LOG_DIR'] = '/tmp/logs';
        
        // Créer les dossiers cache
        @mkdir('/tmp/cache', 0777, true);
        @mkdir('/tmp/logs', 0777, true);
    }
    
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
