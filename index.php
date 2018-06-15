<?php
define('APP_PATH', __DIR__ . '/');

define('APP_DEBUG', true);

session_save_path( './' );
session_start();

require(APP_PATH . 'comphp/Comphp.php');

$config = require(APP_PATH . 'config/config.php');

(new comphp\Comphp($config))->run();
