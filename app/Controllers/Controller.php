<?php

namespace StudioAtual\Controllers;

class Controller
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if (isset($this->container->{$property})) {
            return $this->container->{$property};
        }
    }
}