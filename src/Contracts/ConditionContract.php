<?php

namespace LGrevelink\SimpleJWT\Contracts;

interface ConditionContract
{
    /**
     * Verifies whether the current condition passes the rules set for it.
     *
     * @param mixed $value
     * @param mixed $initialValue
     *
     * @return bool
     */
    public static function passes($value, $initialValue);
}
