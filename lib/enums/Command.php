<?php

namespace TaskManager\Enums;

enum Command: string
{
    case Add = "add";
    case Update = "update";
    case Delete = "delete";
    case MarkInProgress = "mark-in-progress";
    case MarkDone = "mark-done";
    case List = "list";
}