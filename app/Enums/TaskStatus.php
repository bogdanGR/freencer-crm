<?php
namespace App\Enums;

enum TaskStatus:string {
    case TODO='todo';
    case DOING='doing';
    case DONE='done';
}
