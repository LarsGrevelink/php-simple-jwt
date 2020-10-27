<?php

namespace Tests\Data;

use Tests\Mocks\Conditions\DefaultConditionMock;
use Tests\Mocks\Conditions\FailsConditionMock;
use Tests\Mocks\Conditions\PassesConditionMock;
use Tests\TestCase;

final class JwtConditionTest extends TestCase
{
    public function testPasses()
    {
        $this->assertFalse(DefaultConditionMock::passes('some value', 'some initial value'));

        $this->assertTrue(PassesConditionMock::passes('some value', 'some initial value'));

        $this->assertFalse(FailsConditionMock::passes('some value', 'some initial value'));
    }
}
