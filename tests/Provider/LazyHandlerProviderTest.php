<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types=1);

namespace Atanvarno\Middleware\Test\Provider;

use Exception,

stdClass;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Atanvarno\Middleware\{
    Exception\RuntimeException,
    Exception\UnexpectedValueException,
    Provider\LazyHandlerProvider,
};
use PHPUnit\Framework\TestCase;

class LazyHandlerProviderTest extends TestCase
{
    public function testHandlerIsReturnedFromCallable(): void
    {
        $handlerStub = $this->createStub(Handler::class);
        $callable = function () use ($handlerStub) {
            return $handlerStub;
        };
        $provider = new LazyHandlerProvider($callable);
        $actual = $provider->get();
        $this->assertSame($handlerStub, $actual);
    }

    public function testCorrectlyReportsThrowingCallable(): void
    {
        $callable = function () {
            throw new Exception();
        };
        $provider = new LazyHandlerProvider($callable);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Error instantiating handler');
        $provider->get();
    }

    public function testCorrectlyReportsBadReturnValueFromCallable(): void
    {
        $callable = function () {
            return new stdClass();
        };
        $provider = new LazyHandlerProvider($callable);
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Returned value is not a PSR-15 RequestHandlerInterface instance');
        $provider->get();
    }
}
