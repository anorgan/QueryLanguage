<?php

namespace Anorgan\Dsl\Parser;

use Anorgan\Dsl\Condition;
use Anorgan\Dsl\Query;
use Anorgan\Dsl\Select;
use Doctrine\Common\Lexer\AbstractLexer;

class QueryParser
{
    /**
     *
     * @var SelectLexer
     */
    protected $lexer;

    /**
     *
     * @param AbstractLexer $lexer
     */
    public function __construct(AbstractLexer $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     *
     * @param string $input
     *
     * @return Select
     */
    public function parse($input)
    {
        $this->lexer->setInput($input);
        $this->lexer->moveNext();

        $query = new Query;

        while (null !== ($token = $this->lexer->lookahead)) {

            $peek = $this->lexer->glimpse();
            switch ($peek['type']) {
                case QueryLexer::T_NOT:
                case QueryLexer::T_GREATER:
                case QueryLexer::T_LOWER:
                case QueryLexer::T_EQUAL:
                    echo 'Adding condition'. PHP_EOL;
                    $this->Condition($query);

                    $this->lexer->moveNext();

                    print_r($this->lexer->token);

                    break;

                case QueryLexer::T_AND:
                    echo 'Adding composite AND'. PHP_EOL;
                    $this->CompositeAnd($query);

                    $this->lexer->moveNext();

                    break;

                case QueryLexer::T_OR:
                    echo 'Adding composite OR'. PHP_EOL;
                    $this->CompositeOr($query);

                    $this->lexer->moveNext();

                    break;

                default:
                    $this->lexer->moveNext();

            }
        }

        return $query;
    }

    protected function Condition(Query $query)
    {
        // Field
        $field = $this->lexer->lookahead['value'];

        // Operator
        $this->lexer->moveNext();

        switch ($this->lexer->token['value']) {
            // !=
            case QueryLexer::T_NOT && $this->lexer->isNextToken(QueryLexer::T_EQUAL):
                $this->lexer->moveNext();
                $operator = '!=';

                break;

            // >= or <=
            case $this->lexer->isNextToken(QueryLexer::T_GREATER):
            case $this->lexer->isNextToken(QueryLexer::T_LOWER):
                $operator = $this->lexer->lookahead['value'];
                $this->lexer->moveNext();

                if ($this->lexer->isNextToken(QueryLexer::T_EQUAL)) {
                    $operator .= $this->lexer->lookahead['value'];
                    $this->lexer->moveNext();
                }

                break;

            default:
                $operator = $this->lexer->lookahead['value'];

        }

        // Value
        $this->lexer->moveNext();

        $value = $this->Value();

        $query->add(new Condition($field, $operator, $value));
    }

    protected function CompositeAnd(Query $query)
    {
        $query->andX();
    }

    protected function CompositeOr(Query $query)
    {
        $query->orX();
    }

    protected function Value()
    {
        $value = array($this->lexer->lookahead['value']);

        $breakWhile = false;
        while (true) {
            switch (true) {
                case $this->lexer->isNextToken(QueryLexer::T_SINGLE_QUOTE):
                case $this->lexer->isNextToken(QueryLexer::T_DOUBLE_QUOTE):
                    $quote = $this->lexer->lookahead;
                    $this->lexer->moveNext();
                    $breakWhile = true;

                    break;

                case $this->lexer->isNextToken(QueryLexer::T_STRING):
                case $this->lexer->isNextToken(QueryLexer::T_INTEGER):
                case $this->lexer->isNextToken(QueryLexer::T_FLOAT):
                    $this->lexer->moveNext();
                    $value[] = $this->lexer->lookahead['value'];

                    break;

                default:
                    $breakWhile = true;

                    break;
            }

            if ($breakWhile) {
                break;
            }
        }

        return implode(' ', $value);
    }
}