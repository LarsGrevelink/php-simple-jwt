<?php

namespace Tests\Mocks\Blueprints;

use LGrevelink\SimpleJWT\TokenBlueprint;

class JwtIdBlueprintMock extends TokenBlueprint
{
    /**
     * @inheritdoc
     */
    protected static $jwtId = 'my-jwt-id';
}
