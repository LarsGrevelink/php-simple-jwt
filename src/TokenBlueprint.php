<?php

namespace LGrevelink\SimpleJWT;

use LGrevelink\SimpleJWT\Claims\JwtClaim;
use LGrevelink\SimpleJWT\Exceptions\Blueprint\SignatureNotImplementedException;

/**
 * Blueprint class for JWT tokens. Used to generate a basic token and pre-fill
 * registered claims.
 */
abstract class TokenBlueprint
{
    /**
     * The "audience" claim identifies the recipients that the JWT is intended for.
     *
     * @var string
     */
    protected static $audience;

    /**
     * The "expiration time" claim identifies the expiration time on or after which
     * the JWT MUST NOT be accepted for processing. Relative to the current time.
     *
     * @var int
     */
    protected static $expirationTime;

    /**
     * The "issued at" claim identifies the time at which the JWT was issued.
     *
     * @var int
     */
    protected static $issuedAt;

    /**
     * The "issuer" claim identifies the principal that issued the JWT.
     *
     * @var string
     */
    protected static $issuer;

    /**
     * The "JWT ID" claim provides a unique identifier for the JWT.
     *
     * @var mixed
     */
    protected static $jwtId;

    /**
     * The "not before" claim identifies the time before which the JWT MUST NOT be
     * accepted for processing. Relative to the current time.
     *
     * @var int
     */
    protected static $notBefore;

    /**
     * The "subject" claim identifies the principal that is the subject of the JWT.
     *
     * @var string
     */
    protected static $subject;

    /**
     * Generate a token based on the blueprint. Only iterates over the blueprint
     * variables.
     *
     * @param array $claims (optional)
     *
     * @return Token
     */
    public static function generate(array $claims = [])
    {
        $token = new Token($claims);

        $blueprintClaims = self::getBlueprintClaimValues();

        foreach ($blueprintClaims as $claim => $value) {
            $methodName = self::getSetterMethod($claim);

            if (method_exists($token, $methodName)) {
                $token->{$methodName}($value);
            }
        }

        return $token;
    }

    /**
     * Generate a token and sign it based on the blueprint.
     *
     * @param array $claims
     * @param ...$signatureArguments
     *
     * @return Token
     */
    public static function generateAndSign(array $claims = [])
    {
        $signatureArguments = array_slice(func_get_args(), 1);

        return static::sign(
            static::generate($claims),
            ...$signatureArguments
        );
    }

    /**
     * Generate a token and sign it based on the blueprint.
     *
     * @param Token $token
     * @param ...$signatureArguments
     *
     * @return Token
     */
    public static function sign(Token $token)
    {
        if (!method_exists(static::class, 'signature')) {
            throw new SignatureNotImplementedException(
                sprintf('Missing signature implementation on %s', static::class)
            );
        }

        $signatureArguments = array_slice(func_get_args(), 1);

        $signature = forward_static_call_array([static::class, 'signature'], $signatureArguments);

        return $token->sign(
            $signature->signMethod(),
            $signature->signatureKey()
        );
    }

    /**
     * Validate a token based on the blueprint.
     *
     * @param Token $token
     * @param ClaimContract[] $claims (optional)
     *
     * @return bool
     */
    public static function validate(Token $token, array $claims = [])
    {
        return $token->validate(
            array_merge(self::getBlueprintClaims(), $claims)
        );
    }

    /**
     * Verifies a token based on the blueprint.
     *
     * @param Token $token
     * @param ...$signatureArguments
     *
     * @return bool
     */
    public static function verify(Token $token)
    {
        if (!method_exists(static::class, 'signature')) {
            throw new SignatureNotImplementedException(
                sprintf('Missing signature implementation on %s', static::class)
            );
        }

        $signatureArguments = array_slice(func_get_args(), 1);

        $signature = forward_static_call_array([static::class, 'signature'], $signatureArguments);

        return $token->verify(
            $signature->signMethod(),
            $signature->signatureKey()
        );
    }

    protected static function getBlueprintClaims()
    {
        $claims = self::getBlueprintClaimValues();

        foreach ($claims as $name => $value) {
            $claimClass = self::getClaimClassName($name);

            $claims[$name] = new $claimClass($value);
        }

        return $claims;
    }

    /**
     * Retrieve the filled blueprint claims.
     *
     * @return array
     */
    protected static function getBlueprintClaimValues()
    {
        $blueprintClaims = array_keys(get_class_vars(self::class));
        $claims = [];

        foreach ($blueprintClaims as $name) {
            $value = static::$$name;

            if ($value !== null) {
                $claims[$name] = $value;
            }
        }

        return $claims;
    }

    /**
     * Try to guess the getter method for one of the variable names.
     *
     * @param string $name
     *
     * @return string
     */
    protected static function getGetterMethod(string $name)
    {
        return sprintf('get%s', ucfirst($name));
    }

    /**
     * Try to guess the setter method for one of the variable names.
     *
     * @param string $name
     *
     * @return string
     */
    protected static function getSetterMethod(string $name)
    {
        return sprintf('set%s', ucfirst($name));
    }

    /**
     * @param string $name
     */
    protected static function getClaimClassName(string $name)
    {
        return str_replace('\\JwtClaim', sprintf('\\%sClaim', ucfirst($name)), JwtClaim::class);
    }
}
