<?php

namespace Anorgan\QueryLanguage\Parser;

use Doctrine\Common\Lexer\AbstractLexer;

class QueryLexer extends AbstractLexer
{
    const T_NONE                = 0;
    const T_EQUAL               = 1;
    const T_NOT                 = 2;
    const T_GREATER             = 3;
    const T_DOT                 = 4;
    const T_LOWER               = 5;
    const T_SINGLE_QUOTE        = 6;
    const T_DOUBLE_QUOTE        = 7;
    const T_AND                 = 10;
    const T_OR                  = 11;
    const T_CLOSE_PARENTHESIS   = 15;
    const T_OPEN_PARENTHESIS    = 16;
    const T_FLOAT               = 17;
    const T_INTEGER             = 18;
    const T_STRING              = 19;
    const T_OPEN_BRACKETS       = 20;
    const T_CLOSE_BRACKETS      = 21;

    /**
     * Array of patterns to catch
     *
     * @return array
     */
    protected function getCatchablePatterns()
    {
        return array(
            '[a-zA-Z0-9][a-zA-Z0-9_\.]*[a-z0-9_]{1}',
            '[\!\=\:><\[\]\"]{1}',
            '"(?:[^"]|\\")*"', // Quoted strings
            '(AND|OR)'
        );
    }

    /**
     * Array of patterns to ignore
     *
     * @return array
     */
    protected function getNonCatchablePatterns()
    {
        return array('\s+', '(.)');
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
        switch (true) {
            case (is_numeric($value)):
                if (strpos($value, '.') !== false || stripos($value, 'e') !== false) {
                    return self::T_FLOAT;
                }

                return self::T_INTEGER;

            // Recognize quoted strings
            case ($value[0] === '"'):
                $testValue = str_replace('""', '"', substr($value, 1, strlen($value) - 2));

                if (strlen($testValue)) {
                    $value = $testValue;
                    return self::T_STRING;
                }

            case ($value === '"'):
                return self::T_DOUBLE_QUOTE;

            case ($value === "'"):
                return self::T_SINGLE_QUOTE;

            case ($value === '('):
                return self::T_OPEN_PARENTHESIS;

            case ($value === ')'):
                return self::T_CLOSE_PARENTHESIS;

            case ($value === '['):
                return self::T_OPEN_BRACKETS;

            case ($value === ']'):
                return self::T_CLOSE_BRACKETS;

            case ($value === '.'):
                return self::T_DOT;

            case ($value === '!'):
                return self::T_NOT;

            case ($value === '='):
            case ($value === ':'):
                return self::T_EQUAL;

            case ($value === '>'):
                return self::T_GREATER;

            case ($value === '<'):
                return self::T_LOWER;

            case ($value === 'AND'):
                return self::T_AND;

            case ($value === 'OR'):
                return self::T_OR;

            case is_string($value):
                return self::T_STRING;

            default:
                return self::T_NONE;
        }
    }
}
