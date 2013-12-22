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

echo 'a';