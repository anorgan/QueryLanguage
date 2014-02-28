<?php

require_once __DIR__ .'/vendor/autoload.php';

use Anorgan\Query;

$query = new Query();

$stringSelect = 'field other_field,Relation.field, Relation.relation_field Relation.LogoImage.Variation.url ,Relation.HeaderImage.Variation.url Relation.OtherRelation.OtherRelationsRelation.*';
$stringQuery  = 'title=neki title Relation.id!=[1,3,5] OtherRelation.Relation.date<sad';

class SelectLexer extends Doctrine\Common\Lexer\AbstractLexer
{

    const T_FIELD = 1;

    protected function getCatchablePatterns()
    {
        return array(
            '[a-zA-Z][a-zA-Z0-9_\.]*[a-zA-Z0-9\_\*]*'
        );
    }

    protected function getNonCatchablePatterns()
    {
        return array('\s+', ',', '(.)');
    }

    protected function getType(&$value)
    {
        return self::T_FIELD;
    }
}

class SelectParser
{
    /**
     *
     * @var SelectLexer
     */
    protected $lexer;

    public function __construct($lexer)
    {
        $this->lexer = $lexer;
    }

    public function parse($input)
    {
        $this->lexer->setInput($input);
        $this->lexer->moveNext();

        $fields = array();
        while (null !== ($token = $this->lexer->lookahead)) {
            $token = $this->lexer->lookahead;
            $fields[] = $token['value'];
            $this->lexer->moveNext();
        }

        return new Anorgan\Dsl\Select($fields);
    }

    protected function match($token)
    {
        if (!$this->lexer->isNextToken($token) ) {
            $message  = sprintf('Expected %s, got %s.',
                $this->lexer->getLiteral($token),
                $this->lexer->lookahead === null ? 'end of string' : sprintf("'%s' at position %s", $token['value'], $token['position'])
            );

            throw new Exception($message);
        }

        return $this->lexer->moveNext();
    }
}

$sp     = new SelectParser(new SelectLexer);
$select = $sp->parse($stringSelect);

$query->setSelect($select);

$date = date('Y-m-d');
$query
    ->addQuery('title="nekaj" AND '
            . 'id >= [1,2,34] AND '
            . 'is_active!=1 AND '
            . 'LogoImage.Variations.variation_id < 3 AND '
            . 'published_at<="'. $date .'"')
;
