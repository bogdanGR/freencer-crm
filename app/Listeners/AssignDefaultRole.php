<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class AssignDefaultRole
{
    public function handle(Registered $event): void
    {
        $event->user->assignRole('manager'); // default for new signups
    }
}
