<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\Middleware\Test;

use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseFactoryInterface as ResponseFactory,
    ResponseInterface as Response,
};
use Atanvarno\Middleware\BasicFinalHandler;
use PHPUnit\Framework\TestCase;

class BasicFinalHandlerTest extends TestCase
{
    public function testFactoryIsCalled(): void
    {
        $responseStub = $this->createStub(Response::class);
        $requestStub = $this->createStub(Request::class);
        $factoryMock = $this->createMock(ResponseFactory::class);
        $factoryMock->expects(self::once())->method('createResponse')->with()
            ->willReturn($responseStub);
        $handler = new BasicFinalHandler($factoryMock);
        $actual = $handler->handle($requestStub);
        $this->assertSame($responseStub, $actual);
    }
}
