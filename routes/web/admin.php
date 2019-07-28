<?php

/* ADMIN - AUTH | CHANGE PASSWORD */
$app->group('/admin', function () use ($container) {
    $this->get('/auth/{hash}', 'Web.Admin.AuthController:getAuth')
        ->setName('admin.auth');

    $this->group('/change', function () use ($container) {
        $this->get('/password', 'Web.Admin.AuthController:getChangePassword')
            ->setName('admin.change.password');
        $this->post('/password', 'Web.Admin.AuthController:postChangePassword')->add(new StudioAtual\Middleware\CsrfProtectMiddleware($container));
    })->add(new StudioAtual\Middleware\AuthMiddleware($container));
});
