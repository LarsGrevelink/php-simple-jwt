<?php

namespace Tests\Claims;

use LGrevelink\SimpleJWT\Claims\JwtIdClaim;
use Tests\TestCase;

final class JwtIdClaimTest extends TestCase
{
    public function testName()
    {
        $claim = new JwtIdClaim();

        $this->assertSame($claim->name(), 'jti');
    }

    public function testValidate()
    {
        $claim = new JwtIdClaim('some_value');

        $this->assertTrue($claim->validate('some_value'));
        $this->assertFalse($claim->validate('some_other_value'));
    }
}
