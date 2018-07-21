<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Posters;

class InMemoryPosters implements Posters
{
    /** @var Poster[] */
    private $posters = [];

    public function withUsername(Username $username): ?Poster
    {
        foreach ($this->posters as $poster) {
            if ($poster->hasUsername($username)) {
                return $poster;
            }
        }
        return null;
    }

    public function add(Poster $poster): void
    {
        $this->posters[] = $poster;
    }
}
