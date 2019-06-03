<?php

namespace Tests\Mocks\Blueprints;

use LGrevelink\SimpleJWT\TokenBlueprint;

class SubjectBlueprintMock extends TokenBlueprint
{
    /**
     * @inheritdoc
     */
    protected static $subject = 'Test validation';
}
