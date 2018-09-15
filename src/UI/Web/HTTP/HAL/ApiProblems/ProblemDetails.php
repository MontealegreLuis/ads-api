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
    public const INVALID_LOGIN_INFORMATION = [
        'code' => 'LOGIN-INV-INPUT',
        'details' => 'Login credentials are invalid',
    ];
    public const INVALID_CREDENTIALS = [
        'code' => 'INV_CRED_LOGIN',
        'details' => 'Either password or username are incorrect',
    ];
    public const INVALID_DRAFT_INFORMATION = [
        'code' => 'DRAFT-INV-INPUT',
        'details' => 'Draft Ad information is invalid',
    ];
    public const POSTER_NOT_FOUND = [
        'code' => 'POSTER-NOT-FOUND',
        'details' => 'Unknown poster username',
    ];
}
