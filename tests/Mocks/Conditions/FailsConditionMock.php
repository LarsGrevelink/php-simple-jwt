<?php

namespace Tests\Mocks\Conditions;

use LGrevelink\SimpleJWT\Conditions\JwtCondition;

class FailsConditionMock extends JwtCondition
{
    public static function validate($value, $initialValue)
    {
        return false;
    }
}
