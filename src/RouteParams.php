<?php

declare(strict_types=1);

namespace Touta\Cosan;

final readonly class RouteParams
{
    /**
     * @param array<string, string> $value
     */
    public function __construct(
        public array $value = [],
    ) {}
}
