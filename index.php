<?php

require_once 'vendor/autoload.php';

use AvailableActions\AvailableActions;
use CustomExceptions\StatusActionException;


try {
    $strategy = new AvailableActions('in-progress',3,1);

    dump($strategy->getAvailableActions('performer',1));
}catch (StatusActionException $e){
    die($e->getMessage());
}

function dump($data): void
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}