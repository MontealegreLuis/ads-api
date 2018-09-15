<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Registration\SignUp;

use Ads\Application\Validation\InputValidator;
use Ads\CodeList\Posters\Email;
use Ads\CodeList\Posters\Name;
use Ads\CodeList\Posters\Password;
use Ads\CodeList\Posters\Username;
use Symfony\Component\Validator\Constraints as Assert;

class SignUpPosterInput extends InputValidator
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 5)
     * @Assert\Regex("/^[a-zA-Z0-9_]+$/")
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 8)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    public static function withValues(array $values): SignUpPosterInput
    {
        return new SignUpPosterInput($values);
    }

    /** @return string[] */
    public function values(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    public function username(): Username
    {
        return new Username($this->username);
    }

    public function password(): Password
    {
        return Password::fromPlainText($this->password);
    }

    public function name(): Name
    {
        return new Name($this->name);
    }

    public function email(): Email
    {
        return Email::withAddress($this->email);
    }

    protected function __construct(array $input)
    {
        parent::__construct();
        $this->username = $input['username'] ?? '';
        $this->password = $input['password'] ?? '';
        $this->name = $input['name'] ?? '';
        $this->email = $input['email'] ?? '';
    }
}
