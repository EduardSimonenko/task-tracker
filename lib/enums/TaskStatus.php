<?php

namespace TaskManager\Enums;

enum TaskStatus: string
{
    case Todo = "todo";
    case InProgress = "in-progress";
    case Done = "done";
}