<?php

namespace TaskManager\Classes;

require_once ROOT . '/lib/enums/CommandExceptionCase.php';

use TaskManager\Enums\CommandExceptionCase;

class CommandException extends \Exception
{
    public function __construct(CommandExceptionCase $case, string $arg = "")
    {
        match ($case) {
            CommandExceptionCase::MissingArguments => parent::__construct("Missing arguments"),
            CommandExceptionCase::UnknownTask => parent::__construct("Unknown task $arg"),
            CommandExceptionCase::UnknownStatus => parent::__construct("Unknown status"),
            CommandExceptionCase::FailedSave => parent::__construct("Failed to write to $arg")
        };
    }
}