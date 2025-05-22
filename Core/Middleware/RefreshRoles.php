<?php

namespace Core\Middleware;

use Core\App;
use Core\Database;
use Core\Role;

class RefreshRoles
{
    public function handle()
    {
        $user = auth();
        if (!$user) {
            return; // No user is logged in
        }

        $db = App::resolve(Database::class);
        $role = new Role($db);

        // Refresh roles from the database
        $roles = $role->getUserRoles($user->id);

        // Update session roles
        $_SESSION['roles'] = $roles;
    }
}
