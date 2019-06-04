<?php

namespace Tests\Signing\Hmac;

use LGrevelink\SimpleJWT\Signing\Hmac\HmacSha256;
use Tests\Signing\HmacTest;

final class HmacSha256Test extends HmacTest
{
    public function setUp(): void
    {
        $this->hmac = new HmacSha256();
    }

    public function testAlgortithm()
    {
        $this->assertSame('sha256', $this->hmac->getAlgorithm());
    }

    public function testAlgortithmId()
    {
        $hmac = new HmacSha256();

        $this->assertSame('HS256', $this->hmac->getAlgorithmId());
    }
}
