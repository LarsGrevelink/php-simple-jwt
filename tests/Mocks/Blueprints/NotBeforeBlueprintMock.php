<?php

namespace Tests\Mocks\Blueprints;

use LGrevelink\SimpleJWT\TokenBlueprint;

class NotBeforeBlueprintMock extends TokenBlueprint
{
    /**
     * @inheritdoc
     */
    protected static $notBefore = 0;
}
