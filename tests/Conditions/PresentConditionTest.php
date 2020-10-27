<?php

namespace Tests\Data;

use LGrevelink\SimpleJWT\Conditions\PresentCondition;
use Tests\TestCase;

final class PresentConditionTest extends TestCase
{
    public function testPasses()
    {
        $this->assertFalse(PresentCondition::passes(null));

        $this->assertTrue(PresentCondition::passes('some value'));
    }
}
