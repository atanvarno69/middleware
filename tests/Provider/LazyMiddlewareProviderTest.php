<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\Middleware\Test\Provider;

use Exception, stdClass;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Atanvarno\Middleware\{
    Exception\RuntimeException,
    Exception\UnderflowException,
    Exception\UnexpectedValueException,
    Provider\LazyMiddlewareProvider,
};
use PHPUnit\Framework\TestCase;

class LazyMiddlewareProviderTest extends TestCase
{
    public function testMethodIsEmptyCorrectlyReturnsWhenMiddlewareIsNotPresent(): void
    {
        $provider = new LazyMiddlewareProvider();
        $this->assertTrue($provider->isEmpty());
    }

    public function testMethodIsEmptyCorrectlyReturnsWhenMiddlewareIsPresent(): void
    {
        $middleware = $this->createStub(Middleware::class);
        $factory = function () use ($middleware) {
            return $middleware;
        };
        $provider = new LazyMiddlewareProvider($factory);
        $this->assertFalse($provider->isEmpty());
    }

    public function testMethodGetReturnsMiddlewareInExpectedOrder(): void
    {
        $middleware = [
            'A' => $this->createStub(Middleware::class),
            'B' => $this->createStub(Middleware::class),
            'C' => $this->createStub(Middleware::class),
        ];
        $factories = [
            function () use ($middleware) {
                return $middleware['A'];
            },
            function () use ($middleware) {
                return $middleware['B'];
            },
            function () use ($middleware) {
                return $middleware['C'];
            },
        ];
        $provider = new LazyMiddlewareProvider(...$factories);
        $actual = $provider->get();
        $this->assertSame($middleware['A'], $actual);
        $actual = $provider->get();
        $this->assertSame($middleware['B'], $actual);
        $actual = $provider->get();
        $this->assertSame($middleware['C'], $actual);
    }

    public function testMethodGetCorrectlyReportsEmptyQueue(): void
    {
        $provider = new LazyMiddlewareProvider();
        $this->expectException(UnderflowException::class);
        $this->expectExceptionMessage('No middleware available');
        $provider->get();
    }

    public function testMethodGetCorrectlyReportsThrowingCallable(): void
    {
        $callable = function () {
            throw new Exception();
        };
        $provider = new LazyMiddlewareProvider($callable);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Error instantiating middleware');
        $provider->get();
    }

    public function testMethodGetCorrectlyReportsBadReturnValueFromCallable(): void
    {
        $callable = function () {
            return new stdClass();
        };
        $provider = new LazyMiddlewareProvider($callable);
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'Returned value is not a PSR-15 MiddlewareInterface instance'
        );
        $provider->get();
    }
}
