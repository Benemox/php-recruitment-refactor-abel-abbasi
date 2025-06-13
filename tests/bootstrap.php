<?php

declare(strict_types=1);

// Carga el autoloader de Composer
require dirname(__DIR__) . '/vendor/autoload.php';

$dotenvPath = dirname(__DIR__) . '/.env.test';
if (file_exists($dotenvPath)) {
    $_ENV = array_merge($_ENV, parse_ini_file($dotenvPath));
}
