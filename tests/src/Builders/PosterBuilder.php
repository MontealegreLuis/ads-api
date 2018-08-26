<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Builders;

use Ads\CodeList\Posters\Poster;
use Ads\CodeList\Posters\PosterInformation;
use Ads\CodeList\Registration\SignUp\SignUpPosterInput;
use ReflectionClass;

class PosterBuilder
{
    use WithFakeData;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    public function withUsername(string $username): PosterBuilder
    {
        $this->username = $username;

        return $this;
    }

    public function withPassword(string $password): PosterBuilder
    {
        $this->password = $password;

        return $this;
    }

    /** @throws \ReflectionException */
    public function build(): Poster
    {
        $information = PosterInformation::fromInput(SignUpPosterInput::withValues([
            'username' => $this->username ?? str_replace('.', '_', $this->faker->userName),
            'password' => $this->password ?? $this->faker->password(8),
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ]));

        $class = new ReflectionClass(Poster::class);
        $constructor = $class->getConstructor();
        $constructor->setAccessible(true);
        /** @var Poster $poster */
        $poster = $class->newInstanceWithoutConstructor();
        $constructor->invoke($poster, $information);

        return $poster;
    }
}
