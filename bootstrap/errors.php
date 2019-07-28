<?php

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        if (strrpos($request->getUri()->getPath(), "/api") === false) {
            $container->view->render($response, 'errors/404.twig', [
                'title' => 'Error 404',
                'text' => 'Page Not Found!'
            ]);
            return $response->withStatus(404);
        }
        return $response->withJson(['error' => 'Page Not Found!'], 404);
    };
};

$container['phpErrorHandler'] = function ($container) {
    return function ($request, $response, $error) use ($container) {
        if (strrpos($request->getUri()->getPath(), "/api") === false) {
            $container->view->render($response, 'errors/500.twig', [
                'title' => 'Php Error',
                'message' => $error->getMessage(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $error->getTraceAsString()
            ]);
            return $response->withStatus(500);
        }
        return $response->withJson([
                'title' => 'Php Error',
                'message' => $error->getMessage(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $error->getTraceAsString()
            ], 500);
    };
};
