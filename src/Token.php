<?php

namespace LGrevelink\SimpleJWT;

use LGrevelink\SimpleJWT\Claims\ExpirationTimeClaim;
use LGrevelink\SimpleJWT\Claims\IssuedAtClaim;
use LGrevelink\SimpleJWT\Claims\NotBeforeClaim;
use LGrevelink\SimpleJWT\Concerns\ComposesTokens;
use LGrevelink\SimpleJWT\Concerns\ParsesTokens;
use LGrevelink\SimpleJWT\Data\DataBag;
use LGrevelink\SimpleJWT\Exceptions\Token\DataGuardedException;
use LGrevelink\SimpleJWT\Signing\SigningMethod;

class Token
{
    use ParsesTokens,
        ComposesTokens;

    public const JWT_HEADER_ALGORITHM = 'alg';
    public const JWT_HEADER_ALGORITHM_NONE = 'none';
    public const JWT_HEADER_TYPE = 'typ';
    public const JWT_HEADER_TYPE_JWT = 'JWT';

    public const JWT_CLAIM_AUDIENCE = 'aud';
    public const JWT_CLAIM_EXPIRATION_TIME = 'exp';
    public const JWT_CLAIM_ISSUED_AT = 'iat';
    public const JWT_CLAIM_ISSUER = 'iss';
    public const JWT_CLAIM_JWT_ID = 'jti';
    public const JWT_CLAIM_NOT_BEFORE = 'nbf';
    public const JWT_CLAIM_SUBJECT = 'sub';

    /**
     * @var DataBag
     */
    protected $header;

    /**
     * @var DataBag
     */
    protected $payload;

    /**
     * @var string
     */
    protected $signature;

    /**
     * Makes a new token instance.
     *
     * @param array $payload (optional)
     * @param array $header (optional)
     * @param string $signature (optional)
     *
     * @return Token
     */
    public static function make(?array $payload = null, ?array $header = null, ?string $signature = null) {
        return new static($payload, $header, $signature);
    }

    /**
     * Constructor.
     *
     * @param array $payload (optional)
     * @param array $header (optional)
     * @param string $signature (optional)
     */
    public function __construct(?array $payload = null, ?array $header = null, ?string $signature = null)
    {
        $this->payload = new DataBag($payload ?? []);
        $this->header = new DataBag($header ?? [
            self::JWT_HEADER_TYPE => self::JWT_HEADER_TYPE_JWT,
            self::JWT_HEADER_ALGORITHM => self::JWT_HEADER_ALGORITHM_NONE,
        ]);
        $this->signature = $signature;
    }

    /**
     * Gets a claim on the token's payload.
     *
     * @param string $name
     * @param mixed $default (optional)
     *
     * @return mixed
     */
    public function getPayload(string $name, $default = null)
    {
        return $this->payload->get($name, $default);
    }

    /**
     * Gets the audience claim from the token's payload. This claim identifies the
     * recipient that the JWT is intended for.
     *
     * @return string|null
     */
    public function getAudience()
    {
        return $this->getPayload(self::JWT_CLAIM_AUDIENCE);
    }

    /**
     * Gets the "expiration time" claim from the token's payload. This claim identifies the
     * expiration time on or after which the JWT MUST NOT be accepted for processing.
     *
     * @return int|null
     */
    public function getExpirationTime()
    {
        return $this->getPayload(self::JWT_CLAIM_EXPIRATION_TIME);
    }

    /**
     * Gets the "issued at" claim from the token's payload. This claim identifies the
     * time at which the JWT was issued.
     *
     * @return int|null
     */
    public function getIssuedAt()
    {
        return $this->getPayload(self::JWT_CLAIM_ISSUED_AT);
    }

    /**
     * Gets the "issuer" claim from the token's payload. This claim identifies the principal
     * that issued the JWT.
     *
     * @return string|null
     */
    public function getIssuer()
    {
        return $this->getPayload(self::JWT_CLAIM_ISSUER);
    }

    /**
     * Gets the "JWT ID" claim from the token's payload. This claim provides a unique
     * identifier for the JWT.
     *
     * @return string|null
     */
    public function getJwtId()
    {
        return $this->getPayload(self::JWT_CLAIM_JWT_ID);
    }

    /**
     * Gets the "not before" claim from the token's payload. This claim identifies the time
     * before which the JWT MUST NOT be accepted for processing.
     *
     * @return int|null
     */
    public function getNotBefore()
    {
        return $this->getPayload(self::JWT_CLAIM_NOT_BEFORE);
    }

    /**
     * Gets the "subject" claim from the token's payload. This claim identifies the principal
     * that is the subject of the JWT. The claims in a JWT are normally statements about
     * the subject.
     *
     * @return string|null
     */
    public function getSubject()
    {
        return $this->getPayload(self::JWT_CLAIM_SUBJECT);
    }

    /**
     * Checks whether a claim exists on the token.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasPayload(string $name)
    {
        return $this->payload->has($name);
    }

    /**
     * Sets a claim on the token's payload.
     *
     * @param string $name
     * @param mixed $value
     *
     * @throws DataGuardedException
     *
     * @return $this
     */
    public function setPayload(string $name, $value)
    {
        if ($this->signature) {
            throw new DataGuardedException('Token needs to be unsigned before the payload can be changed');
        }

        $this->payload->set($name, $value);

        return $this;
    }

