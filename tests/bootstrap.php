<?php
// include autoload from Composer
require_once 'vendor/autoload.php';

// Path constants to a few helpful things.
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__) . DS);
define('CAKE_CORE_INCLUDE_PATH', ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp');
define('CORE_PATH', ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS);
define('CAKE', CORE_PATH . 'src' . DS);
define('APP', ROOT . 'tests' . DS . 'test_app' . DS);
define('TMP', sys_get_temp_dir() . DS);
define('CACHE', TMP);
define('LOGS', TMP);
