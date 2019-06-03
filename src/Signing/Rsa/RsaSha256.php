<?php

namespace LGrevelink\SimpleJWT\Signing\Rsa;

use LGrevelink\SimpleJWT\Signing\Rsa;

class RsaSha256 extends Rsa
{
    /**
     * @inheritdoc
     */
    public function getAlgorithm()
    {
        return OPENSSL_ALGO_SHA256;
    }

    /**
     * @inheritdoc
     */
    public function getAlgorithmId()
    {
        return 'RS256';
    }
}
