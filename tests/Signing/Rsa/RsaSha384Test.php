<?php

namespace Tests\Signing\Rsa;

use LGrevelink\SimpleJWT\Signing\Rsa\RsaSha384;
use Tests\Signing\RsaTest;

final class RsaSha384Test extends RsaTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->rsa = new RsaSha384();
    }

    public function testAlgortithm()
    {
        $this->assertSame(OPENSSL_ALGO_SHA384, $this->rsa->getAlgorithm());
    }

    public function testAlgortithmId()
    {
        $this->assertSame('RS384', $this->rsa->getAlgorithmId());
    }
}
