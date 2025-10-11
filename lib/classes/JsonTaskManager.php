<?php

namespace TaskManager\Classes;

require_once ROOT . '/lib/interfaces/TaskManagerInterface.php';
require_once ROOT . '/lib/enums/TaskStatus.php';
require_once ROOT . '/lib/classes/Task.php';

use TaskManager\Enums\TaskStatus;
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

    public function create(string $string)
    {
        $this->tasks[] = (new Task($this->lastKey, $string))->getData();
        if (!file_put_contents($this->path, json_encode($this->tasks, JSON_PRETTY_PRINT))) {
            throw new \Error("Failed to write to $this->path");
        }

        return "Task added successfully (ID: $this->lastKey)\n";
    }

    public function update($fields)
    {
        if (count($fields) < 2) {
            throw new \Error("Missing arguments");
        }

        [$id, $description] = $fields;

        $searchKey = array_search($id, array_column($this->tasks, 'id'));
        if (!$searchKey) {
            throw new \Error("Unknown task id: $id");
        }

        $this->tasks[$searchKey]["description"] = $description;
        $this->tasks[$searchKey]["updatedAt"] = (new \DateTime())->format(self::DATE_FORMAT);

        if (!file_put_contents($this->path, json_encode($this->tasks, JSON_PRETTY_PRINT))) {
            throw new \Error("Failed to write to $this->path");
        }

        return "Task updated successfully (ID: $id)\n";
    }

    public function getList($fields)
    {
        [$status] = $fields;
        if (!empty($status) && !TaskStatus::tryFrom($status)) {
            throw new \Error("Unknown task status: $status");
        }

        $result = empty($status) ? $this->tasks : array_filter($this->tasks, fn($task) => $task["status"] === $status);

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    public function delete($fields)
    {
        [$id] = $fields;
        if (!$id) {
            throw new \Error("Missing arguments");
        }

        $searchKey = array_search($id, array_column($this->tasks, 'id'));
        if (!$searchKey) {
            throw new \Error("Unknown task id: $id");
        }

        array_splice($this->tasks, $searchKey, 1);

        if (!file_put_contents($this->path, json_encode($this->tasks, JSON_PRETTY_PRINT))) {
            throw new \Error("Failed to write to $this->path");
        }

        return "Task deleted successfully (ID: $id)\n";
    }

    public function markDone($fields)
    {
        [$id] = $fields;
        if (!$id) {
            throw new \Error("Missing arguments");
        }

        $searchKey = array_search($id, array_column($this->tasks, 'id'));
        if (!$searchKey) {
            throw new \Error("Unknown task id: $id");
        }

        $this->tasks[$searchKey]["status"] = TaskStatus::Done->value;

        if (!file_put_contents($this->path, json_encode($this->tasks, JSON_PRETTY_PRINT))) {
            throw new \Error("Failed to write to $this->path");
        }

        return "Task done successfully (ID: $id)\n";
    }

    public function markInProgress($fields)
    {
        [$id] = $fields;
        if (!$id) {
            throw new \Error("Missing arguments");
        }

        $searchKey = array_search($id, array_column($this->tasks, 'id'));
        if (!$searchKey) {
            throw new \Error("Unknown task id: $id");
        }

        $this->tasks[$searchKey]["status"] = TaskStatus::InProgress->value;

        if (!file_put_contents($this->path, json_encode($this->tasks, JSON_PRETTY_PRINT))) {
            throw new \Error("Failed to write to $this->path");
        }

        return "Task done successfully (ID: $id)\n";
    }

    private function ensureFileExists($path)
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (!file_exists($path)) {
            file_put_contents($path, json_encode([]));
        }
    }
}
