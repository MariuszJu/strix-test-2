<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('STRIX_TEST_ROOT_DIR', __DIR__);

session_start();

require_once __DIR__ . '/autoloader.php';

Autoloader::init();

\RockSolidSoftware\StrixTest\Runtime\App::init(isset($argv) ? $argv : []);