<?php

/**
 * 入口文件
 * 定义常量
 */

// application path
define("APP_PATH", __DIR__ . "/../App");

// config path
define("SETTING_PATH", APP_PATH . "/Settings.php");

// Framework core path
define("CORE_PATH", __DIR__ . "/../Core");

// Logger path
define("LOGGER_PATH", __DIR__ . "/../Log");

// init Framework
require CORE_PATH . "/Init.php";

// start framework
$app = \Core\Start::getApp();

// start Framework
require __DIR__ . '/start.php';
