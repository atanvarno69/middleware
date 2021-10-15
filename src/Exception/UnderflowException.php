<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\Middleware\Exception;

use Atanvarno\Middleware\MiddlewareException;

/**
 * Thrown when attempting to retrieve a non-existent element.
 */
class UnderflowException extends \UnderflowException implements MiddlewareException
{
}
