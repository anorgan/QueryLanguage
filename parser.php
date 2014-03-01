<?php

require_once __DIR__ .'/vendor/autoload.php';

use Anorgan\Dsl\Parser\QueryLexer;
use Anorgan\Dsl\Parser\QueryParser;
use Anorgan\Dsl\Parser\SelectLexer;
use Anorgan\Dsl\Parser\SelectParser;
use Anorgan\Query;

$query = new Query();

$date = date('Y-m-d');
$stringSelect = 'field other_field,Relation.field, Relation.relation_field Relation.LogoImage.Variation.url ,Relation.HeaderImage.Variation.url Relation.OtherRelation.OtherRelationsRelation.*';
$stringQuery  = 'title="neki string" AND '
            . 'id >= [1,2,34] AND '
            . 'is_active!=1 AND '
            . 'LogoImage.Variations.variation_id < 3 AND '
            . 'published_at<="'. $date .'"';

// Select
$sp     = new SelectParser(new SelectLexer);
$select = $sp->parse($stringSelect);

$query->setSelect($select);

// Query
$qp     = new QueryParser(new QueryLexer);

$query->setQuery($qp->parse($stringQuery));