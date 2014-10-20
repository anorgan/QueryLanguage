<?php

namespace Anorgan\QueryLanguage;

class Query implements \IteratorAggregate
{
    protected $_conditions;

    protected static $_instance;

    public function __construct($conditions = null)
    {
        if (null !== $conditions) {
            foreach ($conditions as $condition) {
                $this->add($condition);
            }
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->getConditions());
    }

    /**
     *
     * @param Condition|Composite $condition
     *
     * @return \Anorgan\QueryLanguage\Select
     */
    public function add($condition)
    {
        if (!($condition instanceof Composite) && !($condition instanceof Condition)) {
            throw new \InvalidArgumentException('Error adding condition, expecting composite or condition, got '. gettype($condition));
        }

        $this->_conditions[] = $condition;

        return $this;
    }

    /**
     * 
     * @return array
     */
    public function getConditions()
    {
        return (array) $this->_conditions;
    }

    /**
     *
     * @param Condition|Composite|array $conditions
     * 
     * @return Composite
     */
    public static function create($conditions = null)
    {
        return self::andX($conditions);
    }

    /**
     *
     * @param Condition|Composite|array $conditions
     * 
     * @return Composite
     */
    public static function andX($conditions = null)
    {
        return new Composite(Composite::TYPE_AND, $conditions);
    }

    /**
     *
     * @param Condition|Composite|array $conditions
     * 
     * @return Composite
     */
    public static function orX($conditions = null)
    {
        return new Composite(Composite::TYPE_OR, $conditions);
    }
}
