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
 * Thrown when a closure returns a value of an unexpected type.
 */
class UnexpectedValueException extends \UnexpectedValueException implements MiddlewareException
{
}
