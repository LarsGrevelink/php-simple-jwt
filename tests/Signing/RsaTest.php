<?php

namespace Tests\Signing;

use LGrevelink\SimpleJWT\Exceptions\SigningException;
use LGrevelink\SimpleJWT\Exceptions\VerificationException;
use LGrevelink\SimpleJWT\Signing\Rsa;
use LGrevelink\SimpleJWT\Signing\Rsa\Keys\PrivateKey;
use LGrevelink\SimpleJWT\Signing\Rsa\Keys\PublicKey;
use Tests\Mocks\Signing\Rsa\Keys\PrivateKeyInvalidMock;
use Tests\Mocks\Signing\Rsa\Keys\PrivateKeyMock;
use Tests\Mocks\Signing\Rsa\Keys\PublicKeyInvalidMock;
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

    public function setUp(): void
    {
        $this->privateKey = new PrivateKeyMock('path/to/private/key.pem');
        $this->publicKey = new PublicKeyMock('path/to/public/key.pem');

        $this->rsa = $this->getMockForAbstractClass(Rsa::class);
        $this->rsa->expects(self::any())
            ->method('getAlgorithm')
            ->willReturn(OPENSSL_ALGO_MD5);
    }

    public function testConstructor()
    {
        $rsa = $this->getMockForAbstractClass(Rsa::class, [
            $this->privateKey,
            $this->publicKey,
        ]);

        $this->assertSame($this->privateKey, TestUtil::getProperty($rsa, 'privateKey'));
        $this->assertSame($this->publicKey, TestUtil::getProperty($rsa, 'publicKey'));
    }

    public function testSetPrivateKey()
    {
        $this->assertNull(TestUtil::getProperty($this->rsa, 'privateKey'));

        $this->rsa->setPrivateKey($this->privateKey);

        $this->assertSame($this->privateKey, TestUtil::getProperty($this->rsa, 'privateKey'));
    }

    public function testSetPublicKey()
    {
        $this->assertNull(TestUtil::getProperty($this->rsa, 'publicKey'));

        $this->rsa->setPublicKey($this->publicKey);

        $this->assertSame($this->publicKey, TestUtil::getProperty($this->rsa, 'publicKey'));
    }

    public function testSign()
    {
        $this->rsa->setPrivateKey($this->privateKey);

        $key = openssl_pkey_get_private($this->privateKey->getKey(), '');

        $signature = null;
        openssl_sign('test suite', $signature, $key, $this->rsa->getAlgorithm());

        $this->assertEquals($signature, $this->rsa->sign('test suite', ''));
    }

    public function testSignNoPrivateKeyException()
    {
        $this->expectException(SigningException::class);
        $this->expectExceptionMessage('A private key is needed for RSA token signing');

        $this->rsa->sign('test suite');
    }

    public function testSignGetPrivateKeyException()
    {
        $this->expectException(SigningException::class);
        $this->expectExceptionMessageRegExp('/An error occurred while getting the RSA private key; .*/');

        $this->rsa->setPrivateKey(new PrivateKeyInvalidMock('invalid/key.pem'));

        $this->rsa->sign('test suite');
    }

    public function testVerify()
    {
        $this->rsa->setPrivateKey($this->privateKey);

        $signature = $this->rsa->sign('test suite');

        $this->rsa->setPublicKey($this->publicKey);

        self::assertFalse($this->rsa->verify($signature, 'invalid data'));
        self::assertTrue($this->rsa->verify($signature, 'test suite'));
    }

    public function testVerifyNoPublicKeyException()
    {
        $this->expectException(VerificationException::class);
        $this->expectExceptionMessage('A public key is needed for RSA token verification');

        $this->rsa->verify('signature', 'test suite');
    }

    public function testVerifyGetPrivateKeyException()
    {
        $this->expectException(VerificationException::class);
        $this->expectExceptionMessageRegExp('/An error occurred while getting the RSA public key; .*/');

        $this->rsa->setPublicKey(new PublicKeyInvalidMock('invalid/key.pem'));

        $this->rsa->verify('signature', 'test suite');
    }
}
