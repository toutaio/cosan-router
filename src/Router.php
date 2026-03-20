<?php

declare(strict_types=1);

namespace Touta\Cosan;

use Touta\Aria\Runtime\Failure;
use Touta\Aria\Runtime\Result;
use Touta\Aria\Runtime\Success;

final readonly class Router
{
    public function __construct(
        private RouteCollection $routes,
    ) {}

    /**
     * @return Success<RouteMatch>|Failure<RoutingError>
     */
    public function match(string $method, string $uri): Result
    {
        foreach ($this->routes->all() as $route) {
            if ($route->method !== $method) {
                continue;
            }

            $params = $this->matchPattern($route->pattern, $uri);

            if ($params !== null) {
                return Success::of(new RouteMatch($route, $params));
            }
        }

        return Failure::from(new RoutingError(
            RoutingError::NOT_FOUND,
            "No route matched {$method} {$uri}",
            ['method' => $method, 'uri' => $uri],
        ));
    }

    private function matchPattern(RoutePattern $pattern, string $uri): ?RouteParams
    {
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern->value);
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $uri, $matches) !== 1) {
            return null;
        }

        /** @var array<string, string> $params */
        $params = [];

        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }

        return new RouteParams($params);
    }
}
