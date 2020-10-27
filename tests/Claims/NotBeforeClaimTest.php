<?php

namespace Tests\Claims;

use LGrevelink\SimpleJWT\Claims\NotBeforeClaim;
use Tests\TestCase;

final class NotBeforeClaimTest extends TestCase
{
    public function testName()
    {
        $claim = new NotBeforeClaim();

        $this->assertSame($claim->name(), 'nbf');
    }

    public function testValidate()
    {
        $claim = new NotBeforeClaim();

        $now = time();
        $past = $now - 10;
        $future = $now + 10;

        $this->assertTrue($claim->validate($now));
        $this->assertTrue($claim->validate($past));
        $this->assertFalse($claim->validate($future));
    }
}
