<?php

require_once 'vendor/autoload.php';

use AvailableActions\AvailableActions;
use CustomExceptions\AvailableActionsException;


try {
    $strategy = new AvailableActions(AvailableActions::STATUS_IN_PROGRESS,3,1);

}catch (AvailableActionsException $e){
    die($e->getMessage());
}

dump($strategy->getAvailableActions(AvailableActions::ROLE_PERFORMER,1));


function dump($data): void
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}