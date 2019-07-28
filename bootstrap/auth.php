<?php

$container['auth'] = function ($container) {
    return new StudioAtual\Auth\Auth(
        StudioAtual\Models\User::class,
        'user'
    );
};

$container['jwt'] = function ($container) {
    return new StudioAtual\Auth\Jwt(
        $container->settings['domain'],
        $container->settings['app']['key'],
        ['email', 'username'],
        StudioAtual\Models\User::class,
        'user'
    );
};
