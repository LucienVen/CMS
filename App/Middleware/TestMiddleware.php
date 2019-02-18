<?php
/**
 * test middleware
 */
namespace App\Middleware;

class TestMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $response->getBody()->write('Before response => ');
        $response = $next($request, $response);
        return $response;
    }
}