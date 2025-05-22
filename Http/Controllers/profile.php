<?php

use Core\App;
use Core\Database;

$user = App::resolve(Database::class)
                ->query(
                    "SELECT u.id, u.username, u.email, u.created_at,
                        GROUP_CONCAT(r.name) as roles,
                            tr.expires_at as jit_expiration
                    FROM users u
                    LEFT JOIN user_roles ur ON u.id = ur.user_id
                    LEFT JOIN roles r ON ur.role_id = r.id
                    LEFT JOIN temporary_roles tr ON u.id = tr.user_id
                    WHERE u.id = :id
                    GROUP BY u.id",
                    ['id' => auth()->id]
                )
                ->setFetchMode(\PDO::FETCH_OBJ)
                ->find();
    

    if (!$user) {
        abort(404);
    }

    // Convert roles to an array
    $user->roles = $user->roles ? explode(',', $user->roles) : [];

    view('profile.view.php', [
        'user' => $user
    ]);
