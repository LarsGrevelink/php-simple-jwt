<?php

namespace Tests\Data;

use LGrevelink\SimpleJWT\Conditions\EqualCondition;
use Tests\TestCase;

final class EqualConditionTest extends TestCase
{
    public function testPasses()
    {
        $this->assertTrue(EqualCondition::passes(null, null));

        $this->assertTrue(EqualCondition::passes('some value', 'some value'));

        $this->assertFalse(EqualCondition::passes('some value', 'some different value'));
    }
}
