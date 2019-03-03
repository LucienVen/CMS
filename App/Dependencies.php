<?php

/**
 * 依赖注入容器
 * 依赖注入容器的概念是您将容器配置为能够在需要时加载应用程序所需的依赖项。
 * 一旦DIC创建/组装了依赖项，它就会存储它们，并且如果需要可以在以后再次提供它们。
 */

$container = $app->getContainer();

/**
 * monolog 日志记录
 * https://slimphp.app/docs/tutorial/first-app.html
 */
$container['logger'] = function ($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler(LOGGER_PATH . "/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

/**
 * PDO 了解MYSQL
 */
$container['pdo'] = function ($c) {
    $db = \Core\Config::get('db');
    $pdo = new PDO(
        "mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'],
        $db['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// 复制\Slim\CallableResolver.php，实现查询路由调用
$container['callableResolver'] = new \Core\CallableResolver($container);
