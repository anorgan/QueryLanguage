<?php

require __DIR__ .'/vendor/autoload.php';

use Anorgan\Dsl\Composite;
use Anorgan\Dsl\Query;

$query = Query::create();

$query
    ->orX([
        Query::andX(['a', 'b']),
        Query::andX(['c', 'd']),
    ])
;

print_r($query);
echo $query;
echo PHP_EOL;