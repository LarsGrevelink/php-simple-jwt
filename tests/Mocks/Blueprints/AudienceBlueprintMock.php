<?php

namespace Tests\Mocks\Blueprints;

use LGrevelink\SimpleJWT\TokenBlueprint;

class AudienceBlueprintMock extends TokenBlueprint
{
    /**
     * @inheritdoc
     */
    protected static $audience = 'Tests';
}
