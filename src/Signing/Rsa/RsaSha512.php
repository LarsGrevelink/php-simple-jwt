<?php

namespace LGrevelink\SimpleJWT\Signing\Rsa;

use LGrevelink\SimpleJWT\Signing\Rsa;

class RsaSha512 extends Rsa
{
    /**
     * @inheritdoc
     */
    public function getAlgorithm()
    {
        return OPENSSL_ALGO_SHA512;
    }

    /**
     * @inheritdoc
     */
    public function getAlgorithmId()
    {
        return 'RS512';
    }
}
