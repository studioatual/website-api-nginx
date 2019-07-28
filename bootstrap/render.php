<?php

$container['flash'] = function ($container) {
    return new Slim\Flash\Messages;
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => ($container->settings['displayErrorDetails']) ? false : __DIR__ . '/../storage/cache'
    ]);
    $view->addExtension(new Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));
    $view->getEnvironment()->addGlobal('flash', $container->flash);
    return $view;
};
