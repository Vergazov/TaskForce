<?php

require_once 'vendor/autoload.php';
require_once 'helpers.php';

use AvailableActions\AvailableActions;
use CustomExceptions\StatusActionException;
use Converters\CsvToSqlConverter;


//try {
//    $strategy = new AvailableActions('in-progress',3,1);
//
//    dump($strategy->getAvailableActions('performer',1));
//}catch (StatusActionException $e){
//    die($e->getMessage());
//}

//$file = new SplFileObject('data/categories.csv');
//
//while($file->valid()){
//    dump($file->fgetcsv());
//}
//echo memory_get_usage() . PHP_EOL;
$res = new CsvToSqlConverter('data/categories.csv','dump.sql');
//$res->convert();
$res->write();
dump($res->csvFileContent);
//echo memory_get_usage() . PHP_EOL;

