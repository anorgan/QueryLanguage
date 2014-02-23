<?php

require __DIR__ .'/vendor/autoload.php';

$theInputs = array(
    'simple'                => 'pojam',
    'multi'                 => 'neki pojam s čovima',
    'attributes'            => 'neki:atribut',
    'fulltext attributes'   => 'čudni pojam atribut:vrijednost',
    'fulltext multiword attributes' => 'neki čudni pojam "neki atribut":"neka vrijednost"',
    'the whole shebang'     => '((neki -čudni) OR (pojam)) AND ("neki atribut":"neka vrijednost") AND Objekt.parametar = false'
);

$ebnf = <<<EOF
cqlquery            = scopedclause ;
scopedclause        = [ booleangroup ] , searchclause ;
booleangroup        = boolean ;
boolean             = "and" | "or" | "not" ;
searchclause        = "(" , cqlquery , ")" | comparator , searchterm | searchterm ;
comparator          = comparatorsymbol ;
comparatorsymbol    = "=" | ">" | "<" | ">=" | "<=" | "<>" | "==" | "!=" ;
namedcomparator     = identifier ;
searchterm          = term ;
term                = identifier | "and" | "or" | "not" ;
identifier          = "A" | "B" | "C" | "D" | "E" | "F" | "G"
                     | "H" | "I" | "J" | "K" | "L" | "M" | "N"
                     | "O" | "P" | "Q" | "R" | "S" | "T" | "U"
                     | "V" | "W" | "X" | "Y" | "Z"
                     | "0" | "1" | "2" | "3" | "4" | "5" | "6" | "7" | "8" | "9" ;
EOF;
