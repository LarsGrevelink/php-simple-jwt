<?php

namespace Tests\Signing\Hmac;

use LGrevelink\SimpleJWT\Signing\Hmac\HmacSha512;
use Tests\Signing\HmacTest;

final class HmacSha512Test extends HmacTest
{
    public function setUp()
    {
        $this->hmac = new HmacSha512();
    }

    public function testAlgortithm()
    {
        $this->assertSame('sha512', $this->hmac->getAlgorithm());
    }

    public function testAlgortithmId()
    {
        $hmac = new HmacSha512();

        $this->assertSame('HS512', $this->hmac->getAlgorithmId());
    }
}
