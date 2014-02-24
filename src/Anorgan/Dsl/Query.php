<?php

namespace Anorgan\Dsl;

class Query implements \IteratorAggregate
{
    protected $_conditions;

    protected static $_instance;

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
        return new \ArrayIterator($this->getConditions());
    }

    /**
     *
     * @param string $condition
     *
     * @return \Anorgan\Dsl\Select
     */
    public function add($condition)
    {
        $this->_conditions[] = $condition instanceof Composite ? $condition: $this->_processCondition($condition);

        return $this;
    }

    public function getConditions()
    {
        return $this->_conditions;
    }

    /**
     *
     * @return Query
     */
    protected static function _getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     *
     * @param type $data
     * @return Composite
     */
    public static function create($data = null)
    {
        return self::andX($data);
    }

    /**
     *
     * @param type $data
     * @return Composite
     */
    public static function andX($data = null)
    {
        return new Composite(Composite::TYPE_AND, $data);
    }

    /**
     *
     * @param type $data
     * @return Compositeu
     */
    public static function orX($data = null)
    {
        return new Composite(Composite::TYPE_OR, $data);
    }

    protected function _processCondition($field)
    {
        $operators = [
            '=',
            '!=',
            '>=',
            '<=',
            '<',
            '>',
        ];

        $operators = array_map('preg_quote', $operators);

        $regex = '/^(?P<field>\D[\w\.]+)\s?(?P<operator>(?|'. implode('|', $operators) .'))\s?(?P<quotes>["\']{0,1})(?P<value>[^\g{quotes}]+)\g{quotes}$/';
        preg_match_all($regex, $field, $matches, PREG_SET_ORDER);
print_r($regex);
print_r($matches);
        $match = array_map('trim', $matches[0]);

        $condition = new Condition($match['field'], $match['operator'], $match['value']);

        return $condition;
    }
}
