<?php

namespace App\Listeners;

use Spatie\Permission\Models\Role;
use Illuminate\Auth\Events\Registered;

class AssignDefaultRole
{
    public function handle(Registered $event): void
    {
        Role::firstOrCreate(['name' => 'manager']);
        $event->user->syncRoles(['manager']);
    }
}
