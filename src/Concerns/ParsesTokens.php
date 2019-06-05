<?php

namespace LGrevelink\SimpleJWT\Concerns;

use LGrevelink\SimpleJWT\Exceptions\InvalidFormatException;
use LGrevelink\SimpleJWT\Token;

trait ParsesTokens
{
    /**
     * Decodes data with a MIME base64 which is URL safe.
     *
     * @param string $data
     *
     * @return string|null
     */
    protected static function base64UrlDecode(string $data)
    {
        return base64_decode(strtr($data, '-_', '+/')) ?: null;
    }

    /**
     * Decodes a stringified DataBag representation to an object.
     *
     * @param string $data
     *
     * @return array|null
     */
    protected static function decodeDataBag(string $data)
    {
        return json_decode(self::base64UrlDecode($data), true);
    }

    /**
     * Parses a stringified Token representation to its former Token self.
     *
     * @param string $token
     *
     * @throws InvalidFormatException
     *
     * @return Token
     */
    public static function parse(string $token)
    {
        if (!preg_match('/^([A-z0-9-_]+)\.([A-z0-9-_]+)\.([A-z0-9-_]+)?$/', $token)) {
            throw new InvalidFormatException('Invalid token format');
        }

        [$header, $payload, $signature] = explode('.', $token);

        return new Token(
            self::decodeDataBag($payload),
            self::decodeDataBag($header),
            $signature ? self::base64UrlDecode($signature) : null
        );
    }
}
