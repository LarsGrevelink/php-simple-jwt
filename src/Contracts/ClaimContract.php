<?php

namespace LGrevelink\SimpleJWT\Contracts;

interface ClaimContract
{
    /**
     * Returns the claim name.
     *
     * @return string
     */
    public function name();

    /**
     * Verifies whether a claim passes the current check.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function validate($value);
}
