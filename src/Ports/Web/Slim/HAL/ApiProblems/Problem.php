<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\Ports\Web\Slim\HAL\ApiProblems;

use Crell\ApiProblem\ApiProblem;
use Symfony\Component\Validator\ConstraintViolation;
use Teapot\StatusCode\All as Status;

class Problem
{
    public static function forValidation(array $errors): ApiProblem
    {
        $problem = new ApiProblem(
            'Unprocessable Entity',
            'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'
        );
        $problem->setStatus(Status::UNPROCESSABLE_ENTITY);
        $problem['errors'] = array_map(function (ConstraintViolation $error) {
            return $error->getMessage();
        }, $errors);
        $problem['code'] = ErrorCode::INVALID_POSTER_INFORMATION;

        return $problem;
    }
}
