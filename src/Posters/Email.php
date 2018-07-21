<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Posters;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

/**
 * A poster's email address
 *
 * @see Poster
 * @see EmailTest
 */
class Email
{
    /** @var string */
    private $address;

    public static function withAddress(string $email): Email
    {
        return new Email($email);
    }

    public function asText(): string
    {
        return $this->address;
    }

    private function __construct(string $address)
    {
        $this->setAddress($address);
    }

    private function setAddress(string $address): void
    {
        Assert::notEmpty($address, 'An email cannot be empty');
        $this->assertValidEmail($address);
        $this->address = $address;
    }

    private function assertValidEmail(string $address): void
    {
        if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("$address is not a valid email address");
        }
    }
}
