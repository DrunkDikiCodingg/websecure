<?php

use Core\Response;

function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function urlIs($value)
{
    return $_SERVER['REQUEST_URI'] === $value;
}

function abort($code = 404)
{
    http_response_code($code);

    require base_path("views/{$code}.php");

    die();
}

function authorize($condition, $status = Response::FORBIDDEN)
{
    if (! $condition) {
        abort($status);
    }

    return true;
}

function base_path($path)
{
    return BASE_PATH . $path;
}

function view($path, $attributes = [])
{
    extract($attributes);

    require base_path('views/' . $path);
}

function getView($path, $attributes = [])
{
    extract($attributes);

    ob_start();

    require base_path('views/' . $path);

    return ob_get_clean();
}

function redirect($path)
{
    header("location: {$path}");
    exit();
}

function old($key, $default = '')
{
    return Core\Session::get('old')[$key] ?? $default;
}

function config($key = null)
{
    static $config = null;

    if (!$config) {
        $config = require base_path('config.php');
    }

    // If no key is provided, return the whole configuration array
    if (is_null($key)) {
        return $config;
    }

    // Traverse the array to get the nested value
    $keys = explode('.', $key);
    $value = $config;

    foreach ($keys as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return null; // Return null if the key doesn't exist
        }

        $value = $value[$segment];
    }

    return $value;
}

// AUTH
function auth()
{
    return Core\Session::get('user');
}

function isLoggedIn() {
    return auth() && auth()->id;
}

function hasRole($role) {
    return isset($_SESSION['roles']) && in_array($role, $_SESSION['roles']);
}

function hasJITAccess() {
   return hasRole('jit_access'); // && isset($_SESSION['jit_expiration']) && $_SESSION['jit_expiration'] > time();
}