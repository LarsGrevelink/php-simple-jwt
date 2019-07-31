<?php

namespace LGrevelink\SimpleJWT\Signing\Hmac;

use LGrevelink\SimpleJWT\Signing\Hmac;

final class HmacSha256 extends Hmac
{
    /**
     * @inheritdoc
     */
    public function getAlgorithm()
    {
        return 'sha256';
    }

    /**
     * @inheritdoc
     */
    public function getAlgorithmId()
    {
        return 'HS256';
    }
}
