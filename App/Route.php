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
$app->get('/info', '\App\Action\Test:userInfo')
    ->add(new App\Middleware\JWTMiddleware($container));

// test upload file
$app->post('/upload', '\App\Action\Test:upload');


// test get request path
$app->get('/path', '\App\Action\Test:path')
    ->add(new App\Middleware\PermissionMiddleware($container))
    ->add(new App\Middleware\JWTMiddleware($container));
    // ->setName('path');
    

$app->get('/pdo', '\App\Action\Test:pdo');


/**
 * working
 */

// register
$app->post('/registered', '\App\Action\User:registered');

// login
$app->post('/login', '\App\Action\Auth:login');

// logout
$app->delete('/logout', '\App\Action\Auth:logout')
    ->add(new App\Middleware\JWTMiddleware($container));


$app->group('/auth', function () {
    $this->post('', '\App\Action\Auth:login');
    $this->delete('', '\App\Action\Auth:logout');
});

/**
 * excel opt
 */
$app->group('/excel', function () {
    $this->get('', '\App\Action\Test:excel');
    $this->post('/upload', '\App\Action\Test:uploadExcel');
    // export
    $this->post("/export", '\App\Action\Test:export');
});

/**
 * array sort test
 */

$app->group('/sort', function () {
    // 测试快速排序
    $this->get('/quickSort', 'App\Action\Sort:quickSort');
    // 测试冒泡排序
    $this->get('/bubbleSort', 'App\Action\Sort:bubbleSort');
    // 测试选择排序 selectSort
    $this->get('/selectSort', 'App\Action\Sort:selectSort');
    // test
    $this->get('/test', 'App\Action\Sort:test');
});

// $app->get('/call/{ids}', '\App\Action\Test:export');
