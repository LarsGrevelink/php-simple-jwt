<?php

namespace Tests\Signing;

use LGrevelink\SimpleJWT\Signing\Hmac;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

abstract class HmacTest extends TestCase
{
    /**
     * @var Hmac|MockObject
     */
    protected $hmac;

    public function setUp(): void
    {
        $this->hmac = $this->getMockForAbstractClass(Hmac::class);

        $this->hmac->expects(self::any())
            ->method('getAlgorithm')
            ->willReturn('md5');
    }

    public function testSign()
    {
        $hash = hash_hmac($this->hmac->getAlgorithm(), 'test suite', 'test key', true);

        self::assertEquals($hash, $this->hmac->sign('test suite', 'test key'));
    }

    public function testVerify()
    {
        $hash = $this->hmac->sign('test suite', 'test key');

        self::assertFalse($this->hmac->verify($hash, 'invalid data', 'invalid key'));
        self::assertFalse($this->hmac->verify($hash, 'invalid data', 'test key'));
        self::assertFalse($this->hmac->verify($hash, 'test suite', 'invalid key'));
        self::assertTrue($this->hmac->verify($hash, 'test suite', 'test key'));
    }
}
