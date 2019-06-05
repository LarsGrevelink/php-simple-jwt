<?php

namespace LGrevelink\SimpleJWT\Signing;

abstract class AbstractSigningMethod
{
    /**
     * Retrieves the JWT header algorithm identifier.
     *
     * @return string
     */
    abstract public function getAlgorithmId();

    /**
     * Signs the given data via an optional key and returns the matching signature.
     *
     * @param string $data
     * @param string $key (optional)
     *
     * @return string
     */
    abstract public function sign(string $data, ?string $key = null);

    /**
     * Verifies whether a signature matches the signature of the current data.
     *
     * @param string $expected
     * @param string $data
     * @param string $key (optional)
     *
     * @return bool
     */
    abstract public function verify(string $expected, string $data, ?string $key = null);
}
