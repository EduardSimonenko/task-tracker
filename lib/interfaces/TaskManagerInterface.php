<?php

namespace TaskManager\Interfaces;

use TaskManager\Classes\CommandException;

interface TaskManagerInterface
{
    /**
     * @throws CommandException
     */
    public function create(string $string): string;

    /**
     * @throws CommandException
     */
    public function update($fields): string;

    public function getList($fields): bool|string;

    /**
     * @throws CommandException
     */
    public function delete($fields): string;

    /**
     * @throws CommandException
     */
    public function markDone($fields): string;

    /**
     * @throws CommandException
     */
    public function markInProgress($fields): string;
}