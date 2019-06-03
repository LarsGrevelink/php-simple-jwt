<?php

namespace LGrevelink\SimpleJWT\Signing\Hmac;

use LGrevelink\SimpleJWT\Signing\Hmac;

class HmacSha384 extends Hmac
{
    /**
     * @inheritdoc
     */
    public function getAlgorithm()
    {
        return 'sha384';
    }

    /**
     * @inheritdoc
     */
    public function getAlgorithmId()
    {
        return 'HS384';
    }
}
