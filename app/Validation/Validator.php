<?php

namespace StudioAtual\Validation;

use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    protected $errors;

    public function validate($params, array $rules)
    {
        foreach ($rules as $field => $rule) {
            try {
                $value = (isset($params[$field])) ? $params[$field] : "";
                $rule->assert($value);
            } catch (NestedValidationException $e) {
                $e->findMessages([
                    'email' => 'E-mail inválido!',
                    'notEmpty' => 'Preencha o campo {{name}}!',
                    'noWhitespace' => '{{name}} não é permitido espaços!',
                    'equals' => '{{name}} não confere!',
                    'cpf' => 'CPF Inválido!',
                    'length' => '{{name}} deve conter entre {{minValue}} e {{maxValue}} caracteres!'
                ]);
                $this->errors[$field] = $e->getMessages();
            }
        }
        $_SESSION['errors'] = $this->errors;
        return $this;
    }

    public function failed()
    {
        return !empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
