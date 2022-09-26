<?php

namespace LGrevelink\SimpleJWT\Concerns;

use JsonException;
use LGrevelink\SimpleJWT\Exceptions\Token\InvalidFormatException;
use LGrevelink\SimpleJWT\Token;

trait ParsesTokens
{
    /**
     * Decodes data with a MIME base64 which is URL safe.
     *
     * @param string $data
     *
     * @throws InvalidFormatException
     *
     * @return string
     */
    protected static function base64UrlDecode(string $data)
    {
        $decodedData = base64_decode(strtr($data, '-_', '+/'), true);

        if (!$decodedData) {
            throw new InvalidFormatException('Failed databag decoding');
        }

        return $decodedData;
    }

    /**
     * Decodes a stringified DataBag representation to an object.
     *
     * @param string $data
     *
     * @throws InvalidFormatException
     *
     * @return array
     */
    protected static function decodeDataBag(string $data)
    {
        try {
            return json_decode(self::base64UrlDecode($data), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new InvalidFormatException('Failed databag parsing');
        }
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
