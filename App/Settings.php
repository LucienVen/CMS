<?php
// settings 限制两层结构？ 不限制，因为取出两层结构之后已经是很小维度，很容易继续获取设置信息了
return[
    // slim 框架设置
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'determineRouteBeforeAppMiddleware' => true,
    ],

    'db' => [
        'host' => '127.0.0.1',
        'user' => 'root',
        'pass' => '123456',
        'dbname' => 'CMS',
    ],

    /**
     * JWT 设置
     */
    'JWT' => [
        // jwt 秘钥配置
        'jwt_key' => 'LucienKey',
        // issuer 请求实体，可以是发起请求的用户的信息，也可是jwt的签发者。
        'iss' => 'Lucien',
        // aud 接收该JWT的一方
        'aud' => 'http://helloworld.com',
        // 指定算法
        'alg' => ['HS256'],
        
    ],

    /**
     * 数据库处理代码
     */
    'PDO' => [
        // code => message
        '100' => 'success',
        '101' => 'insert error',
    ],


    /**
     * 应用程序响应及错误代码
     * code => msg
     * success: code < 200
     * prompt： 200 < code < 400
     * error: code > 400
     */
    'status' => [
        '200' => 'success',
        '400' => 'error',

        // JWT
        '401' => 'Header Authorization Not Exists',

        // Auth
        '201' => 'login success',

        // PDO

        // upload file
        '202' => 'upload file success',
        '402' => 'not file upload',
        
    ],
];
