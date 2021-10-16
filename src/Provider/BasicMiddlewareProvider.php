<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\Middleware\Provider;

use Psr\Http\Server\MiddlewareInterface as Middleware;
use Atanvarno\Middleware\{
    Exception\UnderflowException,
    MiddlewareProvider,
};

/**
 * Stores a middleware queue and provides them to a middleware dispatcher.
 */
class BasicMiddlewareProvider implements MiddlewareProvider
{
    /** @var array<array-key, Middleware> */
    protected array $queue;

    /**
     * Create a BasicMiddlewareProvider instance.
     *
     * Accepts a queue of Middleware to provide.
     */
    public function __construct(Middleware ...$middleware)
    {
        $this->queue = $middleware;
    }

    /**
     * Provide the next queued middleware.
     *
     * @throws UnderflowException The middleware queue is empty.
     */
    public function get(): Middleware
    {
        if ($this->isEmpty()) {
            $msg = 'No middleware available';
            throw new UnderflowException($msg);
        }
        return array_shift($this->queue);
    }

    /**
     * Whether the queue is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->queue);
    }
}
