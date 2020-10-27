<?php

namespace Tests\Mocks\Blueprints;

use LGrevelink\SimpleJWT\Token;
use LGrevelink\SimpleJWT\TokenBlueprint;

class CustomValidationBlueprint extends TokenBlueprint
{
    /**
     * @inheritdoc
     */
    public static function generate(array $claims = [])
    {
        return parent::generate(array_merge($claims, [
            // Custom claims
        ]));
    }

    /**
     * @inheritdoc
     */
    public static function validate(Token $token, array $claims = [])
    {
        return parent::validate($token, array_merge($claims, [
            // Custom claims
        ]));
    }
}
