<?php
/**
 * JWT （路由）中间件
 * 
 */
namespace App\Middleware;

use Core\Middleware;
use \Firebase\JWT\JWT;

class JWTMiddleware extends \Core\Middleware
{

    protected $container;
    protected $pdo;

    public function __construct(\Slim\Container $container)
    {
        $this->container = $container;
        $this->pdo = $container->get('pdo');
    }

    /**
     * 在进入应用程序之前进行验证
     * 请求头中获取 Authorization: Bearer <token>
     * 验证信息 JWT::decode
     */
    public function __invoke($request, $response, $next)
    {
        // print_r(__CLASS__);
        // print_r(PHP_EOL);
        // 判断token是否存在
        if (!$request->hasHeader('Authorization')) {
            return $response->withJson(['code' => 401, 'msg' => \Core\Config::get('status')[401]], 400);
        }

        $token = $request->getHeader('Authorization')[0];
        $jwt_config = \Core\Config::get('JWT');
        
        try {
            //当前时间减去60，把时间留点余地
            JWT::$leeway = 60;
            $decoded = JWT::decode($token, $jwt_config['jwt_key'], $jwt_config['alg']);
            // 把JWT存储信息添加到请求属性
            $userData = $decoded->data;
            $userData = (array)$userData;
            // secret 校验
            if ($userData['secret'] !== $this->getSecret($userData['id'])) {
                return $response->withJson($this->return_msg(451), 403);    
            }

            $request = $request->withAttribute('userData', $userData);

        } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            return $response->withJson($this->return_msg(401, $e->getMessage()), 403);
        } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            return $response->withJson($this->return_msg(401, $e->getMessage()), 403);
        } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
            return $response->withJson($this->return_msg(401, $e->getMessage()), 403);
        } catch (Exception $e) {  //其他错误
            return $response->withJson($this->return_msg(401, $e->getMessage()), 403);
        }

        return $next($request, $response);
    }

    /**
     * 获取数据库中用户当前对应secret
     *
     * @param int $uid
     * @return string $secret
     */
    protected function getSecret($uid)
    {
        try {
            $sql = 'select secret from user where id = :uid';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':uid', $uid);
            if ($stmt->execute()) {
                $data = $stmt->fetch();
                return $data['secret'];
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}