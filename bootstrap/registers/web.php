<?php

// =======| ADMIN |========================================== //
$container['Web.Admin.AuthController'] = function ($container) {
    return new StudioAtual\Controllers\Web\Admin\AuthController($container);
};


// =======| HOME |========================================== //
$container['Web.HomeController'] = function ($container) {
    return new StudioAtual\Controllers\Web\HomeController($container);
};
