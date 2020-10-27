<?php

namespace Tests\Mocks\Conditions;

use LGrevelink\SimpleJWT\Conditions\JwtCondition;

class PassesConditionMock extends JwtCondition
{
    public static function validate($value, $initialValue)
    {
        return true;
    }
}