    /**
     * Sets the audience claim on the token's payload. This claim identifies the
     * recipients that the JWT is intended for.
     *
     * @param string $audience
     *
     * @return $this
     */
    public function setAudience(string $audience)
    {
        return $this->setPayload(self::JWT_CLAIM_AUDIENCE, $audience);
    }

    /**
     * Sets the "expiration time" claim on the token's payload. This claim identifies the
     * expiration time on or after which the JWT MUST NOT be accepted for processing.
     *
     * @param int $expirationTime
     * @param bool $relative (optional)
     *
     * @return $this
     */
    public function setExpirationTime(int $expirationTime, bool $relative = true)
    {
        return $this->setPayload(self::JWT_CLAIM_EXPIRATION_TIME, $this->convertRelativeTime($expirationTime, $relative));
    }

    /**
     * Sets the "issued at" claim on the token's payload. This claim identifies the
     * time at which the JWT was issued.
     *
     * @param int $issuedAt
     * @param bool $relative (optional)
     *
     * @return $this
     */
    public function setIssuedAt(int $issuedAt = 0, bool $relative = true)
    {
        return $this->setPayload(self::JWT_CLAIM_ISSUED_AT, $this->convertRelativeTime($issuedAt, $relative));
    }

    /**
     * Sets the "issuer" claim on the token's payload. This claim identifies the principal
     * that issued the JWT.
     *
     * @param string $issuer
     *
     * @return $this
     */
    public function setIssuer(string $issuer)
    {
        return $this->setPayload(self::JWT_CLAIM_ISSUER, $issuer);
    }

    /**
     * Sets the "JWT ID" claim on the token's payload. This claim provides a unique
     * identifier for the JWT.
     *
     * @param string $jwtId
     *
     * @return $this
     */
    public function setJwtId(string $jwtId)
    {
        return $this->setPayload(self::JWT_CLAIM_JWT_ID, $jwtId);
    }

    /**
     * Sets the "not before" claim on the token's payload. This claim identifies the time
     * before which the JWT MUST NOT be accepted for processing.
     *
     * @param int $notBefore
     * @param bool $relative (optional)
     *
     * @return $this
     */
    public function setNotBefore(int $notBefore, bool $relative = true)
    {
        return $this->setPayload(self::JWT_CLAIM_NOT_BEFORE, $this->convertRelativeTime($notBefore, $relative));
    }

    /**
     * Sets the "subject" claim on the token's payload. This claim identifies the principal
     * that is the subject of the JWT. The claims in a JWT are normally statements about
     * the subject.
     *
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject(string $subject)
    {
        return $this->setPayload(self::JWT_CLAIM_SUBJECT, $subject);
    }

    /**
     * Signs the token with a given signing method.
     *
     * @param SigningMethod $method
     * @param string $key
     *
     * @return $this
     */
    public function sign(SigningMethod $method, string $key)
    {
        $this->header->set(self::JWT_HEADER_ALGORITHM, $method->getAlgorithmId());

        $data = rtrim($this->compose($this->header, $this->payload), '.');

        $this->signature = $method->sign($data, $key);

        return $this;
    }

    /**
     * Signs the token with the given signature.
     *
     * @todo Decide whether this small utility belongs here due to the tight integration of
     * signatures and blueprints. Might be better to keep these functionalities in the blueprint
     * part of the package while keeping the token fully standalone.
     *
     * @param TokenSignature $signature
     *
     * @return $this
     */
    public function signature(TokenSignature $signature)
    {
        return $this->sign($signature->signMethod(), $signature->signatureKey());
    }

    /**
     * Validates the token against the default and custom conditions when given.
     *
     * @param ClaimContract[] $claims
     *
     * @return bool
     */
    public function validate(array $claims = [])
    {
        if ($this->hasPayload(ExpirationTimeClaim::JWT_CLAIM_NAME)) {
            $claims[] = new ExpirationTimeClaim();
        }

        if ($this->hasPayload(IssuedAtClaim::JWT_CLAIM_NAME)) {
            $claims[] = new IssuedAtClaim();
        }

        if ($this->hasPayload(NotBeforeClaim::JWT_CLAIM_NAME)) {
            $claims[] = new NotBeforeClaim();
        }

        foreach ($claims as $claim) {
            if (!$claim->validate($this->getPayload($claim->name()))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifies the token against its current signature.
     *
     * @param SigningMethod $method
     * @param string $key
     *
     * @return bool
     */
    public function verify(SigningMethod $method, string $key)
    {
        $data = rtrim($this->compose($this->header, $this->payload), '.');

        return $method->verify($this->signature, $data, $key);
    }

    /**
     * Unsigns the token.
     *
     * @return $this
     */
    public function unsign()
    {
        $this->header->set(self::JWT_HEADER_ALGORITHM, self::JWT_HEADER_ALGORITHM_NONE);
        $this->signature = null;

        return $this;
    }

    /**
     * Composes the token's textual representation.
     *
     * @return string
     */
    public function toString()
    {
        return $this->compose($this->header, $this->payload, $this->signature);
    }

    /**
     * Returns the stringified version of the token class.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Converts a possible relative time to a timestamp.
     *
     * @param int $seconds
     * @param bool $relative
     *
     * @return int
     */
    protected function convertRelativeTime(int $seconds, bool $relative)
    {
        return $relative ? time() + $seconds : $seconds;
    }
}
