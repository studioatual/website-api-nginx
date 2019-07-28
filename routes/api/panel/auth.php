<?php

$app->group('/api/panel', function () use ($container) {
    /* LOGIN */
    $this->post('/login', 'Api.Panel.AuthController:login');

    /* RECOVER PASSWORD */
    $this->post('/recover/password', 'Api.Panel.AuthController:recoverPassword');

    /* AUTOLOGIN */
    $this->get('/autologin', 'Api.Panel.AuthController:autologin')->add(new StudioAtual\Middleware\ApiMiddleware($container));
});
