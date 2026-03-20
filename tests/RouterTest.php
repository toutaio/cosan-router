<?php

declare(strict_types=1);

use Touta\Aria\Runtime\Failure;
use Touta\Aria\Runtime\Success;
use Touta\Cosan\RouteCollection;
use Touta\Cosan\RouteParams;
use Touta\Cosan\RoutePattern;
use Touta\Cosan\Router;
use Touta\Cosan\RoutingError;

// Scenario: matching an exact static route returns Success with RouteMatch
it('matches an exact static route', function (): void {
    $collection = new RouteCollection();
    $collection->get('/users', fn(): string => 'list users');

    $router = new Router($collection);
    $result = $router->match('GET', '/users');

    expect($result)->toBeInstanceOf(Success::class);

    $match = $result->value();
    expect($match->route->pattern)->toEqual(new RoutePattern('/users'))
        ->and($match->params)->toEqual(new RouteParams());
});

// Scenario: matching a route with a parameter extracts RouteParams
it('matches a route with a parameter', function (): void {
    $collection = new RouteCollection();
    $collection->get('/users/{id}', fn(): string => 'show user');

    $router = new Router($collection);
    $result = $router->match('GET', '/users/42');

    expect($result)->toBeInstanceOf(Success::class);

    $match = $result->value();
    expect($match->params)->toEqual(new RouteParams(['id' => '42']));
});

// Scenario: unmatched path returns Failure with RoutingError NOT_FOUND
it('returns failure with RoutingError for unmatched path', function (): void {
    $collection = new RouteCollection();
    $collection->get('/users', fn(): string => 'list');

    $router = new Router($collection);
    $result = $router->match('GET', '/posts');

    expect($result)->toBeInstanceOf(Failure::class);

    $error = $result->error();
    expect($error)->toBeInstanceOf(RoutingError::class)
        ->and($error->code)->toBe(RoutingError::NOT_FOUND);
});

// Scenario: unmatched method returns Failure with RoutingError NOT_FOUND
it('returns failure with RoutingError for unmatched method', function (): void {
    $collection = new RouteCollection();
    $collection->get('/users', fn(): string => 'list');

    $router = new Router($collection);
    $result = $router->match('POST', '/users');

    expect($result)->toBeInstanceOf(Failure::class);

    $error = $result->error();
    expect($error)->toBeInstanceOf(RoutingError::class)
        ->and($error->code)->toBe(RoutingError::NOT_FOUND);
});

// Scenario: first matching route wins when multiple routes match
it('matches the first matching route', function (): void {
    $collection = new RouteCollection();
    $collection->get('/users', fn(): string => 'first');
    $collection->get('/users', fn(): string => 'second');

    $router = new Router($collection);
    $match = $router->match('GET', '/users')->value();

    expect(($match->route->handler)())->toBe('first');
});
