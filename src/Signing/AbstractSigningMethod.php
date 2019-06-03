<?php

namespace LGrevelink\SimpleJWT\Signing;

abstract class AbstractSigningMethod
{
    /**
     * Retrieve the JWT header algorithm identifier.
     *
     * @return string
     */
    abstract public function getAlgorithmId();

    /**
     * @param string $data
     * @param string $key
     *
     * @return string
     */
    abstract public function sign(string $data, ?string $key = null);

    /**
     * @param string $expected
     * @param string $data
     * @param string $key
     *
     * @return string
     */
    abstract public function verify(string $expected, string $data, ?string $key = null);
}
