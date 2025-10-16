<?php

namespace TaskManager\Classes;

require_once ROOT . '/lib/interfaces/TaskManagerInterface.php';
require_once ROOT . '/lib/enums/TaskStatus.php';
require_once ROOT . '/lib/enums/CommandExceptionCase.php';
require_once ROOT . '/lib/classes/Task.php';
require_once ROOT . '/lib/classes/CommandException.php';

use TaskManager\Enums\TaskStatus;
use TaskManager\Enums\CommandExceptionCase;
use TaskManager\Interfaces\TaskManagerInterface;

class JsonTaskManager implements TaskManagerInterface
{
    private mixed $tasks;
    private string $path;
    private int $lastKey;
    private const  DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct($path)
    {
        $this->path = $path;
        $this->ensureFileExists($this->path);

        $this->tasks = json_decode(file_get_contents($path), true);
        $lastItem = end($this->tasks);

        $this->lastKey = !empty($lastItem) ? ++$lastItem["id"] : 0;
    }

    /**
     * @throws CommandException
     */
    public function create(string $string): string
    {
        $this->tasks[] = (new Task($this->lastKey, $string))->getData();
        $this->saveData();

        return "Task added successfully (ID: $this->lastKey)\n";
    }

    /**
     * @throws CommandException
     */
    public function update($fields): string
    {
        if (count($fields) < 2) {
            throw new CommandException(CommandExceptionCase::MissingArguments);
        }

        [$id, $description] = $fields;
        $searchKey = array_search((int)$id, array_column($this->tasks, 'id'));
        if ($searchKey === false) {
            throw new CommandException(CommandExceptionCase::UnknownTask, $id);
        }

        $this->tasks[$searchKey]["description"] = $description;
        $this->tasks[$searchKey]["updatedAt"] = (new \DateTime())->format(self::DATE_FORMAT);

        $this->saveData();

        return "Task updated successfully (ID: $id)\n";
    }

    public function getList($fields): bool|string
    {
        [$status] = $fields;
        if (!empty($status) && !TaskStatus::tryFrom($status)) {
            throw new \Error("Unknown task status: $status");
        }

        $result = empty($status) ? $this->tasks : array_filter($this->tasks, fn($task) => $task["status"] === $status);

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    /**
     * @throws CommandException
     */
    public function delete($fields): string
    {
        [$id] = $fields;
        if (!isset($id)) {
            throw new CommandException(CommandExceptionCase::MissingArguments);
        }

        $searchKey = array_search($id, array_column($this->tasks, 'id'));
        if ($searchKey === false) {
            throw new CommandException(CommandExceptionCase::UnknownTask, $id);
        }

        array_splice($this->tasks, $searchKey, 1);

        $this->saveData();

        return "Task deleted successfully (ID: $id)\n";
    }

    /**
     * @throws CommandException
     */
    public function markDone($fields): string
    {
        [$id] = $fields;
        if (!isset($id)) {
            throw new CommandException(CommandExceptionCase::MissingArguments);
        }

        $searchKey = array_search($id, array_column($this->tasks, 'id'));
        if ($searchKey === false) {
            throw new CommandException(CommandExceptionCase::UnknownTask, $id);
        }

        $this->tasks[$searchKey]["status"] = TaskStatus::Done->value;

        $this->saveData();

        return "Task done successfully (ID: $id)\n";
    }

    /**
     * @throws CommandException
     */
    public function markInProgress($fields): string
    {
        [$id] = $fields;
        if (!isset($id)) {
            throw new CommandException(CommandExceptionCase::MissingArguments);
        }

        $searchKey = array_search($id, array_column($this->tasks, 'id'));
        if ($searchKey === false) {
            throw new CommandException(CommandExceptionCase::UnknownTask, $id);
        }

        $this->tasks[$searchKey]["status"] = TaskStatus::InProgress->value;

        $this->saveData();

        return "Task done successfully (ID: $id)\n";
    }

    private function ensureFileExists($path): void
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (!file_exists($path)) {
            file_put_contents($path, json_encode([]));
        }
    }

    /**
     * @throws CommandException
     */
    private function saveData(): void
    {
        if (!file_put_contents($this->path, json_encode($this->tasks, JSON_PRETTY_PRINT))) {
            throw new CommandException(CommandExceptionCase::FailedSave, $this->path);
        }
    }
}
