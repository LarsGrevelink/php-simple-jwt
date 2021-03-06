<?php

namespace Tests\Signing\Rsa;

use LGrevelink\SimpleJWT\Signing\Rsa\RsaSha256;
use Tests\Signing\RsaTest;

final class RsaSha256Test extends RsaTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->rsa = new RsaSha256();
    }

    public function testAlgortithm()
    {
        $this->assertSame(OPENSSL_ALGO_SHA256, $this->rsa->getAlgorithm());
    }

    public function testAlgortithmId()
    {
        $this->assertSame('RS256', $this->rsa->getAlgorithmId());
    }
}
