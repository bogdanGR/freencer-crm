<?php

namespace App\Enums;

enum ProjectStatus:string {
    case PLANNED='planned';
    case ACTIVE='active';
    case PAUSED='paused';
    case DONE='done';
}
