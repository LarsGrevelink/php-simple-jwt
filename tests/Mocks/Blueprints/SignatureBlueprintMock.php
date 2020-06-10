<?php

namespace Tests\Mocks\Blueprints;

use LGrevelink\SimpleJWT\Signing\Hmac\HmacSha256;
use LGrevelink\SimpleJWT\TokenBlueprint;
use LGrevelink\SimpleJWT\TokenSignature;

class SignatureBlueprintMock extends TokenBlueprint
{
    /**
     * @inheritdoc
     */
    public static function signature(string $customKey = null)
    {
        return new TokenSignature(new HmacSha256(), $customKey ?? 'default-key');
    }
}
