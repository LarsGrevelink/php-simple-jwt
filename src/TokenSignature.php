<?php

namespace LGrevelink\SimpleJWT;

use LGrevelink\SimpleJWT\Signing\SigningMethod;

class TokenSignature
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $key;

    /**
     * Constructor.
     *
     * @param SigningMethod $method
     * @param string $key
     */
    public function __construct(SigningMethod $method, string $key)
    {
        $this->method = $method;
        $this->key = $key;
    }

    /**
     * Fetches the signing method.
     *
     * @return SigningMethod
     */
    public function signMethod()
    {
        return $this->method;
    }

    /**
     * Fetches the signing method.
     *
     * @return string
     */
    public function signatureKey()
    {
        return $this->key;
    }
}
