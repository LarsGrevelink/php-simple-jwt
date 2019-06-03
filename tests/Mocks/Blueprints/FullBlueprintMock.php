<?php

namespace Tests\Mocks\Blueprints;

use LGrevelink\SimpleJWT\TokenBlueprint;

class FullBlueprintMock extends TokenBlueprint
{
    /**
     * Non standard claim which should not be automatically added.
     *
     * @var string
     */
    protected static $helloWorld = 'Hello World!';

    /**
     * @inheritdoc
     */
    protected static $audience = 'Tests';

    /**
     * @inheritdoc
     */
    protected static $expirationTime = 3600;

    /**
     * @inheritdoc
     */
    protected static $issuedAt = 0;

    /**
     * @inheritdoc
     */
    protected static $issuer = 'Test suite';

    /**
     * @inheritdoc
     */
    protected static $jwtId = 'my-jwt-id';

    /**
     * @inheritdoc
     */
    protected static $notBefore = 0;

    /**
     * @inheritdoc
     */
    protected static $subject = 'Test validation';
}
