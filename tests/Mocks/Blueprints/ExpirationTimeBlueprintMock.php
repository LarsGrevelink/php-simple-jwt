<?php

namespace Tests\Mocks\Blueprints;

use LGrevelink\SimpleJWT\TokenBlueprint;

class ExpirationTimeBlueprintMock extends TokenBlueprint
{
    /**
     * @inheritdoc
     */
    protected static $expirationTime = 3600;
}
