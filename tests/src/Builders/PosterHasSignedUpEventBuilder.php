<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Builders;

use Ads\CodeList\Posters\Email;
use Ads\CodeList\Posters\Name;
use Ads\CodeList\Posters\PosterHasSignedUp;
use Ads\CodeList\Posters\Username;
use Carbon\Carbon;

class PosterHasSignedUpEventBuilder
{
    use WithFakeData;

    /** @var int */
    private $timestamp;

    /** @var string */
    private $name;

    /** @var string */
    private $username;

    /** @var string */
    private $email;

    public function withName(string $name): PosterHasSignedUpEventBuilder
    {
        $this->name = $name;

        return $this;
    }

    public function withUsername(string $username): PosterHasSignedUpEventBuilder
    {
        $this->username = $username;

        return $this;
    }

    public function withEmail(string $email): PosterHasSignedUpEventBuilder
    {
        $this->email = $email;

        return $this;
    }

    public function occurredOn(int $timestamp): PosterHasSignedUpEventBuilder
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function build(): PosterHasSignedUp
    {
        return new PosterHasSignedUp(
            new Username($this->username ?? $this->normalizeUsername()),
            new Name($this->name ?? $this->faker->name),
            Email::withAddress($this->email ?? $this->faker->email),
            $this->timestamp ?? Carbon::now('UTC')->getTimestamp()
        );
    }

    private function normalizeUsername(): string
    {
        $username = str_replace('.', '_', $this->faker->userName);
        if (\strlen($username) < 5) {
            $username .= str_repeat('a', 5 - \strlen($username));
        }
        return $username;
    }
}
