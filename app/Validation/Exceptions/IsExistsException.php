<?php

namespace StudioAtual\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class IsExistsException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} não Encontrado!',
        ]
    ];
}
