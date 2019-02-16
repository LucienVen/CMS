<?php
// define const value
define('ROOT_PATH', substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR)));
// ROOT_PATH => usr/local/var/www/CMS;

// load composer vendor
require __DIR__ . '/../vendor/autoload.php';

// load autoload class
require CORE_PATH . '/Autoload.php';
spl_autoload_register("Autoload::load");

// load config file
\Core\Config::load(SETTING_PATH);

// print_r(\Core\Config::get());