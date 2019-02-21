<?php
/**
 * test route
 */
$app->get('/', function ($request, $response, $args) {
    return $response->withStatus(200)->write('Hello World!');
});

$app->get('/hello/{name}', '\App\Action\Test:base');

$app->get('/link', '\App\Action\Test:link');


// 路由中间件
$app->get('/middle_test', '\App\Action\Test:middle_test')
    ->add(new App\Middleware\TestMiddleware());

// 测试post方法
$app->post('/post', '\App\Action\Test:post');

$app->post('/post2', '\App\Action\Test:linkPdo');
$app->get('/instanceof', '\App\Action\Test:instanceof');

// test JWT Middleware (get user info)
// $app->get('/info', '\App\Action\Test:userInfo')
//     ->add(new App\Middleware\JWTMiddleware);
$app->get('/info', function($request, $response, $args){
    $token = $request->getHeader('Authorization')[0];
    $jwt_config = \Core\Config::get('JWT');
    $decoded = \Firebase\JWT\JWT::decode($token, $jwt_config['jwt_key'], $jwt_config['alg']);
    print_r($decoded);
})
// ->add(function($request, $response, $next){
//     print_r('hhelllllll');
//     return $next($request, $response);
// });
->add(new App\Middleware\JWTMiddleware);


// test args called
// $app->get('/hello/{name}', '\App\Action\User:test');
// test link of mysql
// $app->get('/user/{id}', '\App\Action\User:linkMysql')
//     ->add(new App\Middleware\TestMiddleware());


/**
 * working
 */

// user register
$app->post('/registered', '\App\Action\User:registered');

// login 
$app->post('/login', '\App\Action\Auth:login');
