<?php

$container['Api.Panel.AuthController'] = function ($container) {
    return new StudioAtual\Controllers\Api\Panel\AuthController($container);
};
$container['Api.Panel.Admin.ConfigController'] = function ($container) {
    return new StudioAtual\Controllers\Api\Panel\Admin\ConfigController($container);
};
$container['Api.Panel.Admin.UsersController'] = function ($container) {
    return new StudioAtual\Controllers\Api\Panel\Admin\UsersController($container);
};
$container['Api.Panel.Admin.ContactsController'] = function ($container) {
    return new StudioAtual\Controllers\Api\Panel\Admin\ContactsController($container);
};
