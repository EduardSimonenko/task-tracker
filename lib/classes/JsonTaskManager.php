<?php

namespace TaskManager\Classes;

require_once ROOT . '/lib/interfaces/TaskManagerInterface.php';

use TaskManager\Interfaces\TaskManagerInterface;

class JsonTaskManager implements TaskManagerInterface
{
    static private int $taskCounter = 0;
    private mixed $tasks = [];
    private string $path;

    public function __construct($path)
    {
        if (!file_exists($path)) {
            throw new \Error("File $path does not exist");
        }

        $this->tasks = json_decode(file_get_contents($path));
        $this->path = $path;
    }

    public function create(string $string)
    {
        $id = self::$taskCounter++;
        $this->tasks[] = (array)new Task($id, $string);
        if (!file_put_contents($this->path, json_encode($this->tasks, JSON_PRETTY_PRINT))) {
            throw new \Error("Failed to write to $this->path");
        }

        return "Task added successfully (ID: $id)";
    }

    public function update($fields)
    {
        return "";
    }

    public function getList($fields)
    {
        return "";
    }

    public function delete($fields)
    {
        return "";
    }

    public function markDone($fields)
    {
        return "";
    }

    public function markInProgress($fields)
    {
        return "";
    }
}
