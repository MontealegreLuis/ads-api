<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList;

use Carbon\Carbon;

class Clock
{
    /** @var int */
    private static $now;

    public static function freezeTimeAt(int $timestamp): void
    {
        self::$now = $timestamp;
    }

    public static function unfreezeTime(): void
    {
        self::$now = null;
    }

    public static function currentTimestamp(): int
    {
        return self::$now ?? Carbon::now('UTC')->getTimestamp();
    }
}
