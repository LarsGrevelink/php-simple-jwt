<?php

namespace Tests\Mocks\Blueprints;

use LGrevelink\SimpleJWT\Signing\Hmac\HmacSha256;
use LGrevelink\SimpleJWT\TokenBlueprint;
use LGrevelink\SimpleJWT\TokenSignature;

/**
 * @method static \LGrevelink\SimpleJWT\Token generateAndSign(array $claims, string $customKey = null)
 * @method static \LGrevelink\SimpleJWT\Token sign(\LGrevelink\SimpleJWT\Token $token, string $customKey = null)
 * @method static bool verify(\LGrevelink\SimpleJWT\Token $token, string $customKey = null)
 */
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
