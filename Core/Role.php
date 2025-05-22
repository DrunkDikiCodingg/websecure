<?php

namespace Core;

use Core\Database;
use PDO;

class Role
{
    private Database $db;

    public function __construct()
    {
        $this->db = App::resolve(Database::class);
    }

    public function assignRole(int $userId, string $roleName): bool
    {
        $this->db->query(
            "INSERT INTO user_roles (user_id, role_id)
             SELECT :user_id, id
             FROM roles
             WHERE name = :role_name
             ON CONFLICT (user_id, role_id) DO NOTHING",
            [
                'user_id' => $userId,
                'role_name' => $roleName
            ]
        );

        return true; //TODO: make this a reslut from the query: try catch maybe
    }

    /**
     * Remove a specific role from a user.
     */
    public function revokeRole(int $userId, string $roleName): bool
    {
        $this->db->query(
            "DELETE FROM user_roles
             WHERE user_id = :user_id
               AND role_id = (SELECT id FROM roles WHERE name = :role_name)",
            [
                'user_id' => $userId,
                'role_name' => $roleName
            ]
        );
        return true;
    }

    /**
     * Get all roles assigned to a user.
     */
    public function getUserRoles(int $userId): array
    {
        $results = $this->db->query("SELECT r.name
                                    FROM user_roles ur
                                    JOIN roles r ON ur.role_id = r.id
                                    WHERE ur.user_id = :user_id", 
                                    [
                                        'user_id' => $userId
                                    ])->setFetchMode(PDO::FETCH_ASSOC)
                                    ->get();

        return array_column($results, 'name'); // Extract 'name' column as a flat array
    }

    /**
     * Check if a user has a specific role.
     */
    public function hasRole(int $userId, string $roleName): bool
    {
        $result = $this->db->query(
            "SELECT 1
             FROM user_roles ur
             JOIN roles r ON ur.role_id = r.id
             WHERE ur.user_id = :user_id AND r.name = :role_name",
            [
                'user_id' => $userId,
                'role_name' => $roleName
            ]
        )->find();

        return (bool) $result;
    }

    /**
     * Grant JIT access to a specific role for a user.
     */
    public function grantJITAccess(int $userId, $durationMinutes)
    {
        $expiresAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $expiresAt->modify("+{$durationMinutes} minutes");
    
    
        // Assign the jit_access role
        $this->assignRole($userId, 'jit_access');
    
        // Insert or update the expiration time
        $this->db->query(
            "INSERT INTO temporary_roles (user_id, role_id, expires_at)
             SELECT :user_id, id, :expires_at
             FROM roles
             WHERE name = 'jit_access'
             ON CONFLICT (user_id, role_id) 
             DO UPDATE SET expires_at = :expires_at",
            [
                'user_id' => $userId,
                'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
            ]
        );
    
        // Update the session for the logged-in user, if applicable
         if (auth()->id === $userId) {
            $_SESSION['jit_expiration'] = $expiresAt;
             if (!in_array('jit_access', $_SESSION['roles'])) {
                 $_SESSION['roles'][] = 'jit_access';
             }
         }
    }
    

    /**
     * Check if a user has valid JIT access for a role.
     */
    public function hasJITAccess(int $userId, string $roleName): bool
    {
        $result = $this->db->query(
            "SELECT 1
            FROM temporary_roles tr
            JOIN roles r ON tr.role_id = r.id
            WHERE tr.user_id = :user_id
              AND r.name = :role_name
              AND tr.expires_at > CURRENT_TIMESTAMP
        ", [
            'user_id' => $userId,
            'role_name' => $roleName
        ])->find();

        return (bool) $result;
    }

    /**
     * Revoke JIT access.
     */
    public function revokeJITAccess(int $userId)
    {
        $this->revokeRole($userId, 'jit_access');

        $this->db->query(
            "DELETE FROM temporary_roles
             WHERE user_id = :user_id AND role_id = (SELECT id FROM roles WHERE name = 'jit_access')",
            ['user_id' => $userId]
        );

        // // Update session for the logged-in user, if applicable
         if (auth()->id === $userId) {
            $_SESSION['roles'] = array_filter($_SESSION['roles'], fn($role) => $role !== 'jit_access');
            unset($_SESSION['jit_expiration']);
         }
    }
    
    public function isJITAccessExpired(int $userId, string $roleName): bool
    {
        $result = $this->db->query(
            "SELECT 1 
            FROM temporary_roles tr
            JOIN roles r ON tr.role_id = r.id
            WHERE tr.user_id = :user_id
            AND r.name = :role_name
            AND tr.expires_at <= CURRENT_TIMESTAMP",
            [
                'user_id' => $userId,
                'role_name' => $roleName
            ]
        )->find();
        
        return (bool) $result;
    }
    /**
     * Revoke all expired JIT access entries.
     */
    
    public function revokeExpiredJITAccess(): void
    {
        $this->db->query(
            "DELETE FROM temporary_roles WHERE expires_at <= CURRENT_TIMESTAMP"
        );
    }
}
