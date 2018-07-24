<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\HAL;

use Ads\Ports\Web\Slim\HAL\Mappings\ObjectMapping;
use Ads\Ports\Web\Slim\HAL\Mappings\PosterMapping;
use Ads\Ports\Web\Slim\HAL\Mappings\UriBuilder;
use NilPortugues\Api\Hal\HalSerializer;
use NilPortugues\Api\Hal\JsonTransformer;
use NilPortugues\Api\Mapping\Mapper;

class Serializer
{
    public static function hal(UriBuilder $uriBuilder): HalSerializer
    {
        $mappings = [
            ObjectMapping::fromMapper(new PosterMapping($uriBuilder)),
        ];
        return new HalSerializer(new JsonTransformer(new Mapper($mappings)));
    }
}
