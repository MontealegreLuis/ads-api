<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\HTTP;

use Crell\ApiProblem\ApiProblem;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Teapot\StatusCode\All as Status;

class ApiResponse
{
    public static function created(string $location, string $body): ResponseInterface
    {
        $response = new Response();
        $response->write($body);

        return $response
            ->withStatus(Status::CREATED)
            ->withHeader('Content-Type', ContentType::HAL_JSON)
            ->withHeader('Location', $location);
    }

    public static function unprocessableEntity(ApiProblem $problem): ResponseInterface
    {
        $response = new Response();
        $response->write($problem->asJson());

        return $response
            ->withStatus(Status::UNPROCESSABLE_ENTITY)
            ->withHeader('Content-Type',ContentType::PROBLEM_JSON);
    }

    public static function unathorized(ApiProblem $problem): ResponseInterface
    {
        $response = new Response();
        $response->write($problem->asJson());

        return $response
            ->withStatus(Status::UNAUTHORIZED)
            ->withHeader('Content-Type', ContentType::PROBLEM_JSON);
    }

    public static function ok(string $body): ResponseInterface
    {
        $response = new Response();
        $response->write($body);

        return $response
            ->withStatus(Status::OK)
            ->withHeader('Content-Type', ContentType::HAL_JSON);
    }
}
