<?php

declare(strict_types=1);

namespace Touta\Cosan;

final readonly class RoutePattern
{
    public function __construct(
        public string $value,
    ) {}
}
