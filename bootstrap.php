<?php

use Core\App;
use Core\Container;
use Core\Database;
use Core\Role;

$container = new Container();

$container->bind('Core\Database', function () {
    $config = require base_path('config.php');

    return new Database($config['database']);
});

$container->bind('Core\Role', function () {
    return new Role();
});


App::setContainer($container);
