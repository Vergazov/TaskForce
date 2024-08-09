<?php

use Converters\CsvSqlConverter;

require_once 'vendor/autoload.php';
require_once 'helpers.php';



//try {
//    $strategy = new AvailableActions('in-progress',3,1);
//
//    dump($strategy->getAvailableActions('performer',1));
//}catch (StatusActionException $e){
//    die($e->getMessage());
//}

$converter = new CsvSqlConverter('data/csv');
$result = $converter->convertFiles('data/sql');


