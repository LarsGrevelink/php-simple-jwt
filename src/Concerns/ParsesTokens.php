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
     * @return string
     */
    protected static function base64UrlDecode(string $data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Decodes an encoded base64 data bag to its former self.
     *
     * @param string $data
     *
     * @return array
     */
    protected static function decodeDataBag(string $data)
    {
        return json_decode(self::base64UrlDecode($data), true);
    }

    /**
     * Parse as JWT Token.
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
