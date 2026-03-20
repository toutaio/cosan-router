<?php

declare(strict_types=1);

use Touta\Cosan\Route;

it('creates a route with method, path, and handler', function (): void {
    $handler = fn(): string => 'ok';
    $route = new Route('GET', '/users', $handler);

    expect($route->method)->toBe('GET')
        ->and($route->path)->toBe('/users')
        ->and($route->handler)->toBe($handler);
});

it('creates a route with a name', function (): void {
    $route = new Route('POST', '/users', fn(): string => 'ok', 'users.create');

    expect($route->name)->toBe('users.create');
});

it('has null name by default', function (): void {
    $route = new Route('GET', '/', fn(): string => 'ok');

    expect($route->name)->toBeNull();
});
