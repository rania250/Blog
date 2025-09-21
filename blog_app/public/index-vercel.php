<?php

use Symfony\Component\DotEnv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

// Configuration spéciale pour Vercel
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])) {
    // Utiliser /tmp pour le cache sur Vercel
    $_ENV['APP_CACHE_DIR'] = '/tmp/cache';
    $_ENV['APP_LOG_DIR'] = '/tmp/logs';
    
    // Créer les dossiers si nécessaires
    @mkdir($_ENV['APP_CACHE_DIR'], 0777, true);
    @mkdir($_ENV['APP_LOG_DIR'], 0777, true);
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);