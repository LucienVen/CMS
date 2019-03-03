<?php
namespace App\Middleware;

use Core\Middleware;

class PermissionMiddleware extends \Core\Middleware
{
    protected $container;
    protected $pdo;

    public function __construct(\Slim\Container $container)
    {
        $this->container = $container;
        $this->pdo = $container->get('pdo');
    }

    /**
     * 获取权限分值
     *
     * @param int $uid
     * @param string $path
     * @return int $value
     */
    protected function getPermissionValue($uid, $path)
    {
        $sql_search_role = 'select role_id from role where user_id = ?';
        $sql = 'select a.value from access as a right join role as r on ';
    }

    public function __invoke($request, $response, $next)
    {
        
        $route = $request->getAttribute('route');
        // 获取路由名称
        $callbackFunctionName = $route->getCallable();
        // 获取请求方法
        $requestMethod = $route->getMethods()[0];
        // print_r($routeName); => \App\Action\Test:path

        // 字符串替换与截取
        $callbackFunctionName = str_replace(':', '\\', $callbackFunctionName);
        $callbackFunctionName = substr($callbackFunctionName, strpos($callbackFunctionName, '\\', 1));

        // print_r($callbackFunctionName);
        // 校验
        
        // 获取请求属性中用户数据
        $userData = $request->getAttribute('userData');   
        if ($userData == null) {
            return $response->withJson($this->return_msg(401), 401);
        }


        
        

        // print_r($data);
        // 调用数据库进行查验判断
        

        

        return $next($request, $response);
    }

    protected function permission()
    {

    }
}