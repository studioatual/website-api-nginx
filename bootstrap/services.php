<?php

$container['csrf'] = function ($container) {
    return (new Slim\Csrf\Guard)
        ->setFailureCallable(function ($request, $response, $next) {
            $request = $request->withAttribute('csrf_status', false);
            return $next($request, $response);
        });
};

$container['validator'] = function ($container) {
    return new StudioAtual\Validation\Validator;
};

$container['logger'] = function($container) {
    $logger = new Monolog\Logger($container->settings['app']['name']);
    $file_handler = new Monolog\Handler\StreamHandler(__DIR__ . '/../storage/logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['media'] = function ($container) {
    return __DIR__ . '/../public/db';
};
