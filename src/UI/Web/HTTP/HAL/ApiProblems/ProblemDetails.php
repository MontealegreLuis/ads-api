<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\HTTP\HAL\ApiProblems;

interface ProblemDetails
{
    public const INVALID_POSTER_INFORMATION = [
        'code' => 'REG-INV-INPUT',
        'details' => 'Poster information is invalid',
    ];
    public const UNAVAILABLE_USERNAME = [
        'code' => 'REG-DUP-USER',
        'details' => 'Username is unavailable',
    ];
    public const INVALID_EVENTS_PAGE = [
        'code' => 'EVT-INV-PAGE',
        'details' => 'Invalid events page number',
    ];
}
