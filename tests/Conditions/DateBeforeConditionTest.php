<?php

namespace Tests\Data;

use LGrevelink\SimpleJWT\Conditions\DateBeforeCondition;
use Tests\TestCase;

final class DateBeforeConditionTest extends TestCase
{
    public function testPasses()
    {
        $now = time();
        $past = $now - 10;
        $future = $now + 10;

        $this->assertFalse(DateBeforeCondition::passes($now));

        $this->assertFalse(DateBeforeCondition::passes($past));

        $this->assertTrue(DateBeforeCondition::passes($future));
    }
}
