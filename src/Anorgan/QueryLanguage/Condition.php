<?php

namespace Anorgan\QueryLanguage;

class Condition
{
    protected $field;
    protected $operator;
    protected $value;

    public function __construct($field, $operator, $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $this->normalizeValue($value);
    }

    public function normalizeValue($value)
    {
        // Array, JSON notation
        if (is_string($value) && preg_match('/^\[(.*),?\]$/', $value)) {
            $normalized = json_decode($value, true);
            if (is_array($normalized)) {
                $value = $normalized;
            }
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
