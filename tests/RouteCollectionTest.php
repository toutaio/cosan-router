<?php

declare(strict_types=1);

use Touta\Cosan\Route;
use Touta\Cosan\RouteCollection;
use Touta\Cosan\RoutePattern;

// Scenario: adding a Route and retrieving all routes
it('adds and retrieves routes', function (): void {
    $collection = new RouteCollection();
    $route = new Route('GET', new RoutePattern('/users'), fn(): string => 'ok');

    $collection->add($route);

    expect($collection->all())->toBe([$route]);
});

// Scenario: shorthand get() creates a GET route with RoutePattern
it('provides shorthand for GET routes', function (): void {
    $collection = new RouteCollection();
    $handler = fn(): string => 'ok';

    $collection->get('/home', $handler);

    $routes = $collection->all();
    expect($routes)->toHaveCount(1)
        ->and($routes[0]->method)->toBe('GET')
        ->and($routes[0]->pattern)->toEqual(new RoutePattern('/home'));
});

// Scenario: shorthand post() creates a POST route with RoutePattern
it('provides shorthand for POST routes', function (): void {
    $collection = new RouteCollection();
    $handler = fn(): string => 'ok';

    $collection->post('/submit', $handler);

    $routes = $collection->all();
    expect($routes)->toHaveCount(1)
        ->and($routes[0]->method)->toBe('POST');
});
