<?php

declare(strict_types=1);

namespace Touta\Cosan;

final readonly class RoutingError
{
    public const NOT_FOUND = 'ROUTING.NOT_FOUND';
    public const METHOD_NOT_ALLOWED = 'ROUTING.METHOD_NOT_ALLOWED';
    public const INVALID_PATTERN = 'ROUTING.INVALID_PATTERN';
    public const MIDDLEWARE_FAILED = 'ROUTING.MIDDLEWARE_FAILED';

    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        public string $code,
        public string $message,
        public array $context = [],
    ) {}

    public function withMessage(string $message): self
    {
        return new self($this->code, $message, $this->context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function withContext(array $context): self
    {
        return new self($this->code, $this->message, array_merge($this->context, $context));
    }
}
