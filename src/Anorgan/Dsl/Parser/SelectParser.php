<?php

namespace Anorgan\Dsl\Parser;

use Anorgan\Dsl\Select;
use Doctrine\Common\Lexer\AbstractLexer;


class SelectParser
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

        $fields = array();
        while (null !== ($token = $this->lexer->lookahead)) {
            $fields[] = $token['value'];
            $this->lexer->moveNext();
        }

        return new Select($fields);
    }
}