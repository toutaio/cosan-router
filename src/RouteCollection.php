<?php

declare(strict_types=1);

namespace Touta\Cosan;

final class RouteCollection
{
    /** @var list<Route> */
    private array $routes = [];

    public function add(Route $route): void
    {
        $this->routes[] = $route;
    }

    public function get(string $path, callable $handler, ?string $name = null): void
    {
        $this->add(new Route('GET', $path, $handler, $name));
    }

    public function post(string $path, callable $handler, ?string $name = null): void
    {
        $this->add(new Route('POST', $path, $handler, $name));
    }

    /**
     * @return list<Route>
     */
    public function all(): array
    {
        return $this->routes;
    }
}
