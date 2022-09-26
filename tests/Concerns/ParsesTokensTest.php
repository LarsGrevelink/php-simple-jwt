<?php

namespace Tests\Data;

use LGrevelink\SimpleJWT\Concerns\ParsesTokens;
use LGrevelink\SimpleJWT\Exceptions\Token\InvalidFormatException;
use LGrevelink\SimpleJWT\Signing\Hmac\HmacSha256;
use LGrevelink\SimpleJWT\Token;
use Tests\TestCase;
use Tests\TestUtil;

final class ParsesTokensTest extends TestCase
{
    protected $trait;

    public function setUp(): void
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

    public function testBase64UrlDecodeInvalidFormat()
    {
        $this->expectException(InvalidFormatException::class);
        $this->expectExceptionMessage('Failed databag decoding');

        TestUtil::invokeMethod($this->trait, 'base64UrlDecode', [
            'Ωç√-string-which-fails-base64-decoding',
        ]);
    }

    public function testDecodeDataBag()
    {
        $databag = TestUtil::invokeMethod($this->trait, 'decodeDataBag', [
            'eyJoZWFkZXIiOiJiYWcifQ',
        ]);

        $this->assertSame(['header' => 'bag'], $databag);
    }

    public function testDecodeDataBagInvalidFormat()
    {
        $this->expectException(InvalidFormatException::class);
        $this->expectExceptionMessage('Failed databag parsing');

        TestUtil::invokeMethod($this->trait, 'decodeDataBag', [
            'string-which-fails-json-decoding',
        ]);
    }

    public function testParse()
    {
        $token = Token::parse('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhdWQiOiJKV1QgdGVzdHMiLCJpc3MiOiJMYXJyeSBDb29rIiwidXJsIjoiaHR0cHM6Ly9naXRodWIuY29tL0xhcnNHcmV2ZWxpbmsvcGhwLXNpbXBsZS1qd3QifQ.jJoVlDemNwfvRHvjN8qv77RXzoTzA3BeV3bQkacYvYA');

        $this->assertTrue($token instanceof Token);
        $this->assertTrue($token->verify(new HmacSha256(), 'your-256-bit-secret'));
    }

    public function testParseInvalidFormat()
    {
        $this->expectException(InvalidFormatException::class);
        $this->expectExceptionMessage('Invalid token format');

        Token::parse('definitely-not-a-JWT');
    }
}
