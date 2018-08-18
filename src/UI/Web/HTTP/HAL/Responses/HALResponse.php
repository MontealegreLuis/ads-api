<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\HTTP\HAL\Responses;

use Crell\ApiProblem\ApiProblem;
use Psr\Http\Message\ResponseInterface;
use Teapot\StatusCode\All as Status;

class HALResponse
{
    public static function created(ResponseInterface $response, string $location, string $body): ResponseInterface
    {
        $response->getBody()->write($body);

        return $response
            ->withStatus(Status::CREATED)
            ->withHeader('Content-Type', 'application/hal+json')
            ->withHeader('Location', $location);
    }

    public static function unprocessableEntity(ResponseInterface $response, ApiProblem $problem): ResponseInterface
    {
        $response->getBody()->write($problem->asJson());

        return $response
            ->withStatus(Status::UNPROCESSABLE_ENTITY)
            ->withHeader('Content-Type','application/problem+json');
    }
}
