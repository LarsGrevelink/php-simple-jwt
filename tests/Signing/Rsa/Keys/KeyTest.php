<?php

namespace Tests\Signing\Rsa\Keys;

use LGrevelink\SimpleJWT\Signing\Rsa\Keys\Key;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\TestUtil;

class KeyTest extends TestCase
{
    /**
     * @var Key|MockObject
     */
    protected $key;

    public function setUp(): void
    {
        $this->key = $this->getMockBuilder(Key::class)->setConstructorArgs(['path/to/key.pem'])->setMethods(['loadKey'])->getMockForAbstractClass();
    }

    public function testConstructor()
    {
        $this->assertSame('path/to/key.pem', TestUtil::getProperty($this->key, 'path'));
    }

    public function testGetKey()
    {
        $this->key->expects(self::any())
            ->method('loadKey')
            ->willReturn('preset key');

        $this->assertSame('preset key', $this->key->getKey());
    }

    public function testLoadKey()
    {
        $key = $this->getMockBuilder(Key::class)->setConstructorArgs([__FILE__])->getMockForAbstractClass();

        $this->assertSame(file_get_contents(__FILE__), $key->getKey());
    }

    public function testLoadKeyInvalid()
    {
        $this->assertNull($this->key->getKey());
    }
}
