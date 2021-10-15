<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\Middleware\Provider;

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Atanvarno\Middleware\HandlerProvider;

/**
 * Stores a final handler given to the constructor for use by a middleware
 * dispatcher.
 */
class BasicHandlerProvider implements HandlerProvider
{
    /**
     * Create a BasicHandlerProvider instance.
     *
     * Accepts a Handler to provide.
     */
    public function __construct(
        protected Handler $handler
    ) {
    }

    /**
     * Provide the stored handler.
     */
    public function get(): Handler
    {
        return $this->handler;
    }
}
