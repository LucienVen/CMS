<?php

/**
 * autoload class
 */

class Autoload
{
    public static function load($className)
    {
        // echo DIRECTORY_SEPARATOR  => '/'
        $className = str_replace(['\\', '-'], DIRECTORY_SEPARATOR, $className);
        // ROOT_PATH => usr/local/var/www/CMS;
        // 获取需要加载的php文件全路径
        $loadPath = ROOT_PATH . DIRECTORY_SEPARATOR . $className . '.php';
        // echo $loadPath . PHP_EOL;
        if (file_exists($loadPath)) {
            require $loadPath;
        }else{
            throw new \Exception('load: ' . $loadPath . ': file does not exist!', 404);
        }
    }
}
