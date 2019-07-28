<?php

namespace StudioAtual\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class FieldAvailableException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} já está em uso.',
        ]
    ];
}