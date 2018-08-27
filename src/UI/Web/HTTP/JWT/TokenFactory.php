<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\HTTP\JWT;

use Ads\CodeList\Posters\Poster;
use Carbon\Carbon;
use ReallySimpleJWT\TokenBuilder;

class TokenFactory
{
    /** @var string */
    private $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /** @throws \ReallySimpleJWT\Exception\TokenBuilderException */
    public function builderFor(Poster $poster): TokenBuilder
    {
        return (new TokenBuilder())
            ->addPayload(['key' => 'username', 'value' =>  (string)$poster->username()])
            ->setSecret($this->secret)
            ->setExpiration(Carbon::now('UTC')->addMinutes(30)->getTimestamp());
    }

    public function secret(): string
    {
        return $this->secret;
    }
}
