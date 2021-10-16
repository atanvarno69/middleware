<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\Middleware;

use Psr\Http\Server\MiddlewareInterface as Middleware;

/**
 * Provides middleware for a middleware dispatcher.
 */
interface MiddlewareProvider
{
    /**
     * Whether the provider is empty.
     */
    public function isEmpty(): bool;

    /**
     * Retrieve the next available middleware.
     *
     * MUST NOT throw an UnderflowException when `empty()` returns `false`.
     *
     * MAY throw a MiddlewareException.
     */
    public function get(): Middleware;
}
