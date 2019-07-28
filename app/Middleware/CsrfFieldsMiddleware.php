<?php

namespace StudioAtual\Middleware;

class CsrfFieldsMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $nameKey = $this->container->csrf->getTokenNameKey();
        $valueKey = $this->container->csrf->getTokenValueKey();
        $name = $this->container->csrf->getTokenName();
        $value = $this->container->csrf->getTokenValue();

        $fields = '<input type="hidden" name="' . $nameKey . '" value="' . $name . '">
        <input type="hidden" name="' . $valueKey . '" value="' . $value . '">';

        $this->container->view->getEnvironment()->addGlobal('csrf', $fields);

        return $next($request, $response);
    }
}