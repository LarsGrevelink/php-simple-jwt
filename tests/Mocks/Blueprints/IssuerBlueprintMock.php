<?php

namespace Tests\Mocks\Blueprints;

use LGrevelink\SimpleJWT\TokenBlueprint;

class IssuerBlueprintMock extends TokenBlueprint
{
    /**
     * @inheritdoc
     */
    protected static $issuer = 'Test suite';
}
