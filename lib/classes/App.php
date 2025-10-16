<?php

namespace TaskManager\Classes;

use TaskManager\Enums\Command;
use TaskManager\Interfaces\TaskManagerInterface;

class App
{
    private TaskManagerInterface $manager;

    public function __construct(TaskManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @throws CommandException
     */
    public function executeCommand(Command $command, array $fields = []): void
    {
        $result = match ($command) {
            Command::Add => $this->manager->create($fields[0]),
            Command::Update => $this->manager->update($fields),
            Command::List => $this->manager->getList($fields),
            Command::Delete => $this->manager->delete($fields),
            Command::MarkDone => $this->manager->markDone($fields),
            Command::MarkInProgress => $this->manager->markInProgress($fields)
        };

        echo $result;
    }
}