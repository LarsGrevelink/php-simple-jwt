<?php

namespace Tests\Signing\Hmac;

use LGrevelink\SimpleJWT\Signing\Hmac\HmacSha384;
use Tests\Signing\HmacTest;

final class HmacSha384Test extends HmacTest
{
    public function setUp(): void
    {
        $this->hmac = new HmacSha384();
    }

    public function testAlgortithm()
    {
        $this->assertSame('sha384', $this->hmac->getAlgorithm());
    }

    public function testAlgortithmId()
    {
        $hmac = new HmacSha384();

        $this->assertSame('HS384', $this->hmac->getAlgorithmId());
    }
}
