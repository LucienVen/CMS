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
