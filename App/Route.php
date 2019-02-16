<?php
/**
 * test route
 */
$app->get('/hello/{name}', function ($request, $response, $args) {
    echo "Hello, " . $args['name'];
});