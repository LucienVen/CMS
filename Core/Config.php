<?php
/**
 * load Config file
 * 应用单例模式
 */

 namespace Core;

class Config
{
    private static $config = [];
    private static $path =  SETTING_PATH;

    // 加载用户设置
    public static function load($path = null)
    {
        if(!is_null($path)){
            // 判断用户自定义配置文件是否存在
            self::$path = file_exists($path) ? $path : self::$path;
        }

        self::$config = require self::$path;
    }

    // 返回用户设置
    public static function get($field = null)
    {
        // 判断设置是否被加载
        if (!self::$config) {
            self::load();
        }

        // 判断用户需要加载的配置范围
        if (!is_null($field)) {
            foreach (self::$config as $first_key => $first_value) {
                // 如果第一层遍历找到了对应的配置信息，则返回
                if ($first_key == $field) {
                    return $first_value;
                }
                // 第二层结构上遍历
                if (is_array($first_value)) {
                    foreach ($first_value as $sencond_key => $sencond_value) {
                        if ($sencond_key == $field) {
                            return $sencond_value;
                        }
                    }
                }
            }
            
            return null;
        }

        return self::$config;
    }
}
