<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\Request;

class SignUpPosterController
{
    public function signUp(Request $request, Response $response)
    {
        $server = $request->getServerParam('SERVER_NAME');
        $poster = $request->getParsedBody();
        $response->getBody()->write(json_encode($poster));
        return $response
            ->withStatus(201)
            ->withHeader('Content-Type', 'application/hal+json')
            ->withHeader('Location', "{$server}/{$poster['username']}");
    }
}
