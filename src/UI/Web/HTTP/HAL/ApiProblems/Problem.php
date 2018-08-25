<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ads\UI\Web\HTTP\HAL\ApiProblems;

use Crell\ApiProblem\ApiProblem;
use Teapot\StatusCode\All as Status;

class Problem
{
    public static function failedValidation(array $errors, array $details): ApiProblem
    {
        $problem = self::unprocessableEntity();
        $problem['errors'] = $errors;
        $problem['code'] = $details['code'];
        $problem['details'] = $details['details'];

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
