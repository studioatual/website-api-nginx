<?php

$app->add(new StudioAtual\Middleware\CsrfFieldsMiddleware($container));
$app->add(new StudioAtual\Middleware\ValidationErrorsMiddleware($container));
$app->add(new StudioAtual\Middleware\OldInputMiddleware($container));
$app->add($container->csrf);

$app->add(new StudioAtual\Middleware\AllowOriginMiddleware($container));
