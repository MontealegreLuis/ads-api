<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\DataStorage\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

class UsernameTypeTest extends TestCase
{
    /** @test */
    function it_does_not_need_to_convert_to_database_value_if_username_is_null()
    {
        /** @var UsernameType $type */
        $type = Type::getType('Username');

        $convertedValue = $type->convertToDatabaseValue(null, new SqlitePlatform());

        $this->assertNull($convertedValue);
    }

    /** @test */
    function it_does_not_need_to_convert_to_database_value_if_username_is_string()
    {
        /** @var UsernameType $type */
        $type = Type::getType('Username');

        $convertedValue = $type->convertToDatabaseValue('thomas_anderson', new SqlitePlatform());

        $this->assertEquals('thomas_anderson', $convertedValue);
    }

    /** @test */
    function it_fails_to_convert_to_database_value_if_no_string_or_null_value_is_given()
    {
        /** @var UsernameType $type */
        $type = Type::getType('Username');

        $this->expectException(ConversionException::class);
        $type->convertToDatabaseValue([1, 2, 3], new SqlitePlatform());
    }

    /** @test */
    function it_does_not_need_to_convert_to_php_value_if_username_is_null()
    {
        /** @var UsernameType $type */
        $type = Type::getType('Username');

        $convertedValue = $type->convertToPHPValue(null, new SqlitePlatform());

        $this->assertNull($convertedValue);
    }

    /** @test */
    function it_fails_to_convert_to_php_value_if_invalid_username_is_given()
    {
        /** @var UsernameType $type */
        $type = Type::getType('Username');

        $this->expectException(ConversionException::class);
        $type->convertToPHPValue('this is not a valid username', new SqlitePlatform());
    }

    /** @before */
    function configure()
    {
        if (!Type::hasType('Username')) {
            Type::addType('Username', UsernameType::class);
        }
    }
}
