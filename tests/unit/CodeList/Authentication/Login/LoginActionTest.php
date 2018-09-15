<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Authentication\Login;

use Ads\Builders\A;
use Ads\CodeList\Posters\InMemoryPosters;
use LogicException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class LoginActionTest extends TestCase
{
    /** @var LoginAction */
    private $action;

    /** @test */
    function it_authenticates_a_poster_successfully()
    {
        $poster = A::poster()->withUsername('thomas_anderson')->withPassword('ilovemyjob')->build();
        $this->posters->add($poster);
        $input = LoginInput::withValues([
            'username' => 'thomas_anderson',
            'password' => 'ilovemyjob',
        ]);
        $this->action->attach($this->responder->reveal());

        $this->action->authenticatePoster($input);

        $this->responder
            ->respondToSuccessfulAuthentication($poster)
            ->shouldHaveBeenCalled();
        $this->assertEmpty($input->errors());
    }

    /** @test */
    function it_provides_feedback_username_is_wrong()
    {
        $poster = A::poster()->withUsername('thomas_anderson')->withPassword('ilovemyjob')->build();
        $this->posters->add($poster);
        $input = LoginInput::withValues([
            'username' => 'wrong_username',
            'password' => 'ilovemyjob',
        ]);
        $this->action->attach($this->responder->reveal());

        $this->action->authenticatePoster($input);

        $this->responder
            ->respondToUserNotFound($input)
            ->shouldHaveBeenCalled();
        $this->assertEmpty($input->errors());
    }

    /** @test */
    function it_provides_feedback_if_password_is_wrong()
    {
        $poster = A::poster()->withUsername('thomas_anderson')->withPassword('ilovemyjob')->build();
        $this->posters->add($poster);
        $input = LoginInput::withValues([
            'username' => 'thomas_anderson',
            'password' => 'wrong password',
        ]);
        $this->action->attach($this->responder->reveal());

        $this->action->authenticatePoster($input);

        $this->responder
            ->respondToIncorrectPassword($input)
            ->shouldHaveBeenCalled();
        $this->assertEmpty($input->errors());
    }

    /** @test */
    function it_provides_feedback_if_input_is_invalid()
    {
        $input = LoginInput::withValues([
            'username' => 'invalid username', // no spaces allowed
            'password' => '',
        ]);
        $this->action->attach($this->responder->reveal());

        $this->action->authenticatePoster($input);

        $this->responder
            ->respondToInvalidInput(Argument::type('array'))
            ->shouldHaveBeenCalled();
        $this->assertFalse($input->isValid());
        $this->assertCount(2, $input->errors());
    }

    /** @test */
    function it_fails_if_no_responder_is_given()
    {
        $input = LoginInput::withValues([
            'username' => 'thomas_anderson',
            'password' => 'ilovemyjob',
        ]);

        $this->expectException(LogicException::class);
        $this->action->authenticatePoster($input);
    }

    /** @before */
    function configure()
    {
        $this->responder = $this->prophesize(LoginResponder::class);
        $this->posters = new InMemoryPosters();
        $this->action = new LoginAction($this->posters);
    }

    /** @var LoginResponder */
    private $responder;

    /** @var \Ads\CodeList\Posters\Posters */
    private $posters;
}
