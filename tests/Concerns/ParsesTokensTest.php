<?php

namespace Tests\Data;

use LGrevelink\SimpleJWT\Concerns\ParsesTokens;
use LGrevelink\SimpleJWT\Token;
use Tests\TestCase;
use Tests\TestUtil;

final class ParsesTokensTest extends TestCase
{
    protected $trait;

    public function setUp()
    {
        $this->trait = $this->getMockForTrait(ParsesTokens::class);
    }

    public function testBase64UrlDecode()
    {
        $result = TestUtil::invokeMethod($this->trait, 'base64UrlDecode', [
            'zqnDp-KImi1zaWduYXR1cmU',
        ]);

        $this->assertSame('Ωç√-signature', $result);
    }

    public function testDecodeDataBag()
    {
        $databag = TestUtil::invokeMethod($this->trait, 'decodeDataBag', [
            'eyJoZWFkZXIiOiJiYWcifQ',
        ]);

        $this->assertSame(['header' => 'bag'], $databag);
    }

    public function testParse()
    {
        $token = Token::parse('eyJoZWFkZXIiOiJiYWcifQ.eyJwYXlsb2FkIjoiYmFnIn0.zqnDp-KImi1zaWduYXR1cmU');

        $this->assertTrue($token instanceof Token);
    }
}
