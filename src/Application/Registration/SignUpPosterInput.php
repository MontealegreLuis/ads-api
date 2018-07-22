<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\Registration;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SignUpPosterInput
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

    /** @var ConstraintViolation[] */
    private $errors;

    public static function withValues(array $values): SignUpPosterInput
    {
        return new SignUpPosterInput($values);
    }

    public function isValid(): bool
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $this->violationsToErrors($validator);

        return \count($this->errors) <= 0;
    }

    public function errors(): array
    {
        return $this->errors;
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

    private function __construct(array $input)
    {
        $this->username = $input['username'];
        $this->password = $input['password'];
        $this->name = $input['name'];
        $this->email = $input['email'];
        $this->errors = [];
    }

    private function violationsToErrors(ValidatorInterface $validator): void
    {
        $violations = $validator->validate($this);
        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $this->errors[$violation->getPropertyPath()] = $violation;
        }
    }
}
