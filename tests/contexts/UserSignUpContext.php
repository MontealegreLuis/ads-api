<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Ads\Application\DomainEvents\DomainEventsCollector;
use Ads\Application\DomainEvents\EventPublisher;
use Ads\Builders\A;
use Ads\CodeList\Posters\InMemoryPosters;
use Ads\CodeList\Posters\Poster;
use Ads\CodeList\Posters\PosterHasSignedUp;
use Ads\CodeList\Posters\PosterInformation;
use Ads\CodeList\Registration\SignUp\SignUpPosterAction;
use Ads\CodeList\Registration\SignUp\SignUpPosterInput;
use Ads\CodeList\Registration\SignUp\SignUpPosterResponder;
use Ads\CodeList\Registration\SignUp\UnavailableUsername;
use Behat\Behat\Context\Context;
use Faker\Factory;

class UserSignUpContext implements Context, SignUpPosterResponder
{
    /** @var \Ads\CodeList\Posters\Posters */
    private $posters;

    /** @var SignUpPosterAction */
    private $action;

    /** @var \Faker\Generator */
    private $faker;

    /** @var bool */
    private $usernameIsUnavailable = false;

    /** @var DomainEventsCollector */
    private $collector;

    /** @var Poster */
    private $poster;

    public function __construct()
    {
        $this->posters = new InMemoryPosters();
        $this->action = new SignUpPosterAction($this->posters);
        $this->action->attach($this);
        $this->collector = new DomainEventsCollector();
        $this->faker = Factory::create();
        EventPublisher::reset();
        EventPublisher::subscribe($this->collector);
    }

    /**
     * @Given a poster with the username :username
     */
    public function aPosterWithTheUsername(string $username): void
    {
        $this->posters->add(A::poster()->withUsername($username)->build());
    }

    /**
     * @When I sign up with the username :username
     */
    public function iSignUpWithTheUsername(string $username)
    {
        $this->action->signUpPoster(SignUpPosterInput::withValues([
            'username' => $username,
            'password' => $this->faker->password(8),
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ]));
    }

    /**
     * @Then I should be asked to choose a different username
     */
    public function iShouldBeAskedToChooseADifferentUsername()
    {
        assertTrue($this->usernameIsUnavailable);
    }

    /**
     * @Then I should be notified that my account was created
     */
    public function iShouldBeNotifiedThatMyAccountWasCreated()
    {
        assertCount(1, $this->collector->events());
        assertInstanceOf(PosterHasSignedUp::class, $this->collector->events()[0]);
        assertInstanceOf(Poster::class, $this->poster);
    }

    public function respondToPosterSignedUp(Poster $poster): void
    {
        $this->poster = $poster;
    }

    public function respondToUnavailableUsername(SignUpPosterInput $input, UnavailableUsername $error): void
    {
        $this->usernameIsUnavailable = true;
    }

    public function respondToInvalidPosterInformation(array $errors): void
    {
        // this is covered by unit tests
    }
}
