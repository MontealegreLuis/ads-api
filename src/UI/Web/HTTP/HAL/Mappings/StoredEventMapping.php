<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\HTTP\HAL\Mappings;

use Ads\Application\DomainEvents\StoredEvent;
use NilPortugues\Api\Mappings\HalMapping;

class StoredEventMapping implements HalMapping
{
    /**
     * Returns a string with the full class name, including namespace.
     *
     * @return string
     */
    public function getClass(): string
    {
        return StoredEvent::class;
    }

    /**
     * Returns a string representing the resource name as it will be shown after the mapping.
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'event';
    }

    /**
     * Returns an array of properties that will be renamed.
     * Key is current property from the class. Value is the property's alias name.
     *
     * @return array
     */
    public function getAliasedProperties(): array
    {
        return [];
    }

    /**
     * List of properties in the class that will be ignored by the mapping.
     *
     * @return array
     */
    public function getHideProperties(): array
    {
        return [];
    }

    /**
     * Returns an array of properties that are used as an ID value.
     *
     * @return array
     */
    public function getIdProperties(): array
    {
        return ['eventId'];
    }

    /**
     * Returns a list of URLs. This urls must have placeholders to be replaced with the getIdProperties() values.
     *
     * @return array
     */
    public function getUrls(): array
    {
        return ['self' => '  ']; // any no empty string will work...
    }

    /**
     * Returns an array of curies.
     *
     * @return array
     */
    public function getCuries(): array
    {
        return [];
    }
}
