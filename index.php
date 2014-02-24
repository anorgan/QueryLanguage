<?php

require __DIR__ .'/vendor/autoload.php';


use Anorgan\Dsl\Query;

$query = new \Anorgan\Query();

//$query
//    ->addSelect('field')
//    ->addSelect('other_field')
//    ->addSelect('Relation.field')
//    ->addSelect('Relation.relation_field')
//    ->addSelect('Relation.LogoImage.Variation.url')
//    ->addSelect('Relation.HeaderImage.Variation.url')
//    ->addSelect('Relation.OtherRelation.OtherRelationsRelation.*')
//;

$query->addSelect('field,other_field,Relation.field, '
        . 'Relation.relation_field Relation.LogoImage.Variation.url '
        . 'Relation.HeaderImage.Variation.url '
        . 'Relation.OtherRelation.OtherRelationsRelation.*');

$date = date('Y-m-d');
$query
    ->addQuery('title="nekaj"')
    ->addQuery('id >= [1,2,34]')
    ->addQuery('is_active!=1')
    ->addQuery('LogoImage.Variations.variation_id < 3')
    ->addQuery('published_at<="'. $date .'"')
;

$select = [
    'field',
    'other_field',
    'Relation.field',
    'Relation.relation_field',
    'Relation.LogoImage.Variation.url',
    'Relation.HeaderImage.Variation.url',
    'Relation.OtherRelation.OtherRelationsRelation.*',
];

$constraints = [
    [
        'field' => 'title',
        'operator' => '=',
        'value' => '"nekaj"'
    ],
    [
        'field' => 'id',
        'operator' => '>=',
        'value' => ['1','2','34']
    ],
    [
        'field' => 'is_active',
        'operator' => '!=',
        'value' => '1'
    ],
    [
        'field' => 'LogoImage.Variations.variation_id',
        'operator' => '<',
        'value' => '3'
    ],
    [
        'field' => 'published_at',
        'operator' => '<=',
        'value' => '"'. $date .'"'
    ],
];

$queryConditions = $query->getQuery()->getConditions();
$validConstraints = true;
foreach ($constraints as $key => $constraint) {
    if (!isset($queryConditions[$key])) {
        echo 'Unknown key '. $key .' in conditions'. PHP_EOL;
        break;
    }

    if ($constraint !== $queryConditions[$key]->toArray()) {
        echo 'Error for condition '. $key .PHP_EOL;
        print_r($queryConditions[$key]);
        $validConstraints = false;
    }
}

if (
    $select === $query->getSelect()->getFields() && $validConstraints
) {
    echo 'Done, odi spat';
} else {
    print_r($query->getSelect()->getFields());
    print_r($queryConditions);
    echo 'Keep working';
}
