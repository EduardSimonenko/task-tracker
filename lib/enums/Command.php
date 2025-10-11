<?php

namespace TaskManager\Enums;

enum Command: string
{
    case Add = "add";
    case Update = "update";
    case Delete = "delete";
    case MarkInProgress = "mark_in_progress";
    case MarkDone = "mark_done";
    case List = "list";
}