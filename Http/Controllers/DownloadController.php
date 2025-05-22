<?php

namespace Http\Controllers;

use Core\App;
use Core\Role;

class DownloadController
{
    public function download($folder, $file)
    {
        $user = auth();
        $role = App::resolve(Role::class);

        // Ensure user has JIT access or is an admin
        if (!$user || (!$role->hasJITAccess($user->id, 'jit_access') && !hasRole('admin'))) {
            abort(403, 'Unauthorized access to resources.');
        }

        // File path validation
        $filePath = base_path("storage/resources/{$folder}/{$file}");
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Serve the file securely
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        readfile($filePath);
        exit;
    }
}
