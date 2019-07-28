<?php

namespace StudioAtual\Middleware;

class ApiMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $authorization = $request->getHeader("Authorization")[0];

        if (!$authorization) {
            return $response->withJson([ 'erro' => 'no-authorization' ], 401);
        }

        if (!$this->container->jwt->checkToken($authorization)) {
            return $response->withJson([ 'erro' => 'no-authorization' ], 401);
        }

        if (!$this->container->jwt->getUser()->admin) {
            return $response->withJson([ 'erro' => 'no-authorization' ], 401);
        }

        return $next($request, $response);
    }
}
