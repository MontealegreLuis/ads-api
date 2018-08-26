<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Application\Validation;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InputValidator
{
    /** @var ConstraintViolation[] */
    private $errors;

    protected function __construct()
    {
        $this->errors = [];
    }

    public function isValid(): bool
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $this->violationsToErrors($validator);

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    private function violationsToErrors(ValidatorInterface $validator): void
    {
        $violations = $validator->validate($this);
        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $this->errors[$violation->getPropertyPath()] = $violation->getMessage();
        }
    }
}
