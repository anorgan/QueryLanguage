<?php

require __DIR__ .'/bootstrap.php';

require __DIR__ .'/vendor/ferno/loco/ebnf.php';

$contextQuery = $ebnfGrammar->parse($ebnf);


foreach ($theInputs as $input) {
    print_r($contextQuery->parse($input));
}