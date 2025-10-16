<?php

namespace TaskManager\Enums;

enum CommandExceptionCase
{
    case MissingArguments;
    case UnknownTask;
    case UnknownStatus;
    case FailedSave;
}