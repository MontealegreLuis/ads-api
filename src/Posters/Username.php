<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Posters;

use Webmozart\Assert\Assert;

/**
 * A poster's username
 *
 * @see Poster
 * @see UsernameTest
 */
class Username
{
    private const MIN_LENGTH = 5;
    private const FORMAT     = '/^[a-zA-Z0-9_]+$/';

    /** @var string */
    private $username;

    public function __construct(string $username)
    {
        $this->setUsername($username);
    }

    public function asText(): string
    {
        return $this->username;
    }

    private function setUsername(string $username): void
    {
        Assert::notEmpty($username, 'The username should not be blank.');
        Assert::minLength($username, self::MIN_LENGTH, 'The username should contains at least %2$s characters.');
        Assert::regex($username, self::FORMAT, 'The username is invalid.');
        $this->username = $username;
    }
}
