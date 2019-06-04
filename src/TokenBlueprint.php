<?php

namespace LGrevelink\SimpleJWT;

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

        $blueprintClaims = self::getBlueprintClaims();

        foreach ($blueprintClaims as $claim => $value) {
            $methodName = self::getSetterMethod($claim);

            if (method_exists($token, $methodName)) {
                $token->{$methodName}($value);
            } else {
                $token->setPayload($claim, $value);
            }
        }

        return $token;
    }

    /**
     * Validate a token based on the blueprint.
     *
     * @param Token $token
     * @param array $claims (optional)
     *
     * @return bool
     */
    public static function validate(Token $token, $claims = [])
    {
        $blueprintClaims = self::getBlueprintClaims();
        $now = time();

        foreach ($blueprintClaims as $claim => $value) {
            $methodName = self::getGetterMethod($claim);
            $tokenValue = $token->{$methodName}();

            switch ($claim) {
                case 'audience':
                case 'issuer':
                case 'jwtId':
                case 'subject':
                    if ($tokenValue !== $value) {
                        return false;
                    }

                    break;
                case 'issuedAt':
                case 'notBefore':
                    if ($now < $tokenValue) {
                        return false;
                    }

                    break;
                case 'expirationTime':

                    if ($now > $tokenValue) {
                        return false;
                    }

                    break;
            }
        }

        foreach ($claims as $claim => $value) {
            if ($token->getPayload($claim) !== $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Retrieve the filled blueprint claims.
     *
     * @return array
     */
    protected static function getBlueprintClaims()
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
}
