<?php

namespace Tests\Mocks\Blueprints;

use LGrevelink\SimpleJWT\TokenBlueprint;

class IssuedAtBlueprintMock extends TokenBlueprint
{
    /**
     * @inheritdoc
     */
    protected static $issuedAt = 0;
}
