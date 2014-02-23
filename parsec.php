<?php

require __DIR__ .'/bootstrap.php';

$lexer = new Parsec\Lexer(array(
    'value' => '[\w]+',
    'bracketOpen' => '\(',
    'bracketClose' => '\)',
));

foreach ($theInputs as $input) {
    print_r($lexer->tokenize($input));
}