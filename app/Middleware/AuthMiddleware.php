<?php

namespace StudioAtual\Middleware;

class AuthMiddleware extends Middleware
{
  public function __invoke($request, $response, $next)
  {
    if (!$this->container->auth->checkUserIsAuthenticated()) {
      return $response->withRedirect(
          $this->container->router->pathFor('home')
      );
    }
    if (!$this->container->auth->checkUserIsValid()) {
        return $response->withRedirect(
            $this->container->router->pathFor('home')
        );
    }
    return $next($request, $response);
  }
}
