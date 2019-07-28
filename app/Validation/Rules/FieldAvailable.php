<?php

namespace StudioAtual\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class FieldAvailable extends AbstractRule
{
    protected $class;
    protected $field;
    protected $id;   
    protected $id_field; 

    public function __construct($class, $field, $id = null, $id_field = 'id')
    {
        $this->class = $class;
        $this->field = $field;
        $this->id = $id;
        $this->id_field = $id_field;
    }

    public function validate($input)
    {
        if ($this->id) {
            return $this->class->where([
                [$this->field, $input],
                [$this->id_field, '!=', $this->id]
            ])->count() === 0;
        }
        return $this->class->where($this->field, $input)->count() === 0;
    }
}