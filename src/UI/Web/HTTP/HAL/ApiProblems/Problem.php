<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\HTTP\HAL\ApiProblems;

use Ads\Registration\SignUp\UnavailableUsername;
use Crell\ApiProblem\ApiProblem;
use Symfony\Component\Validator\ConstraintViolation;
use Teapot\StatusCode\All as Status;

class Problem
{
    public static function forValidation(array $errors): ApiProblem
    {
        $problem = self::unprocessableEntity();
        $problem['errors'] = array_map(function (ConstraintViolation $error) {
            return $error->getMessage();
        }, $errors);
        $problem['code'] = ErrorCode::INVALID_POSTER_INFORMATION;

        return $problem;
    }

    public static function unavailableUsername(UnavailableUsername $error): ApiProblem
    {
        $problem = self::unprocessableEntity();
        $problem['errors'] = [
            'username' => $error->getMessage(),
        ];
        $problem['code'] = ErrorCode::UNAVAILABLE_USERNAME;

        return $problem;
    }

    private static function unprocessableEntity(): ApiProblem
    {
        $problem = new ApiProblem(
            'Unprocessable Entity',
            'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'
        );
        $problem->setStatus(Status::UNPROCESSABLE_ENTITY);

        return $problem;
    }
}
