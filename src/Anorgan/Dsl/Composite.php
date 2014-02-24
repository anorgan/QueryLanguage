<?php

namespace Anorgan\Dsl;

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

    private $parent;

    /**
     * Constructor.
     *
     * @param string $type  Instance type of composite expression.
     * @param array  $parts Composition of expressions to be joined on composite expression.
     */
    public function __construct($type, $parts = array(), Composite $parent = null)
    {
        $this->type = $type;
        $this->parent = $parent;

        $this->addParts((array) $parts);
    }

    public function back()
    {
        if (null === $this->parent) {
            return $this;
        }

        return $this->parent;
    }

    public function hasParent()
    {
        return null !== $this->parent;
    }

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
     * @return \Anorgan\Dsl\Composite
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
     * @param self $data
     * @return \Anorgan\Dsl\Composite
     */
    public function add($data)
    {
        if ( ! empty($data) || ($data instanceof self && $data->count() > 0)) {
            $this->parts[] = $data;
        }

        return $this;
    }

    /**
     *
     * @param type $data
     * @return \Anorgan\Dsl\Composite
     */
    public function andX($data = null)
    {
        $composite = new Composite(self::TYPE_AND, $data, $this);
        $this->add($composite);

        return $composite;
    }

    /**
     *
     * @param type $data
     * @return \Anorgan\Dsl\Composite
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
     * Returns the type of this composite expression (AND/OR).
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}