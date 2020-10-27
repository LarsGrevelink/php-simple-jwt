<?php

namespace Tests\Claims;

use LGrevelink\SimpleJWT\Claims\JwtClaim;
use Tests\TestCase;
use Tests\TestUtil;

final class JwtClaimTest extends TestCase
{
    public function testBlueprintValue()
    {
        $value = 'some_value';

        $claim = $this->getMockForAbstractClass(JwtClaim::class, [$value]);

        $this->assertSame(TestUtil::getProperty($claim, 'blueprintValue'), $value);
        $this->assertSame($claim->getBlueprintValue(), $value);
    }
}
