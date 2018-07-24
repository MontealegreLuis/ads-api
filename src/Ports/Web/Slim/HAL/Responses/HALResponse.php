<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\HAL\Responses;


use Psr\Http\Message\ResponseInterface;
use Teapot\StatusCode\Http;

class HALResponse
{
    public static function created(ResponseInterface $response, string $location, string $body): ResponseInterface
    {
        $response->getBody()->write($body);

        return $response
            ->withStatus(Http::CREATED)
            ->withHeader('Content-Type', 'application/hal+json')
            ->withHeader('Location', $location);
    }
}
