<?php

namespace Anorgan\QueryLanguage\Parser;

use Anorgan\QueryLanguage\Condition;
use Anorgan\QueryLanguage\Query;
use Anorgan\QueryLanguage\Select;
use Doctrine\Common\Lexer\AbstractLexer;
use Exception;

/**
 * <EBNF>
 * Query                       ::= ConditionalTerm {"OR" ConditionalTerm}*
 * ConditionalTerm             ::= ConditionalPrimary {"AND" ConditionalPrimary}*
 * ConditionalPrimary          ::= ComparisonExpression | "(" Query ")"
 * ComparisonExpression        ::= Field ComparisonOperator Value
 * ComparisonOperator          ::= "=" | ":" | "<" | "<=" | ">" | ">=" | "!="
 * Field                       ::= Literal
 * Value                       ::= Literal | "\"" Literal "\""
 * Literal                     ::= string | char | integer | float | boolean
 */
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

        $query = new Query;

        $this->Query($query);

        return $query;
    }

    /**
     *
     * @param int $token
     * @throws Exception
     */
    public function match($token)
    {
        $lookaheadType = $this->lexer->lookahead['type'];

        // short-circuit on first condition, usually types match
        if ($lookaheadType !== $token && $token !== QueryLexer::T_STRING && $lookaheadType <= QueryLexer::T_STRING) {
            throw new Exception('Error matching token, expecting '. $this->lexer->getLiteral($token));
        }

        $this->lexer->moveNext();
    }

    /**
     * Query                       ::= ConditionalTerm {"OR" ConditionalTerm}*
     * Create OR parts
     */
    public function Query(Query $query)
    {
        $this->lexer->moveNext();

        $query->add($query->orX($this->ConditionalTerm($query)));
        while ($this->lexer->isNextToken(QueryLexer::T_OR)) {
            $this->match(QueryLexer::T_OR);

            $query->add($query->orX($this->ConditionalTerm($query)));
        }

        return $query;
    }

    /**
     * ConditionalTerm             ::= ConditionalPrimary {"AND" ConditionalPrimary}*
     * Create AND parts
     */
    public function ConditionalTerm(Query $query)
    {
        $composite = $query->andX($this->ConditionalPrimary($query));
        while ($this->lexer->isNextToken(QueryLexer::T_AND)) {
            $this->match(QueryLexer::T_AND);

            $composite->add($this->ConditionalPrimary($query));
        }

        return $composite;
    }

    /**
     * ConditionalPrimary          ::= ComparisonExpression | "(" Query ")"
     * Create condition or composite (another Query)
     */
    public function ConditionalPrimary(Query $query)
    {
        if (!$this->lexer->isNextToken(QueryLexer::T_OPEN_PARENTHESIS)) {
            return $this->ComparisonExpression();
        }

        $this->match(QueryLexer::T_OPEN_PARENTHESIS);
        $query = $this->Query($query);
        $this->match(QueryLexer::T_CLOSE_PARENTHESIS);

        return $query;
    }

    /**
     * ComparisonExpression        ::= Field ComparisonOperator Value
     * Create condition
     */
    public function ComparisonExpression()
    {
        $field      = $this->Field();
        $operator   = $this->ComparisonOperator();
        $value      = $this->Value();

        return new Condition($field, $operator, $value);
    }

    /**
     * Field                        ::= Literal [ ("." Literal)* ]
     *
     * @return string
     */
    public function Field()
    {
        $this->match(QueryLexer::T_STRING);

        $field = $this->lexer->token['value'];

        while ($this->lexer->isNextToken(QueryLexer::T_DOT)) {
            $this->match(QueryLexer::T_DOT);
            $this->match(QueryLexer::T_STRING);
            $field .= '.'. $this->lexer->token['value'];
        }

        return $field;
    }

    /**
     * ComparisonOperator ::= "=" | ":" | "<" | "<=" | ">" | ">=" | "!="
     *
     * @return string
     */
    public function ComparisonOperator()
    {
        switch ($this->lexer->lookahead['value']) {
            case '=':
                $this->match(QueryLexer::T_EQUAL);

                return '=';

            case '<':
                $this->match(QueryLexer::T_LOWER);
                $operator = '<';

                if ($this->lexer->isNextToken(QueryLexer::T_EQUAL)) {
                    $this->match(QueryLexer::T_EQUAL);
                    $operator .= '=';
                } else if ($this->lexer->isNextToken(QueryLexer::T_GREATER)) {
                    $this->match(QueryLexer::T_GREATER);
                    $operator .= '>';
                }

                return $operator;

            case '>':
                $this->match(QueryLexer::T_GREATER);
                $operator = '>';

                if ($this->lexer->isNextToken(QueryLexer::T_EQUAL)) {
                    $this->match(QueryLexer::T_EQUAL);
                    $operator .= '=';
                }

                return $operator;

            case '!':
                $this->match(QueryLexer::T_NOT);
                $this->match(QueryLexer::T_EQUAL);

                return '!=';

            default:
                throw new Exception('Error matching comparison opeartor, expecting one of: =, :, <, <=, >, >=, !=, got '. $this->lexer->lookahead['value']);
        }
    }

    /**
     * Value                       ::= Literal | "\"" Literal "\""
     * Get literal
     */
    public function Value()
    {
        $values = [];

        if ($this->lexer->isNextToken(QueryLexer::T_DOUBLE_QUOTE)) {
            $this->lexer->moveNext();

            $startPosition = $this->lexer->token['position'];
            while (true) {
                if ($this->lexer->isNextToken(QueryLexer::T_DOUBLE_QUOTE) && $this->lexer->token['value'] !== '\\') {
                    // Not escaped quote, stop
                    $this->lexer->moveNext();
                    $endPosition = $this->lexer->token['position'];
                    break;
                }

                $this->lexer->moveNext();
            }

            $values[] = str_replace('\"', '"', substr($this->lexer->getInputUntilPosition($endPosition), $startPosition + 1));

        } else {
            $values[] = $this->Literal();
        }

        return implode(' ', $values);
    }

    /**
     * Literal                     ::= string | char | integer | float | boolean
     * Get terminal
     */
    public function Literal()
    {
        switch ($this->lexer->lookahead['type']) {
            case QueryLexer::T_STRING:
                $this->match(QueryLexer::T_STRING);
                return $this->lexer->token['value'];

            case QueryLexer::T_INTEGER:
            case QueryLexer::T_FLOAT:
                $this->match(
                    $this->lexer->isNextToken(QueryLexer::T_INTEGER) ? QueryLexer::T_INTEGER : QueryLexer::T_FLOAT
                );
                return $this->lexer->token['value'];

            default:
                throw new Exception('Error, expecting Literal, got '. $this->lexer->token['value']);
        }
    }
}