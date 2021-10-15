<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\Middleware\Test\Provider;

use Psr\Http\Server\MiddlewareInterface as Middleware;
use Atanvarno\Middleware\{
    Exception\UnderflowException,
    Provider\BasicMiddlewareProvider,
};
use PHPUnit\Framework\TestCase;

class BasicMiddlewareProviderTest extends TestCase
{
    public function testMethodIsEmptyCorrectlyReturnsWhenMiddlewareIsNotPresent(): void
    {
        $provider = new BasicMiddlewareProvider();
        $this->assertTrue($provider->isEmpty());
    }

    public function testMethodIsEmptyCorrectlyReturnsWhenMiddlewareIsPresent(): void
    {
        $middleware = $this->createStub(Middleware::class);
        $provider = new BasicMiddlewareProvider($middleware);
        $this->assertFalse($provider->isEmpty());
    }

    public function testMethodGetReturnsMiddlewareInExpectedOrder(): void
    {
        $middleware = [
            'A' => $this->createStub(Middleware::class),
            'B' => $this->createStub(Middleware::class),
            'C' => $this->createStub(Middleware::class),
        ];
        $provider = new BasicMiddlewareProvider(...$middleware);
        $actual = $provider->get();
        $this->assertSame($middleware['A'], $actual);
        $actual = $provider->get();
        $this->assertSame($middleware['B'], $actual);
        $actual = $provider->get();
        $this->assertSame($middleware['C'], $actual);
    }

    public function testMethodGetCorrectlyReportsEmptyQueue(): void
    {
        $provider = new BasicMiddlewareProvider();
        $this->expectException(UnderflowException::class);
        $this->expectExceptionMessage('No middleware available');
        $provider->get();
    }
}
