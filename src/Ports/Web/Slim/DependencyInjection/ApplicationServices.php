<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\DependencyInjection;

use Ads\Application\Registration\SignUpPosterAction;
use Ads\Ports\Web\Slim\Controllers\SignUpPosterController;
use Ads\Ports\Web\Slim\HAL\Mappings\ObjectMappingFactory;
use Ads\Ports\Web\Slim\HAL\Mappings\PosterMapping;
use Ads\Posters\InMemoryPosters;
use Ads\Registration\SignUpPoster;
use NilPortugues\Api\Hal\HalSerializer;
use NilPortugues\Api\Hal\JsonTransformer;
use NilPortugues\Api\Mapping\Mapper;
use Slim\Container;

class ApplicationServices
{
    public function register(Container $container): void
    {
        $container[PosterMapping::class] = function (Container $container) {
            return new PosterMapping($container['request'], $container['router']);
        };
        $container[HalSerializer::class] = function (Container $container) {
            $mappings = [
                ObjectMappingFactory::fromMapper($container[PosterMapping::class]),
            ];
            return new HalSerializer(new JsonTransformer(new Mapper($mappings)));
        };
        $container[SignUpPosterController::class] = function (Container $container) {
            return new SignUpPosterController(
                new SignUpPosterAction(new SignUpPoster(new InMemoryPosters())),
                $container['router']
            );
        };
    }
}
