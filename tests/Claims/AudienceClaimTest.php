<?php

namespace Tests\Claims;

use LGrevelink\SimpleJWT\Claims\AudienceClaim;
use Tests\TestCase;

final class AudienceClaimTest extends TestCase
{
    public function testName()
    {
        $claim = new AudienceClaim();

        $this->assertSame($claim->name(), 'aud');
    }

    public function testValidate()
    {
        $claim = new AudienceClaim('some_value');

        $this->assertTrue($claim->validate('some_value'));
        $this->assertFalse($claim->validate('some_other_value'));
    }
}
