<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\Middleware\Test\Provider;

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Atanvarno\Middleware\Provider\BasicHandlerProvider;
use PHPUnit\Framework\TestCase;

class BasicHandlerProviderTest extends TestCase
{
    public function testHandlerIsReturned(): void
    {
        $handlerStub = $this->createStub(Handler::class);
        $provider = new BasicHandlerProvider($handlerStub);
        $actual = $provider->get();
        $this->assertSame($handlerStub, $actual);
    }
}
