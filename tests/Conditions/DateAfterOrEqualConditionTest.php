<?php

namespace Tests\Data;

use LGrevelink\SimpleJWT\Conditions\DateAfterOrEqualCondition;
use Tests\TestCase;

final class DateAfterOrEqualConditionTest extends TestCase
{
    public function testPasses()
    {
        $now = time();
        $past = $now - 10;
        $future = $now + 10;

        $this->assertTrue(DateAfterOrEqualCondition::passes($now));

        $this->assertTrue(DateAfterOrEqualCondition::passes($past));

        $this->assertFalse(DateAfterOrEqualCondition::passes($future));
    }
}
