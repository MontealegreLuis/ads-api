<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\JmsSerializer;

use Ads\Ports\DomainEvents\DomainEvent;
use Ads\Ports\DomainEvents\EventSerializer;
use Ads\Posters\Email;
use Ads\Posters\Name;
use Ads\Posters\Username;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;

class JSONSerializer implements EventSerializer
{
    /** @var \JMS\Serializer\Serializer */
    private $serializer;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()
            ->configureHandlers(function (HandlerRegistry $registry) {
                $registry->registerHandler(
                    'serialization',
                    Username::class,
                    'json',
                    function ($visitor, Username $username) {
                        return (string)$username;
                    }
                );
                $registry->registerHandler(
                    'serialization',
                    Name::class,
                    'json',
                    function ($visitor, Name $name) {
                        return $name->asText();
                    }
                );
                $registry->registerHandler(
                    'serialization',
                    Email::class,
                    'json',
                    function ($visitor, Email $dateTime) {
                        return $dateTime->asText();
                    }
                );
            })
            ->build();
    }

    public function serialize(DomainEvent $anEvent): string
    {
        return $this->serializer->serialize($anEvent, 'json');
    }
}
