<?php

namespace Tests\Signing;

use LGrevelink\SimpleJWT\Signing\Rsa;
use LGrevelink\SimpleJWT\Signing\Rsa\Keys\PrivateKey;
use LGrevelink\SimpleJWT\Signing\Rsa\Keys\PublicKey;
use Tests\Mocks\Signing\Rsa\Keys\PrivateKeyMock;
use Tests\Mocks\Signing\Rsa\Keys\PublicKeyMock;
use Tests\TestCase;
use Tests\TestUtil;

class RsaTest extends TestCase
{
    /**
     * @var MockObject|Rsa
     */
    protected $rsa;

    /**
     * @var PrivateKey
     */
    protected $privateKey;

    /**
     * @var PublicKey
     */
    protected $publicKey;

    public function setUp()
    {
        $this->privateKey = new PrivateKeyMock('path/to/private/key.pem');
        $this->publicKey = new PublicKeyMock('path/to/public/key.pem');

        $this->rsa = $this->getMockForAbstractClass(Rsa::class, [
            $this->privateKey,
            $this->publicKey,
        ]);

        $this->rsa->expects(self::any())
            ->method('getAlgorithm')
            ->willReturn(OPENSSL_ALGO_MD5);
    }

    public function testSetPrivateKey()
    {
        $this->assertSame($this->privateKey, TestUtil::getProperty($this->rsa, 'privateKey'));

        $privateKey = new PrivateKey('private key');

        $this->rsa->setPrivateKey($privateKey);

        $this->assertSame($privateKey, TestUtil::getProperty($this->rsa, 'privateKey'));
    }

    public function testSetPublicKey()
    {
        $this->assertSame($this->publicKey, TestUtil::getProperty($this->rsa, 'publicKey'));

        $publicKey = new PublicKey('public key');

        $this->rsa->setPublicKey($publicKey);

        $this->assertSame($publicKey, TestUtil::getProperty($this->rsa, 'publicKey'));
    }

    public function testSign()
    {
        $key = openssl_pkey_get_private($this->privateKey->getKey(), '');

        $signature = null;
        openssl_sign('test suite', $signature, $key, $this->rsa->getAlgorithm());

        $this->assertEquals($signature, $this->rsa->sign('test suite', ''));
    }

    public function testVerify()
    {
        $signature = $this->rsa->sign('test suite');

        self::assertFalse($this->rsa->verify($signature, 'invalid data'));
        self::assertTrue($this->rsa->verify($signature, 'test suite'));
    }
}
