<?php

declare(strict_types=1);

namespace Touta\Cosan;

final readonly class Route
{
    /**
     * @param callable $handler
     */
    public function __construct(
        public string $method,
        public string $path,
        public mixed $handler,
        public ?string $name = null,
    ) {}
}
