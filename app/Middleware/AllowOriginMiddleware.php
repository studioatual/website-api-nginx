<?php

namespace StudioAtual\Middleware;

class AllowOriginMiddleware extends Middleware
{
  public function __invoke($request, $response, $next)
  {
    return $next($request, $response)
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
  }
}
