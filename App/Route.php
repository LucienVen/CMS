<?php
/**
 * test route
 */
// $app->get('/hello/{name}', function ($request, $response, $args) {
//     echo "Hello, " . $args['name'];
// });

$app->get('/', function ($request, $response, $args) {
    return $response->withStatus(200)->write('Hello World!');
});

// use Action\User as UserAction;
$app->get('/test', '\App\Action\User:test');

// test args called
$app->get('/hello/{name}', '\App\Action\User:test');
// test link of mysql
$app->get('/user/{id}', '\App\Action\User:linkMysql');
