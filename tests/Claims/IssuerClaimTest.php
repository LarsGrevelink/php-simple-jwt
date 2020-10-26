<?php

namespace Tests\Claims;

use LGrevelink\SimpleJWT\Claims\IssuerClaim;
use Tests\TestCase;

final class IssuerClaimTest extends TestCase
{
    public function testName()
    {
        $claim = new IssuerClaim();

        $this->assertSame($claim->name(), 'iss');
    }

    public function testValidate()
    {
        $claim = new IssuerClaim('some_value');

        $this->assertTrue($claim->validate('some_value'));
        $this->assertFalse($claim->validate('some_other_value'));
    }
}
