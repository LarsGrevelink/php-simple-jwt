<?php

namespace LGrevelink\SimpleJWT\Signing\Hmac;

use LGrevelink\SimpleJWT\Signing\Hmac;

class HmacSha512 extends Hmac
{
    /**
     * @inheritdoc
     */
    public function getAlgorithm()
    {
        return 'sha512';
    }

    /**
     * @inheritdoc
     */
    public function getAlgorithmId()
    {
        return 'HS512';
    }
}
