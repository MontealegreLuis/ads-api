<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\Slim\Handlers;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Handlers\Error;

class ErrorHandler extends Error
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger, bool $displayErrorDetails = false)
    {
        parent::__construct($displayErrorDetails);
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Exception $exception)
    {
        $this->logger->error($exception);

        return parent::__invoke($request, $response, $exception);
    }
}
