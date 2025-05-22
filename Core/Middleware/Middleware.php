<?php

namespace Core\Middleware;

class Middleware
{
    public const MAP = [
        'guest' => Guest::class,
        'auth' => Authenticated::class,
        'role:admin' => RoleMiddleware::class,
        'jit_access' => JITAccess::class,
        'refresh_roles' => RefreshRoles::class,
    ];

    public static function resolve($keys)
    {
        if (!$keys) {
            return;
        }

        foreach ((array) $keys as $key) {
            $middleware = static::MAP[$key] ?? null;

            if (!$middleware) {
                throw new \Exception("No matching middleware found for key '{$key}'.");
            }

            (new $middleware)->handle();
        }
    }
}
