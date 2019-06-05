<?php

namespace LGrevelink\SimpleJWT\Concerns;

use LGrevelink\SimpleJWT\Data\DataBag;

trait ComposesTokens
{
    /**
     * Encodes data with MIME base64 and makes sure it's URL safe.
     *
     * @param string $data
     *
     * @return string
     */
    protected function base64UrlEncode(string $data)
    {
        return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
    }

    /**
     * Encodes a DataBag object to a string.
     *
     * @param DataBag $bag
     *
     * @return string
     */
    protected function encodeDataBag(DataBag $bag)
    {
        return self::base64UrlEncode(
            json_encode($bag->all())
        );
    }

    /**
     * Composes a stringified version of the token.
     *
     * @param DataBag $header
     * @param DataBag $payload
     * @param string $signature (optional)
     *
     * @return string
     */
    protected function compose(DataBag $header, DataBag $payload, ?string $signature = null)
    {
        $signature = $signature ? $this->base64UrlEncode($signature) : '';

        return implode('.', [
            $this->encodeDataBag($header),
            $this->encodeDataBag($payload),
            $signature,
        ]);
    }
}
