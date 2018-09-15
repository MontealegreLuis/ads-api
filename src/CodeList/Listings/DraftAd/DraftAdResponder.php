<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Listings\DraftAd;

use Ads\CodeList\Ads\Ad;
use Ads\CodeList\Posters\Username;

interface DraftAdResponder
{
    public function respondToAdDrafted(Ad $ad): void;

    public function respondToInvalidInput(DraftAdInput $input): void;

    public function respondToUnknownPosterWith(Username $username): void;
}
