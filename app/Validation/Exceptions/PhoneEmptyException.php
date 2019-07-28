<?php

namespace StudioAtual\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class PhoneEmptyException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'É necessário preencher um Telefone!',
        ]
    ];
}