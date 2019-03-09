<?php

// log operation
$app->group('/auth', function(){
    $this->post('', "\App\Action\Auth:login");
    $this->delete('', "\App\Action\Auth:logout");
        // ->add(new App\Middleware\JWTMiddleware($this->container));
});


// user register
$app->post('/registered', '\App\Action\User:registered');

