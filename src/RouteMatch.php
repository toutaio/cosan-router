<?php

declare(strict_types=1);

namespace Touta\Cosan;

final readonly class RouteMatch
{
    public function __construct(
        public Route $route,
        public RouteParams $params = new RouteParams(),
    ) {}
}
