<?php
/**
 * JWT （路由）中间件
 * 
 */
namespace App\Middleware;

use \Firebase\JWT\JWT;

class JWTMiddleware
{
    /**
     * 在进入应用程序之前进行验证
     * 请求头中获取 Authorization: Bearer <token>
     * 验证信息 JWT::decode
     */
    public function __invoke($request, $response, $next)
    {
        // 判断token是否存在
        if (!$request->hasHeader('Authorization')) {
            return $response->withJson(['code' => 401, 'msg' => \Core\Config::get('status')[401]], 400);
        }

        $token = $request->getHeader('Authorization')[0];
        $jwt_config = \Core\Config::get('JWT');
        // print_r($jwt_config);
        // print_r($token);
        try {
            //当前时间减去60，把时间留点余地
            JWT::$leeway = 60;
            $decoded = JWT::decode($token, $jwt_config['jwt_key'], $jwt_config['alg']);


        } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            echo $e->getMessage();
        } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            echo $e->getMessage();
        } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
            echo $e->getMessage();
        } catch (Exception $e) {  //其他错误
            echo $e->getMessage();
        }

        return $next($request, $response);
    }
}