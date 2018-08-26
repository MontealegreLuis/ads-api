<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\CodeList\Authentication\Login;

use Ads\Application\Validation\InputValidator;
use Symfony\Component\Validator\Constraints as Assert;

class LoginInput extends InputValidator
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 5)
     * @Assert\Regex("/^[a-zA-Z0-9_]+$/")
     */
    private $username;

    /**
     * @Assert\NotBlank()
     */
    private $password;

    public static function withValues(array $values): LoginInput
    {
        return new LoginInput($values);
    }

    public function values(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
        ];
    }

    protected function __construct(array $values)
    {
        parent::__construct();
        $this->username = trim($values['username'] ?? '');
        $this->password = $values['password'] ?? '';
    }
}
