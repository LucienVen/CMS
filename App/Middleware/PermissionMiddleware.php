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


    public function __invoke($request, $response, $next)
    {
        // print_r(__CLASS__);
        // print_r(PHP_EOL);
        $route = $request->getAttribute('route');
        // 获取路由名称
        $callbackFunctionName = $route->getCallable();
        // 获取请求方法
        $requestMethod = $route->getMethods()[0];
        // print_r($routeName); => \App\Action\Test:path

        // 字符串替换与截取
        $callbackFunctionName = str_replace(':', '\\', $callbackFunctionName);
        $callbackFunctionName = substr($callbackFunctionName, strpos($callbackFunctionName, '\\', 1));
        
        // 校验
        
        // 获取请求属性中用户数据
        $userData = $request->getAttribute('userData');   
        if ($userData == null) {
            return $response->withJson($this->return_msg(401), 401);
        }

        // 获取用户角色id
        if (!$role_id = $this->getRoleId($userData['id'])) {
            return $response->withJson($this->return_msg(403, 'Auth False! user not in ROLE_GROUP!'));
        }

        // 获取node_id
        if (!$node_id = $this->getNodeId(strtolower($callbackFunctionName))) {
            return $response->withJson($this->return_msg(403, 'Auth False! ACTION not in PERMISSIOM!'));
        }
        
        // 获取权限分值
        if (!$access_value = $this->getAccessValue($role_id, $node_id)) {
            return $response->withJson($this->return_msg(403, 'Auth False! USER NO HAVE PERMISSION to ACCESS'));
        }

        // 验证权限
        if ($this->permission($access_value, $requestMethod)) {
            return $next($request, $response);    
        }

        return $response->withJson($this->return_msg(403, 'USER CAN NOT BE PERMISSION!'));
    }

    /**
     * 获取用户组权限分值
     *
     * @param int $role_id
     * @param int $node_id
     * @return int $value
     */
    protected function getAccessValue($role_id, $node_id)
    {
        try {
            $sql = 'select value from access where role_id = :role_id and node_id = :node_id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':role_id', $role_id);
            $stmt->bindParam(':node_id', $node_id);
            if ($stmt->execute()) {
                $data = $stmt->fetch();
                return $data['value'];
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 获取角色/用户组id
     *
     * @param int $uid
     * @return int $role_id
     */
    protected function getRoleId($uid)
    {
        try {
            $sql = 'select role_id from role_user where user_id = :uid';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':uid', $uid);
            if($stmt->execute()){
                $data = $stmt->fetch();
                return $data['role_id'];
            }else {
                return false;
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 获取权限节点id
     *
     * @param string $path
     * @return int $node_id
     */
    protected function getNodeId($path)
    {
        try {
            $sql = 'select id from permission where path = :path';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':path', $path);
            if ($stmt->execute()) {
                $data = $stmt->fetch();
                // return $data['id'];
                return $data['id'];
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 权限验证
     *
     * @param int $value
     * @param string $mode
     * @return bool
     */
    protected function permission($value, $mode)
    {
        $permission = \Core\Config::get('permission')[$mode];
        if (in_array($value, $permission)) {
            return true;
        }
        return false;
    }
}