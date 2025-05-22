<?php

namespace Core\Middleware;

use Core\App;
use Core\Database;
use Core\Role;

class JITAccess
{
    public function handle()
    {
        $db = App::resolve(Database::class);
        $role = new Role($db);

        $user = auth();

        // Allow admins to bypass JIT access check
        if ($user && in_array('admin', $role->getUserRoles($user->id))) {
            return;
        }

        // Only proceed with JIT checks if the session indicates jit_access
        if ($user && in_array('jit_access', $_SESSION['roles'] ?? [])) {
                
            $this->checkAndRevokeExpiredAccess($role, $user);

        }

        // Final JIT access validation
        if (!$user || !$role->hasJITAccess($user->id, 'jit_access')) {
            abort(403, "You don't have temporary access to view this page.");
        }
    }

    private function checkAndRevokeExpiredAccess(Role $role, $user)
    {
        if ($user) {
            
            
            // Check if the JIT access has expired in the database
            $hasExpired = $role->isJITAccessExpired($user->id, 'jit_access');
            
            
            if ($hasExpired) {
                // Revoke the expired JIT access
                $role->revokeJITAccess($user->id);
            }
            
        }
    }
}
