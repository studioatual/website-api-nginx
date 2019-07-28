<?php

namespace StudioAtual\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use StudioAtual\Models\User;

class IsExists extends AbstractRule
{
    protected $class;
    protected $nullable;

    public function __construct($class, $nullable = false)
    {
        $this->class = $class;
        $this->nullable = $nullable;
    }

    public function validate($input)
    {
        if ($input == null && $this->nullable) {
            return true;
        }
        return $this->class->find($input);
    }
}
