<?php
/**
 * 获取JWT内存储的用户信息
 */
namespace App\Common;

// use Core\Action;
use \Firebase\JWT\JWT;

class GetInfo
{
    protected $thisRequest;

    public function __construct($request)
    {
        $this->thisRequest = $request;
    }

    public function __invoke()
    {
        $token = $this->thisRequest->getHeader('Authorization')[0];
        $jwt_config = \Core\Config::get('JWT');
        $decoded = JWT::decode($token, $jwt_config['jwt_key'], $jwt_config['alg']);

        // user info
        // return $decoded['data'];
        // print_r($decoded);
        // return $this->thisRequest->write($decoded);


    }
}