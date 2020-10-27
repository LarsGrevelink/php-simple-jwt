<?php

namespace Tests\Claims;

use LGrevelink\SimpleJWT\Claims\IssuedAtClaim;
use Tests\TestCase;

final class IssuedAtClaimTest extends TestCase
{
    public function testName()
    {
        $claim = new IssuedAtClaim();

        $this->assertSame($claim->name(), 'iat');
    }

    public function testValidate()
    {
        $claim = new IssuedAtClaim();

        $now = time();
        $past = $now - 10;
        $future = $now + 10;

        $this->assertTrue($claim->validate($now));
        $this->assertTrue($claim->validate($past));
        $this->assertFalse($claim->validate($future));
    }
}
