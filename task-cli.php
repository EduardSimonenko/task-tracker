<?php

const ROOT = __DIR__;

require_once ROOT . '/lib/classes/App.php';
require_once ROOT . '/lib/classes/JsonTaskManager.php';
require_once ROOT . '/lib/enums/Command.php';

use TaskManager\Classes\App;
use TaskManager\Classes\JsonTaskManager;
use TaskManager\Enums\Command;

try {
    $taskManager = new JsonTaskManager(ROOT . "/data/tasks.json");
    $app = new App($taskManager);

    if ($argc < 2) {
        throw new Error("Usage: php script.php <command> [argv...]\n");
    }

    $command = Command::from($argv[1]);
    $fields = array_slice($argv, 2);

    $app->executeCommand($command, $fields);
    print_r($argv);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
