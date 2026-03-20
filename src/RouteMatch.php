<?php

declare(strict_types=1);

namespace Touta\Cosan;

/**
 * @param array<string, string> $params
 */
final readonly class RouteMatch
{
    /**
     * @param array<string, string> $params
     */
    public function __construct(
        public Route $route,
        public array $params = [],
    ) {}
}
