<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\Middleware;

use Psr\Http\{
    Message\ResponseFactoryInterface as ResponseFactory,
    Message\ResponseInterface as Response,
    Message\ServerRequestInterface as Request,
    Server\RequestHandlerInterface as Handler,
};

/**
 * Provides an empty response regardless of the request.
 */
class BasicFinalHandler implements Handler
{
    /**
     * Create a BasicFinalHandler instance.
     *
     * Accepts a PSR-17 response factory.
     */
    public function __construct(
        protected ResponseFactory $factory
    ) {
    }

    /**
     * Returns an empty response regardless of the request.
     */
    public function handle(Request $request): Response
    {
        return $this->factory->createResponse();
    }
}
