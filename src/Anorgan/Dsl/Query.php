<?php

namespace Anorgan\Dsl;

class Query implements \IteratorAggregate
{
    protected $_fields;

    public function __construct($data = null)
    {
        if (null !== $data) {
            foreach ($data as $item) {
                $this->add($item);
            }
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->getFields());
    }

    /**
     *
     * @param string $field
     *
     * @return \Anorgan\Dsl\Select
     */
    public function add($field)
    {
        $this->_fields[] = $this->_processField($field);

        return $this;
    }

    public function getFields()
    {
        return $this->_fields;
    }

    protected function _processField($field)
    {
        $operators = [
            '=',
            '!=',
            '>',
            '>=',
            '<',
            '<=',
        ];

        preg_match_all('/^(?P<field>[\w\.]+)\s?(?P<operator>'. implode('|', $operators) .')\s?(?P<value>.+)$/', $field, $matches, PREG_SET_ORDER);

        $matches = $matches[0];
        $value = $matches['value'];

        if (strpos($value, '[') === 0) {
            $value = json_decode($value);
        }

        return array(
            'field'     => $matches['field'],
            'operator'  => $matches['operator'],
            'value'     => $value,
        );
    }
}
