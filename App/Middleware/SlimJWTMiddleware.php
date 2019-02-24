<?php
namespace App\Middleware;

use Tuupola\Middleware\JwtAuthentication;

class SlimJWTMiddleware
{
    public function __invoke()
    {
        $jwt_setting = \Core\Config::get('JWT');
        $setting = [
            'secret' => $jwt_setting['jwt_key'],
            'algorithm' => $jwt_setting['alg'],
            'attribute' => 'jwt',
            'secure' => false,

        ];
        return new Tuupola\Middleware\JwtAuthentication($setting);

        // TODO 权限检查
        
    }
}