<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();

$app = new Slim\App(include __DIR__ . '/config.php');
$container = $app->getContainer();

require __DIR__ . '/database.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/services.php';
require __DIR__ . '/mail.php';
require __DIR__ . '/render.php';
require __DIR__ . '/errors.php';
require __DIR__ . '/middleware.php';

require __DIR__ . '/registers/api.php';
require __DIR__ . '/registers/web.php';

require __DIR__ . '/../routes/api.php';
require __DIR__ . '/../routes/web.php';
