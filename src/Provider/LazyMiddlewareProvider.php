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
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Atanvarno\Middleware\{
    Exception\RuntimeException,
    Exception\UnderflowException,
    Exception\UnexpectedValueException,
    MiddlewareProvider,
};

/**
 * Stores a queue of callables given to the constructor and calls them in order
 * to create middleware and provide them to a middleware dispatcher.
 */
class LazyMiddlewareProvider implements MiddlewareProvider
{
    /** @var array<array-key, Closure> */
    protected array $queue;

    /**
     * Create a LazyMiddlewareProvider instance.
     *
     * Accepts a queue of factories which each generate a Middleware instance.
     * These must have the signature: `function (): MiddlewareInterface`.
     */
    public function __construct(Closure ...$middlewareFactories)
    {
        $this->queue = $middlewareFactories;
    }

    /**
     * Provide the next queued middleware.
     *
     * Calls the next queued factory to generate a Middleware instance to
     * return.
     *
     * @throws RuntimeException         The factory call throws an error or
     *                                  exception.
     * @throws UnderflowException       The middleware factory queue is empty.
     * @throws UnexpectedValueException The factory call returns a value that is
     *                                  not a Middleware instance.
     */
    public function get(): Middleware
    {
        if (empty($this->queue)) {
            $msg = 'No middleware available';
            throw new UnderflowException($msg);
        }
        $factory = array_shift($this->queue);
        try {
            $middleware = $factory();
        } catch (Throwable $caught) {
            $msg = 'Error instantiating middleware';
            throw new RuntimeException(message: $msg, previous: $caught);
        }
        if (!$middleware instanceof Middleware) {
            $msg = 'Returned value is not a PSR-15 MiddlewareInterface instance';
            throw new UnexpectedValueException($msg);
        }
        return $middleware;
    }

    /**
     * Whether the queue is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->queue);
    }
}
