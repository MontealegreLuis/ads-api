<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\HTTP\HAL\Mappings;

use NilPortugues\Api\Mapping\MappingFactory;
use NilPortugues\Api\Mappings\HalMapping;

class ObjectMapping extends MappingFactory
{
    public static function fromMapper(HalMapping $mapper): array
    {
        return [
            static::CLASS_KEY => $mapper->getClass(),
            static::ALIAS_KEY => $mapper->getAlias(),
            static::ALIASED_PROPERTIES_KEY => $mapper->getAliasedProperties(),
            static::HIDE_PROPERTIES_KEY => $mapper->getHideProperties(),
            static::ID_PROPERTIES_KEY => $mapper->getIdProperties(),
            static::URLS_KEY => $mapper->getUrls(),
            static::CURIES_KEY => $mapper->getCuries(),
        ];
    }
}
