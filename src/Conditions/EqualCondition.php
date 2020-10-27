<?php

namespace LGrevelink\SimpleJWT\Conditions;

final class EqualCondition extends JwtCondition
{
    /**
     * Validates the condition against the given parameters.
     *
     * @param mixed $value
     * @param mixed $bluepintValue
     *
     * @return bool
     */
    public static function validate($value, $bluepintValue)
    {
        return $value === $bluepintValue;
    }
}
