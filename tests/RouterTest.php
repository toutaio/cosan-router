<?php

declare(strict_types=1);

use Touta\Aria\Runtime\Failure;
use Touta\Aria\Runtime\Success;
use Touta\Cosan\Route;
use Touta\Cosan\RouteCollection;
use Touta\Cosan\Router;

it('matches an exact static route', function (): void {
    $collection = new RouteCollection();
    $collection->get('/users', fn(): string => 'list users');

    $router = new Router($collection);
    $result = $router->match('GET', '/users');

    expect($result)->toBeInstanceOf(Success::class);

    $match = $result->value();
    expect($match->route->path)->toBe('/users')
        ->and($match->params)->toBe([]);
});

it('matches a route with a parameter', function (): void {
    $collection = new RouteCollection();
    $collection->get('/users/{id}', fn(): string => 'show user');

    $router = new Router($collection);
    $result = $router->match('GET', '/users/42');

    expect($result)->toBeInstanceOf(Success::class);

    $match = $result->value();
    expect($match->params)->toBe(['id' => '42']);
});

it('returns failure for unmatched path', function (): void {
    $collection = new RouteCollection();
    $collection->get('/users', fn(): string => 'list');

    $router = new Router($collection);
    $result = $router->match('GET', '/posts');

    expect($result)->toBeInstanceOf(Failure::class);
});

it('returns failure for unmatched method', function (): void {
    $collection = new RouteCollection();
    $collection->get('/users', fn(): string => 'list');

    $router = new Router($collection);
    $result = $router->match('POST', '/users');

    expect($result)->toBeInstanceOf(Failure::class);
});

it('matches the first matching route', function (): void {
    $collection = new RouteCollection();
    $collection->get('/users', fn(): string => 'first');
    $collection->get('/users', fn(): string => 'second');

    $router = new Router($collection);
    $match = $router->match('GET', '/users')->value();

    expect(($match->route->handler)())->toBe('first');
});
