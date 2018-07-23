<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\HAL;

use Ads\Ports\Web\Slim\HAL\Mappings\ObjectMappingFactory;
use Ads\Ports\Web\Slim\HAL\Mappings\PosterMapping;
use NilPortugues\Api\Hal\HalSerializer;
use NilPortugues\Api\Hal\JsonTransformer;
use NilPortugues\Api\Mapping\Mapper;
use Slim\Http\Request;
use Slim\Router;

class HalSerializerFactory
{
    public static function createFor(Request $request, Router $router): HalSerializer
    {
        $mappings = [
            ObjectMappingFactory::fromMapper(new PosterMapping($request, $router)),
        ];
        return new HalSerializer(new JsonTransformer(new Mapper($mappings)));
    }
}
