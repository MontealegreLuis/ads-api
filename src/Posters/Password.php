<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Posters;

use Webmozart\Assert\Assert;

/**
 * A poster's password
 *
 * @see Poster
 * @see PasswordTest
 */
class Password
{
    private const MIN_LENGTH = 8;

    /** @var string Hashed password  */
    private $hash;

    public static function fromPlainText(string $password): Password
    {
        return new Password($password);
    }

    public function verify(string $password): bool
    {
        return password_verify($password, $this->hash);
    }

    private function __construct(string $text)
    {
        $this->setPassword($text);
    }

    private function setPassword(string $text): void
    {
        Assert::notEmpty($text, 'Password cannot be empty');
        Assert::minLength($text, self::MIN_LENGTH, 'The password must be at least %2$s characters long.');
        $this->hash = password_hash($text, PASSWORD_DEFAULT);
    }
}
