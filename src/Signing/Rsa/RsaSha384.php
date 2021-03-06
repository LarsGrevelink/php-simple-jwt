<?php

namespace LGrevelink\SimpleJWT\Signing\Rsa;

use LGrevelink\SimpleJWT\Signing\Rsa;

final class RsaSha384 extends Rsa
{
    /**
     * @inheritdoc
     */
    public function getAlgorithm()
    {
        return OPENSSL_ALGO_SHA384;
    }

    /**
     * @inheritdoc
     */
    public function getAlgorithmId()
    {
        return 'RS384';
    }
}
