<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Posters;

interface Posters
{
    public function withUsername(Username $username): ?Poster;

    public function add(Poster $poster): void;
}
