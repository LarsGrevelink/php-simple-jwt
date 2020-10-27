<?php

namespace Tests\Claims;

use LGrevelink\SimpleJWT\Claims\SubjectClaim;
use Tests\TestCase;

final class SubjectClaimTest extends TestCase
{
    public function testName()
    {
        $claim = new SubjectClaim();

        $this->assertSame($claim->name(), 'sub');
    }

    public function testValidate()
    {
        $claim = new SubjectClaim('some_value');

        $this->assertTrue($claim->validate('some_value'));
        $this->assertFalse($claim->validate('some_other_value'));
    }
}
