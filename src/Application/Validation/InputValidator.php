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

    private $hasRunValidations;

    protected function __construct()
    {
        $this->hasRunValidations = false;
        $this->errors = [];
    }

    public function isValid(): bool
    {
        if (!$this->hasRunValidations) {
            $validator = Validation::createValidatorBuilder()
                ->enableAnnotationMapping()
                ->getValidator();

            $this->violationsToErrors($validator);
            $this->hasRunValidations = true;
        }

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
