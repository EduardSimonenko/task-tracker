<?php

namespace TaskManager\Classes;

use TaskManager\Enums\TaskStatus;
use TaskManager\Interfaces\TaskManagerInterface;

class Task
{
    private int $id;
    public string $description;
    public TaskStatus $status;
    private \DateTime $createdAt;
    public \DateTime $updatedAt;

    private const string DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct(
        $id,
        $description,
        TaskStatus $status = TaskStatus::Todo,
        \DateTime $createdAt = new \DateTime(),
        \DateTime $updatedAt = new \DateTime()
    ) {
        $this->id = $id;
        $this->description = $description;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function __serialize(): array
    {
        return [
            "id" => $this->id,
            "description" => $this->description,
            "status" => $this->status->value,
            "createdAt" => $this->createdAt->format(self::DATE_FORMAT),
            "updatedAt" => $this->updatedAt->format(self::DATE_FORMAT),
        ];
    }

    public function __unserialize(array $data)
    {
        $this->id = $data["id"];
        $this->description = $data["description"];
        $this->status = $data["status"];
        $this->createdAt = $data["createdAt"];
        $this->updatedAt = $data["updatedAt"];
    }
}