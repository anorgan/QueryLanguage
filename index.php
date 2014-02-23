<?php

require __DIR__ .'/vendor/autoload.php';
//
//$select = [
//    'field',
//    'other_field',
//    'Relation' => [
//        'field',
//        'relation_field',
//        'RelationsRelation' => [
//            'field',
//            'relations_relation_field',
//        ]
//    ],
//    'OtherRelation' => [
//        'OtherRelationsRelation' => '*'
//    ]
//];
//
//$output= [];
//
//$arrayIterator  = new ArrayIterator(($select));
//$recursiveArrayIterator  = new RecursiveArrayIterator(
//        $arrayIterator);
//$selectIterator = new RecursiveIteratorIterator(
//        $recursiveArrayIterator, RecursiveIteratorIterator::SELF_FIRST);
//
//while ($selectIterator->valid()) {
//    print_r($selectIterator->key()) .'#'. PHP_EOL;
//    print_r($selectIterator->current()). PHP_EOL;
//    //echo sprintf('%s => %s', $selectIterator->key(), $selectIterator->current()) . PHP_EOL;
//
//    $selectIterator->next();
//}



use Anorgan\Dsl;

$select = new Dsl\Select([
    'field',
    'other_field',
    'Relation.field',
    'Relation.relation_field',
    'Relation.LogoImage.Variation.url',
    'Relation.HeaderImage.Variation.url',
    'Relation.OtherRelation.OtherRelationsRelation.*',
]);

$output = $select->getFields();

$query = new Dsl\Query();
$query
    ->add('title="nekaj"')
    ->add('id = [1,2,34]')
    ->add('is_active!=1')
    ->add('LogoImage.Variations.variation_id = 3')
    ->add('published_at<='. date('Y-m-d'))
;

print_r($query->getFields());

$solution = [
    'field',
    'other_field',
    'Relation.field',
    'Relation.relation_field',
    'Relation.LogoImage.Variation.url',
    'Relation.HeaderImage.Variation.url',
    'Relation.OtherRelation.OtherRelationsRelation.*',
];

if ($solution == $output) {
    echo 'Done, odi spat';
} else {
    echo 'Keep working';
print_r($output);
//    print_r(array_diff($solution, $output));
}
