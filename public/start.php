<?php

/**
 * 检索当前路由（https://slimphp.app/docs/cookbook/retrieving-current-route.html）
 * 在应用程序中获取当前的路由
 */
 \Core\Start::getApp()->add(function ($request, $response, $next) {
     $route = $request->getAttribute('route');
    //  print_r($route);
     $this['args'] = $route ? $route->getArguments() : '';
     $this['routeName'] = $route->getName();
    //  print_r($this['args']);
     return $next($request, $response);
 });

/**
 * 注册slim容器
 */
if (file_exists(APP_PATH . '/Dependencies.php')) {
    require APP_PATH . '/Dependencies.php';
}

/**
 * 注册路由
 */
if (file_exists(APP_PATH . '/Route.php')) {
    require APP_PATH . '/Route.php';
}

/**
 * 启动应用
 */
\Core\Start::run();