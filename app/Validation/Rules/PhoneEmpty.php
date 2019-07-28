<?php

namespace StudioAtual\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class PhoneEmpty extends AbstractRule
{
    protected $other;

    public function __construct($other)
    {
        $this->other = $other;
    }

    public function validate($input)
    {
        return ($input != "" || $this->other != "");
    }
}