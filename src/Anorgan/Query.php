<?php

namespace Anorgan;

class Query
{
    protected $_select;
    protected $_query;

    /**
     *
     * @return Dsl\Select
     */
    public function getSelect()
    {
        if (null === $this->_select) {
            $this->_select = new Dsl\Select;
        }

        return $this->_select;
    }

    /**
     *
     * @return Dsl\Query
     */
    public function getQuery()
    {
        if (null === $this->_query) {
            $this->_query = new Dsl\Query;
        }

        return $this->_query;
    }

    public function setSelect(Dsl\Select $select)
    {
        $this->_select = $select;

        return $this;
    }

    public function setQuery(Dsl\Query $query)
    {
        $this->_query = $query;

        return $this;
    }

    public function addSelect($field)
    {
        $this->getSelect()->add($field);

        return $this;
    }

    public function addQuery($query)
    {
        $this->getQuery()->add($query);

        return $this;
    }
}
