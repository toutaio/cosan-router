<?php

declare(strict_types=1);

namespace Touta\Cosan;

use Touta\Aria\Runtime\Http\RequestInterface;
use Touta\Aria\Runtime\Result;

final class MiddlewarePipeline
{
    /** @var list<callable(RequestInterface, callable): Result<mixed, mixed>> */
    private array $middleware = [];

    /**
     * @param callable(RequestInterface, callable): Result<mixed, mixed> $middleware
     */
    public function add(callable $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * @param callable(RequestInterface): Result<mixed, mixed> $handler
     *
     * @return Result<mixed, mixed>
     */
    public function handle(RequestInterface $request, callable $handler): Result
    {
        $pipeline = $handler;

        foreach (array_reverse($this->middleware) as $mw) {
            $next = $pipeline;
            $pipeline = static fn(RequestInterface $req): Result => $mw($req, $next);
        }

        return $pipeline($request);
    }
}
