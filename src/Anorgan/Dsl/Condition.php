<?php

namespace Anorgan\Dsl;

class Condition
{
    protected $field;
    protected $operator;
    protected $value;

    public function __construct($field, $operator, $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function normalizeValue($value)
    {
        if (preg_match('/^\[(.*),?\]$/', $value, $parts)) {
            print_r($parts);
        }
        return $value;
    }

    public function denormalizeValue($value)
    {
        if (is_array($value)) {
            return json_encode($value);
        }

        return $value;
    }

    public function toArray()
    {
        return array(
            'field' => $this->field,
            'operator' => $this->operator,
            'value' => $this->value,

        );
    }

    public function __toString()
    {
        return $this->field . $this->operator . $this->denormalizeValue($this->value);
    }
}
