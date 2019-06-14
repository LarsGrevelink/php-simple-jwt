<?php

namespace LGrevelink\SimpleJWT\Signing;

abstract class Hmac extends SigningMethod
{
    /**
     * @inheritdoc
     */
    public function sign(string $data, ?string $key = null)
    {
        return hash_hmac($this->getAlgorithm(), $data, $key ?? '', true);
    }

    /**
     * @inheritdoc
     */
    public function verify(string $expected, string $data, ?string $key = null)
    {
        return hash_equals($expected, $this->sign($data, $key ?? ''));
    }

    /**
     * Retrieves the HMAC algorithm.
     *
     * @return string
     */
    abstract public function getAlgorithm();
}
