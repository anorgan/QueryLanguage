<?php

namespace Anorgan;

class Query
{
    protected $_select;
    protected $_query;

    /**
     *
     * @return QueryLanguage\Select
     */
    public function getSelect()
    {
        if (null === $this->_select) {
            $this->_select = new QueryLanguage\Select;
        }

        return $this->_select;
    }

    /**
     *
     * @return QueryLanguage\Query
     */
    public function getQuery()
    {
        if (null === $this->_query) {
            $this->_query = new QueryLanguage\Query;
        }

        return $this->_query;
    }

    public function setSelect(QueryLanguage\Select $select)
    {
        $this->_select = $select;

        return $this;
    }

    public function setQuery(QueryLanguage\Query $query)
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
