<?php

namespace Tests\Signing\Rsa;

use LGrevelink\SimpleJWT\Signing\Rsa\RsaSha512;
use Tests\Signing\RsaTest;

final class RsaSha512Test extends RsaTest
{
    public function setUp()
    {
        parent::setUp();

        $this->rsa = new RsaSha512($this->privateKey, $this->publicKey);
    }

    public function testAlgortithm()
    {
        $this->assertSame(OPENSSL_ALGO_SHA512, $this->rsa->getAlgorithm());
    }

    public function testAlgortithmId()
    {
        $this->assertSame('RS512', $this->rsa->getAlgorithmId());
    }
}
