<?php

namespace Http\Controllers;

use Core\App;
use Core\Database;
use Core\Role;
use Core\Session;

class UserController
{
    public function index()
    {
        $users = App::resolve(Database::class)
                    ->query(
                        "SELECT u.id, u.username, u.email, u.created_at, 
                            GROUP_CONCAT(r.name) as roles
                        FROM users u
                        LEFT JOIN user_roles ur ON u.id = ur.user_id
                        LEFT JOIN roles r ON ur.role_id = r.id
                        GROUP BY u.id"
                    )
                    ->setFetchMode(\PDO::FETCH_OBJ)
                    ->get();

        view('users/index.view.php', [
            'users' => $users
        ]);
    }

    public function show($id)
    {
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
                        ['id' => $id]
                    )
                    ->setFetchMode(\PDO::FETCH_OBJ)
                    ->find();
    
        if (!$user) {
            abort(404); // User not found
        }
    
        // Convert roles to an array
        $user->roles = $user->roles ? explode(',', $user->roles) : [];
    
        view('users/show.view.php', [
            'user' => $user
        ]);
    }

    public function grantJITAccess($id)
    {
        $role = new Role();

        // Validate the duration from the request
        $duration = (int) ($_POST['duration'] ?? 0);
        if ($duration <= 0) {
            abort(400, 'Invalid duration for JIT access.');
        }

        $role->grantJITAccess($id, $duration);

        // Redirect back to the user profile
        redirect("/user/{$id}");
    }

    public function revokeJITAccess($id)
    {
        $role = new Role();

        $role->revokeJITAccess($id);

        // Redirect back to the user profile
        redirect("/user/{$id}");
    }

    public function updatePassword($id)
    {
        $newPassword = $_POST['password'] ?? null;

        if (!$newPassword || strlen($newPassword) < 8) {
            // Redirect back with error
            Session::flash('errors', ['password' => 'Password must be at least 8 characters.']);
            return redirect("/user/{$id}");
        }

        App::resolve(Database::class)
            ->query("UPDATE users SET password = :password WHERE id = :id", [
                'password' => password_hash($newPassword, PASSWORD_BCRYPT),
                'id' => $id
            ]);

        // Redirect back with success message
        Session::flash('success', 'Password updated successfully.');
        redirect("/user/{$id}");
    }

    public function updateUsername($id)
    {
        $newUsername = $_POST['username'] ?? null;
    
        if (!$newUsername || strlen($newUsername) < 3) {
            // Redirect back with error
            Session::flash('errors', ['username' => 'Username must be at least 3 characters.']);
            return redirect("/user/{$id}");
        }
    
        App::resolve(Database::class)
            ->query("UPDATE users SET username = :username WHERE id = :id", [
                'username' => $newUsername,
                'id' => $id
            ]);
    
        // Redirect back with success message
        Session::flash('success', 'Username updated successfully.');
        redirect("/user/{$id}");
    }

    public function deleteUser($id)
    {
        App::resolve(Database::class)
            ->query("DELETE FROM users WHERE id = :id", ['id' => $id]);

        // Redirect to the users list
        Session::flash('success', 'User deleted successfully.');
        redirect('/users');
    }

}