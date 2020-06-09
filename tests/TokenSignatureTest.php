<?php

namespace Tests;

use LGrevelink\SimpleJWT\Signing\Hmac\HmacSha256;
use LGrevelink\SimpleJWT\TokenSignature;

final class TokenSignatureTest extends TestCase
{
    public function testConstructor()
    {
        $signingMethod = new HmacSha256();
        $signatureKey = 'something';

        $signature = new TokenSignature($signingMethod, $signatureKey);

        $this->assertSame($signingMethod, $signature->signMethod());
        $this->assertSame($signatureKey, $signature->signatureKey());
    }
}
