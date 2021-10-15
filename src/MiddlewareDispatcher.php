<?php

/**
 * @package   atanvarno/middleware
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\Middleware;

use Psr\Http\Server\{
    MiddlewareInterface as Middleware,
    RequestHandlerInterface as Handler,
};
use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request,
};
use Atanvarno\Middleware\{
    Exception\RuntimeException,
    Provider\BasicHandlerProvider,
};

/**
 * PSR-15 middleware dispatcher.
 */
class MiddlewareDispatcher implements Handler, Middleware
{
    protected ?HandlerProvider $finalHandler;

    /**
     * Create a MiddlewareDispatcher instance.
     *
     * Accepts a MiddlewareProvider containing the middleware queue to dispatch.
     *
     * Optionally accepts a HandlerProvider or PSR-15 Handler to be called if no
     * middleware in the queue provide a response.
     */
    public function __construct(
        protected MiddlewareProvider $middleware,
        HandlerProvider|Handler|null $finalHandler = null,
    ) {
        if ($finalHandler instanceof Handler) {
            $this->setFinalHandler($finalHandler);
        } else {
            $this->finalHandler = $finalHandler;
        }
    }

    /**
     * Handle a request and produces a response by calling the queued
     * middleware.
     *
     * This method is primarily for the use of middleware. Consider calling
     * `process()` instead. If you call it directly, ensure a handler has been
     * set in the constructor.
     *
     * @throws RuntimeException The middleware queue is empty and no final
     *                          handler is available to provide a response. A
     *                          handler can be provided to the constructor or
     *                          via the `process()` method.
     */
    public function handle(Request $request): Response
    {
        if ($this->middleware->isEmpty()) {
            if ($this->finalHandler == null) {
                $msg = 'No final handler available';
                throw new RuntimeException($msg);
            }
            return $this->finalHandler->get()->handle($request);
        }
        return $this->middleware->get()->process($request, $this);
    }

    /**
     * Process an incoming server request and produces a response by calling the
     * queued middleware.
     *
     * If the queued middleware is unable to produce the response itself, it
     * delegates to the provided PSR-15 handler to do so.
     */
    public function process(Request $request, Handler $handler): Response
    {
        $this->setFinalHandler($handler);
        return $this->handle($request);
    }

    /**
     * Store a given handler to be called by `handle()` if required.
     *
     * @internal
     */
    protected function setFinalHandler(Handler $handler): void
    {
        $this->finalHandler = new BasicHandlerProvider($handler);
    }
}
