<?php

$app->group('/api/panel/admin', function () use ($container) {
    /* CONFIG */
    $this->group('/config', function() {
        $this->put('[/]', 'Api.Panel.Admin.ConfigController:update');
        $this->get('[/]', 'Api.Panel.Admin.ConfigController:index');
    });

    /* USERS */
    $this->group('/users', function () {
        $this->group('/{id}', function () {
            $this->delete('[/]', 'Api.Panel.Admin.UsersController:destroy');
            $this->get('[/]', 'Api.Panel.Admin.UsersController:show');
            $this->put('[/]', 'Api.Panel.Admin.UsersController:update');
        });
        $this->get('[/]', 'Api.Panel.Admin.UsersController:index');
        $this->post('[/]', 'Api.Panel.Admin.UsersController:store');
    });

    /* CONTACTS */
    $this->group('/contacts', function() {
        $this->group('/{id}', function () {
            $this->delete('[/]', 'Api.Panel.Admin.ContactsController:destroy');
            $this->get('[/]', 'Api.Panel.Admin.ContactsController:show');
        });
        $this->get('[/]', 'Api.Panel.Admin.ContactsController:index');
    });
})->add(new StudioAtual\Middleware\ApiMiddleware($container));
