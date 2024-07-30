<?php

require_once 'vendor/autoload.php';

use TaskService\Task;

$task = new Task('new', 10);

$res = $task->statusAllowedActions('in-progress');

dump($res);

function dump($data): void
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}