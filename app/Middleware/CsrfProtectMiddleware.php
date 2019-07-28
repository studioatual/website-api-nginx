<?php

namespace StudioAtual\Middleware;

class CsrfProtectMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if ($request->getAttribute('csrf_status') === false) {
            return $this->container->view->render($response, 'errors/404.twig', [
                'title' => 'CSRF Failed!',
                'text' => 'CSRF Failed!'
            ]);
        }

        return $next($request, $response);
    }
}