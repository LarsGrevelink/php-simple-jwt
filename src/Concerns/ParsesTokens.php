<?php

namespace LGrevelink\SimpleJWT\Concerns;

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
     * @return Token
     */
    public static function parse(string $token)
    {
        [$header, $payload, $signature] = explode('.', trim($token, '.'));

        return new Token(
            $payload ? self::decodeDataBag($payload) : null,
            $header ? self::decodeDataBag($header) : null,
            $signature ? self::base64UrlDecode($signature) : null
        );
    }
}
