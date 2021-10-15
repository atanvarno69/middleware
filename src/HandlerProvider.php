<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\Middleware;

use Psr\Http\Server\RequestHandlerInterface as Handler;

/**
 * Provides a final handler for a middleware dispatcher.
 */
interface HandlerProvider
{
    /**
     * Retrieve the stored handler.
     *
     * MUST NOT throw an UnderflowException when `empty()` returns `false`.
     */
    public function get(): Handler;
}
