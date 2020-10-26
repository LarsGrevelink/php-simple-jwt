<?php

namespace Tests\Claims;

use LGrevelink\SimpleJWT\Claims\ExpirationTimeClaim;
use Tests\TestCase;

final class ExpirationTimeClaimTest extends TestCase
{
    public function testName()
    {
        $claim = new ExpirationTimeClaim();

        $this->assertSame($claim->name(), 'exp');
    }

    public function testValidate()
    {
        $claim = new ExpirationTimeClaim();

        $now = time();
        $past = $now - 10;
        $future = $now + 10;

        $this->assertFalse($claim->validate($now));
        $this->assertFalse($claim->validate($past));
        $this->assertTrue($claim->validate($future));
    }
}
