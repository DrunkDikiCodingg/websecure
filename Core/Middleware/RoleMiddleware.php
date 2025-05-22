<?php

namespace Core\Middleware;

use Core\Session;

class RoleMiddleware
{
    public function handle()
    {
        $roles = Session::get('roles', []);

        if (!in_array('admin', $roles)) {
            abort(403);
        }
    }
}
