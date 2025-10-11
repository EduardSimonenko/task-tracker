<?php

namespace TaskManager\Classes;

require_once ROOT . '/lib/enums/TaskStatus.php';

use TaskManager\Enums\TaskStatus;

class Task
{
    private int $id;
    public string $description;
    public TaskStatus $status;
    private \DateTime $createdAt;
    public \DateTime $updatedAt;

    private const  DATE_FORMAT = 'Y-m-d H:i:s';

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

    public function getData(): array
    {
        return [
            "id" => $this->id,
            "description" => $this->description,
            "status" => $this->status->value,
            "createdAt" => $this->createdAt->format(self::DATE_FORMAT),
            "updatedAt" => $this->updatedAt->format(self::DATE_FORMAT),
        ];
    }
}