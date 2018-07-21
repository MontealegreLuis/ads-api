<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Ads\Builders\A;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

class UserSignUpContext implements Context
{
    public function __construct()
    {
    }

    /**
     * @Given a poster with the username :username
     */
    public function aPosterWithTheUsername(string $username): void
    {
        A::poster()->withUsername($username)->build();
    }

    /**
     * @When I sign up with the username :arg1
     */
    public function iSignUpWithTheUsername($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should be asked to choose a different username
     */
    public function iShouldBeAskedToChooseADifferentUsername()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that my account was created
     */
    public function iShouldBeNotifiedThatMyAccountWasCreated()
    {
        throw new PendingException();
    }
}
