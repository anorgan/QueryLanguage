<?php

namespace Anorgan\Dsl\Parser;

use Doctrine\Common\Lexer\AbstractLexer;

class SelectLexer extends AbstractLexer
{

    const T_FIELD = 1;

    /**
     * Array of patterns to catch
     *
     * @return array
     */
    protected function getCatchablePatterns()
    {
        return array(
            '[a-zA-Z][a-zA-Z0-9_\.]*[a-zA-Z0-9\_\*]*'
        );
    }

    /**
     * Array of patterns to ignore
     *
     * @return array
     */
    protected function getNonCatchablePatterns()
    {
        return array('\s+', ',', '(.)');
    }

    /**
     * Get type of token
     *
     * @param string $value
     *
     * @return int
     */
    protected function getType(&$value)
    {
        return self::T_FIELD;
    }
}
