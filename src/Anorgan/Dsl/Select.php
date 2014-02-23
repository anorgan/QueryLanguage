<?php

namespace Anorgan\Dsl;

class Select implements \IteratorAggregate
{
    protected $_fields;
    protected $_relations;

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
     * @param string $item
     *
     * @return \Anorgan\Dsl\Select
     */
    public function add($item)
    {
        $this->_relations = null;
        $this->_fields[] = $item;

        return $this;
    }

    public function getFields()
    {
        return $this->_fields;
    }

    public function getRelations()
    {
        if (null === $this->_relations) {
            $this->_relations = array();
            foreach ($this->getFields() as $field) {
                $this->_processField($field);
            }
        }

        return $this->_relations;
    }

    /**
     *
     * @param array $data
     *
     * @return \Anorgan\Dsl\Select
     */
    public function addRelation(array $data)
    {
        $this->_relations[$data['name']] = $data;

        return $this;
    }

    protected function _processField($field)
    {
        if (strpos($field, '.') === false) {
            return;
        }

        $parts = explode('.', $field);
        array_pop($parts);

        // Add each part as a relation
        $cnt = count($parts);
        $path = array();
        for ($i = 0; $i < $cnt;) {
            $path[] = $parts[$i++];

            $this->addRelation(array(
                'name'  => implode('.', $path),
                'alias' => implode('_', $path)
            ));
        }
    }
}
