<?php

declare(strict_types=1);

use Touta\Aria\Runtime\Http\RequestInterface;
use Touta\Aria\Runtime\Http\ResponseInterface;
use Touta\Aria\Runtime\Result;
use Touta\Aria\Runtime\Success;
use Touta\Aria\Runtime\Type\HeaderMap;
use Touta\Aria\Runtime\Type\HttpBody;
use Touta\Aria\Runtime\Type\HttpMethod;
use Touta\Aria\Runtime\Type\StatusCode;
use Touta\Aria\Runtime\Type\UriPath;
use Touta\Cosan\MiddlewarePipeline;

// Scenario: handler executes directly when no middleware registered
it('executes handler when no middleware is registered', function (): void {
    $pipeline = new MiddlewarePipeline();

    $request = createStubRequest('GET', '/');
    $handler = fn(RequestInterface $req): Result => Success::of(createStubResponse(200, 'ok'));

    $result = $pipeline->handle($request, $handler);

    expect($result)->toBeInstanceOf(Success::class)
        ->and($result->value()->body()->value)->toBe('ok');
});

// Scenario: single middleware wraps the handler response
it('passes request through a single middleware', function (): void {
    $pipeline = new MiddlewarePipeline();
    $pipeline->add(function (RequestInterface $request, callable $next): Result {
        $result = $next($request);
        $response = $result->value();

        return Success::of(createStubResponse(
            $response->statusCode()->value,
            $response->body()->value . ' +middleware',
        ));
    });

    $request = createStubRequest('GET', '/');
    $handler = fn(RequestInterface $req): Result => Success::of(createStubResponse(200, 'base'));

    $result = $pipeline->handle($request, $handler);

    expect($result->value()->body()->value)->toBe('base +middleware');
});

// Scenario: middleware executes in order — first added is outermost
it('executes middleware in order (first added = outermost)', function (): void {
    $pipeline = new MiddlewarePipeline();

    $pipeline->add(function (RequestInterface $request, callable $next): Result {
        $result = $next($request);

        return Success::of(createStubResponse(200, $result->value()->body()->value . ' +A'));
    });

    $pipeline->add(function (RequestInterface $request, callable $next): Result {
        $result = $next($request);

        return Success::of(createStubResponse(200, $result->value()->body()->value . ' +B'));
    });

    $request = createStubRequest('GET', '/');
    $handler = fn(RequestInterface $req): Result => Success::of(createStubResponse(200, 'core'));

    $result = $pipeline->handle($request, $handler);

    expect($result->value()->body()->value)->toBe('core +B +A');
});

// Test helpers
function createStubRequest(string $method, string $uri): RequestInterface
{
    return new class ($method, $uri) implements RequestInterface {
        public function __construct(
            private readonly string $method,
            private readonly string $uri,
        ) {}

        public function method(): HttpMethod
        {
            return HttpMethod::from($this->method);
        }

        public function uri(): UriPath
        {
            return UriPath::from($this->uri);
        }

        public function headers(): HeaderMap
        {
            return HeaderMap::from([]);
        }

        public function body(): HttpBody
        {
            return HttpBody::from('');
        }
    };
}

function createStubResponse(int $status, string $body): ResponseInterface
{
    return new class ($status, $body) implements ResponseInterface {
        public function __construct(
            private readonly int $status,
            private readonly string $body,
        ) {}

        public function statusCode(): StatusCode
        {
            return StatusCode::from($this->status);
        }

        public function headers(): HeaderMap
        {
            return HeaderMap::from([]);
        }

        public function body(): HttpBody
        {
            return HttpBody::from($this->body);
        }
    };
}
