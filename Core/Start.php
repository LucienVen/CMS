<?php
/**
 * start framework
 * 单例模式实例化以及获取应用实例
 * 对象实例instance与slim应用实例有什么差别？？？
 */

 namespace Core;

class Start
{
    /**
     * object instance
     * @var \Core\Start
     */
    private static $instance;

    /**
     * object App
     * @var \Slim\App
     */
    private static $app;

    private function __construct()
    {
        // load config file
        self::$app = new \Slim\App(\Core\Config::get());
    }

    public static function getInstance()
    {
        if(!isset(self::$instance)){
            // 实例化自身
            $s = __CLASS__;
            $instance = new $s;
        }
        return self::$instance;
    }

    /**
     * 获取slim应用实例
     */
    public static function getApp()
    {
        if (!isset(self::$app)) {
            self::getInstance();
        }

        return self::$app;
    }

    /**
     * run slim
     */
    public static function run()
    {
        self::$app->run();
    }
}
