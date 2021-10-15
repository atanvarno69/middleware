<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\Middleware\Provider;

use Closure,

Throwable;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Atanvarno\Middleware\{
    Exception\RuntimeException,
    Exception\UnexpectedValueException,
    HandlerProvider,
};

/**
 * Calls a factory given to the constructor to create a final handler for use by
 * a middleware dispatcher.
 */
class LazyHandlerProvider implements HandlerProvider
{
    /**
     * Create a LazyHandlerProvider instance.
     *
     * Accepts a factory which generates a Handler instance. This MUST have the
     * signature: `function (): RequestHandlerInterface`.
     */
    public function __construct(
        protected Closure $factory
    ) {
    }

    /**
     * Provide the stored handler.
     *
     * Calls the stored factory to generate a Handler instance to return.
     *
     * @throws RuntimeException         The factory call throws an error or
     *                                  exception.
     * @throws UnexpectedValueException The factory call returns a value that is
     *                                  not a Handler instance.
     */
    public function get(): Handler
    {
        try {
            $handler = ($this->factory)();
        } catch (Throwable $caught) {
            $msg = 'Error instantiating handler';
            throw new RuntimeException(message: $msg, previous: $caught);
        }
        if (!$handler instanceof Handler) {
            $msg = 'Returned value is not a PSR-15 RequestHandlerInterface instance';
            throw new UnexpectedValueException($msg);
        }
        return $handler;
    }
}
