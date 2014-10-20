<?php

namespace Anorgan\QueryLanguage;

use Countable;

class Composite implements Countable
{
    const TYPE_AND = 'AND';

    const TYPE_OR  = 'OR';

    /**
     * The instance type of composite expression.
     *
     * @var string
     */
    private $type;

    /**
     * Each expression part of the composite expression.
     *
     * @var array
     */
    private $parts = array();

    /**
     *
     * @var Composite
     */
    private $parent;

    /**
     * Constructor.
     *
     * @param string $type  Instance type of composite expression.
     * @param array  $parts Composition of expressions to be joined on composite expression.
     * @param Composite $parent Composite this is child of
     */
    public function __construct($type, $parts = null, Composite $parent = null)
    {
        $this->type = $type;
        $this->parent = $parent;

        if (null !== $parts) {
            if (is_array($parts)) {
                $this->addParts($parts);
            } else {
                $this->add($parts);
            }
        }
    }

    /**
     * Returns parent if any, or self if no parent
     *
     * @return \Anorgan\QueryLanguage\Composite
     */
    public function back()
    {
        if (null === $this->parent) {
            return $this;
        }

        return $this->parent;
    }

    /**
     * 
     * @return boolean
     */
    public function hasParent()
    {
        return null !== $this->parent;
    }

    /**
     * Returns leaf parent or self if no parents
     *
     * @return \Anorgan\QueryLanguage\Composite
     */
    public function end()
    {
        if (!$this->hasParent()) {
            return $this;
        }

        $parent = $this;
        while ($parent->hasParent()) {
            $parent = $parent->back();
        }

        return $parent;
    }

    /**
     *
     * @param array $parts
     *
     * @return \Anorgan\QueryLanguage\Composite
     */
    public function addParts(array $parts = array())
    {
        foreach ((array) $parts as $part) {
            $this->add($part);
        }

        return $this;
    }

    /**
     *
     * @param Condition|Composite $data
     *
     * @return \Anorgan\QueryLanguage\Composite
     * @throws \InvalidArgumentException
     */
    public function add($data)
    {
        if (!($data instanceof Composite) && !($data instanceof Condition)) {
            throw new \InvalidArgumentException('Error adding data, expecting composite or condition, got '. gettype($data));
        }

        if ($data instanceof \Anorgan\QueryLanguage\Composite && $data->count() == 0) {
            return $this;
        }

        $this->parts[] = $data;

        return $this;
    }

    /**
     *
     * @param Condition|Composite $data
     * 
     * @return \Anorgan\QueryLanguage\Composite
     */
    public function andX($data = null)
    {
        $composite = new Composite(self::TYPE_AND, $data, $this);
        $this->add($composite);

        return $composite;
    }

    /**
     *
     * @param Condition|Composite $data
     *
     * @return \Anorgan\QueryLanguage\Composite
     */
    public function orX($data = null)
    {
        $composite = new Composite(self::TYPE_OR, $data, $this);
        $this->add($composite);

        return $composite;
    }

    /**
     * Retrieves the amount of expressions on composite expression.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->parts);
    }

    /**
     * Retrieves the string representation of this composite expression.
     *
     * @return string
     */
    public function __toString()
    {
        $cnt = count($this->parts);
        if ($cnt === 0) {
            return '';
        }

        if ($cnt === 1) {
            return (string) $this->parts[0];
        }

        return '(' . implode(') ' . $this->type . ' (', $this->parts) . ')';
    }

    /**
     * 
     * @return mixed
     */
    public function toArray()
    {
        $cnt = count($this->parts);
        if ($cnt === 0) {
            return array();
        }

        if ($cnt === 1) {
            return $this->parts[0];
        }

        return $this->parts;
    }

    /**
     * Returns the type of this composite expression (AND/OR).
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}