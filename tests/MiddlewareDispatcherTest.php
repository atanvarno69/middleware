<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\Middleware\Test;

use Atanvarno\Middleware\Exception\RuntimeException;
use Atanvarno\Middleware\HandlerProvider;
use Atanvarno\Middleware\MiddlewareDispatcher;
use Atanvarno\Middleware\MiddlewareProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class MiddlewareDispatcherTest extends TestCase
{
    public function testConstructorAcceptsNullHandler(): void
    {
        $middlewareProviderStub = $this->createStub(MiddlewareProvider::class);
        $actual = new MiddlewareDispatcher($middlewareProviderStub);
        $this->assertInstanceOf(MiddlewareDispatcher::class, $actual);
    }

    public function testConstructorAcceptsHandler(): void
    {
        $middlewareProviderStub = $this->createStub(MiddlewareProvider::class);
        $handlerStub = $this->createStub(Handler::class);
        $actual = new MiddlewareDispatcher($middlewareProviderStub, $handlerStub);
        $this->assertInstanceOf(MiddlewareDispatcher::class, $actual);
    }

    public function testConstructorAcceptsHandlerProvider(): void
    {
        $middlewareProviderStub = $this->createStub(MiddlewareProvider::class);
        $handlerProviderStub = $this->createStub(HandlerProvider::class);
        $actual = new MiddlewareDispatcher($middlewareProviderStub, $handlerProviderStub);
        $this->assertInstanceOf(MiddlewareDispatcher::class, $actual);
    }

    public function testMethodProcessCallsMiddlewareIfAvailable(): void
    {
        $responseStub = $this->createStub(Response::class);
        $middlewareMock = $this->createMock(Middleware::class);
        $middlewareMock->expects($this->once())->method('process')->willReturn($responseStub);
        $middlewareProviderMock = $this->createMock(MiddlewareProvider::class);
        $middlewareProviderMock->expects($this->once())->method('isEmpty')->willReturn(false);
        $middlewareProviderMock->expects($this->once())->method('get')->willReturn($middlewareMock);
        $handlerMock = $this->createMock(Handler::class);
        $handlerMock->expects($this->never())->method('handle');
        $dispatcher = new MiddlewareDispatcher($middlewareProviderMock);
        $actual = $dispatcher->process($this->createStub(Request::class), $handlerMock);
        $this->assertSame($responseStub, $actual);
    }

    public function testMethodProcessDelegatesToHandlerWithoutMiddleware(): void
    {
        $middlewareProviderStub = $this->createStub(MiddlewareProvider::class);
        $middlewareProviderStub->method('isEmpty')->willReturn(true);
        $responseStub = $this->createStub(Response::class);
        $handlerStub = $this->createStub(Handler::class);
        $handlerStub->method('handle')->willReturn($responseStub);
        $dispatcher = new MiddlewareDispatcher($middlewareProviderStub);
        $actual = $dispatcher->process($this->createStub(Request::class), $handlerStub);
        $this->assertSame($responseStub, $actual);
    }

    public function testMethodHandleCallsMiddlewareIfAvailable(): void
    {
        $responseStub = $this->createStub(Response::class);
        $middlewareMock = $this->createMock(Middleware::class);
        $middlewareMock->expects($this->once())->method('process')->willReturn($responseStub);
        $middlewareProviderMock = $this->createMock(MiddlewareProvider::class);
        $middlewareProviderMock->expects($this->once())->method('isEmpty')->willReturn(false);
        $middlewareProviderMock->expects($this->once())->method('get')->willReturn($middlewareMock);
        $handlerMock = $this->createMock(Handler::class);
        $handlerMock->expects($this->never())->method('handle');
        $dispatcher = new MiddlewareDispatcher($middlewareProviderMock, $handlerMock);
        $actual = $dispatcher->handle($this->createStub(Request::class));
        $this->assertSame($responseStub, $actual);
    }

    public function testMethodHandleDelegatesToHandlerWithoutMiddleware(): void
    {
        $middlewareProviderStub = $this->createStub(MiddlewareProvider::class);
        $middlewareProviderStub->method('isEmpty')->willReturn(true);
        $responseStub = $this->createStub(Response::class);
        $handlerStub = $this->createStub(Handler::class);
        $handlerStub->method('handle')->willReturn($responseStub);
        $dispatcher = new MiddlewareDispatcher($middlewareProviderStub, $handlerStub);
        $actual = $dispatcher->handle($this->createStub(Request::class));
        $this->assertSame($responseStub, $actual);
    }

    public function testMethodHandleCorrectlyReportsMissingHandler(): void
    {
        $middlewareProviderStub = $this->createStub(MiddlewareProvider::class);
        $middlewareProviderStub->method('isEmpty')->willReturn(true);
        $dispatcher = new MiddlewareDispatcher($middlewareProviderStub);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No final handler available');
        $dispatcher->handle($this->createStub(Request::class));
    }
}
