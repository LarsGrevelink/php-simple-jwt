<?php

namespace LGrevelink\SimpleJWT\Conditions;

use LGrevelink\SimpleJWT\Contracts\ConditionContract;

abstract class JwtCondition implements ConditionContract
{
    /**
     * @inheritdoc
     */
    public static function passes($value, $initialValue = null)
    {
        if (method_exists(static::class, 'validate')) {
            return (bool) forward_static_call([static::class, 'validate'], $value, $initialValue);
        }

        return true;
    }
}
