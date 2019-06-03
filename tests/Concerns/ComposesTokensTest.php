<?php

namespace Tests\Data;

use LGrevelink\SimpleJWT\Concerns\ComposesTokens;
use LGrevelink\SimpleJWT\Data\DataBag;
use Tests\TestCase;
use Tests\TestUtil;

final class ComposesTokensTest extends TestCase
{
    protected $trait;

    public function setUp()
    {
        $this->trait = $this->getMockForTrait(ComposesTokens::class);
    }

    public function testBase64UrlEncode()
    {
        $result = TestUtil::invokeMethod($this->trait, 'base64UrlEncode', [
            'very very very important data',
        ]);

        $this->assertSame('dmVyeSB2ZXJ5IHZlcnkgaW1wb3J0YW50IGRhdGE', $result);
    }

    public function testEncodeDataBag()
    {
        $result = TestUtil::invokeMethod($this->trait, 'encodeDataBag', [
            new DataBag(['header' => 'bag']),
        ]);

        $this->assertSame('eyJoZWFkZXIiOiJiYWcifQ', $result);
    }

    public function testCompose()
    {
        $result = TestUtil::invokeMethod($this->trait, 'compose', [
            new DataBag(['header' => 'bag']),
            new DataBag(['payload' => 'bag']),
        ]);

        $this->assertSame('eyJoZWFkZXIiOiJiYWcifQ.eyJwYXlsb2FkIjoiYmFnIn0.', $result);

        $result = TestUtil::invokeMethod($this->trait, 'compose', [
            new DataBag(['header' => 'bag']),
            new DataBag(['payload' => 'bag']),
            null,
        ]);

        $this->assertSame('eyJoZWFkZXIiOiJiYWcifQ.eyJwYXlsb2FkIjoiYmFnIn0.', $result);

        $result = TestUtil::invokeMethod($this->trait, 'compose', [
            new DataBag(['header' => 'bag']),
            new DataBag(['payload' => 'bag']),
            'Ωç√-signature',
        ]);

        $this->assertSame('eyJoZWFkZXIiOiJiYWcifQ.eyJwYXlsb2FkIjoiYmFnIn0.zqnDp-KImi1zaWduYXR1cmU', $result);
    }
}
