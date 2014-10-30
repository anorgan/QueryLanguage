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
    
    public function __toString()
    {
        $cnt = count($this->getConditions());
        if ($cnt === 0) {
            return '';
        }

        if ($cnt === 1) {
            return (string) $this->getConditions()[0];
        }

        $stringParts = array();
        foreach ($this->getConditions() as $condition) {
            if (!empty($stringParts)) {
                $stringParts[] = $condition->getType();
            }
            $stringParts[] = (string) $condition;
        }

        return implode(' ', $stringParts);
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
