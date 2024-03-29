<?php

namespace LGrevelink\SimpleJWT\Signing;

use LGrevelink\SimpleJWT\Exceptions\Signing\RsaSigningException;
use LGrevelink\SimpleJWT\Exceptions\Signing\RsaVerificationException;
use LGrevelink\SimpleJWT\Exceptions\SigningException;
use LGrevelink\SimpleJWT\Signing\Rsa\Keys\PrivateKey;
use LGrevelink\SimpleJWT\Signing\Rsa\Keys\PublicKey;

abstract class Rsa extends SigningMethod
{
    /**
     * The RSA private key.
     *
     * @var PrivateKey
     */
    protected $privateKey;

    /**
     * The RSA public key.
     *
     * @var PublicKey
     */
    protected $publicKey;

    /**
     * Constructor.
     *
     * @param PrivateKey $privateKey
     * @param PublicKey $publicKey
     */
    public function __construct(?PrivateKey $privateKey = null, ?PublicKey $publicKey = null)
    {
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
    }

    /**
     * Sets an RSA private key used for encryption.
     *
     * @param PrivateKey $privateKey
     *
     * @return $this
     */
    public function setPrivateKey(PrivateKey $privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * Sets an RSA public key used for decryption.
     *
     * @param PublicKey $publicKey
     *
     * @return $this
     */
    public function setPublicKey(PublicKey $publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @throws SigningException
     */
    public function sign(string $data, ?string $key = null)
    {
        if ($this->privateKey === null) {
            throw new RsaSigningException('A private key is needed for token signing');
        }

        $privateKey = openssl_pkey_get_private($this->privateKey->getKey(), $key ?? '');
        if ($privateKey === false) {
            throw new RsaSigningException('An error occurred while getting the private key; ' . openssl_error_string());
        }

        $signature = null;

        if (!openssl_sign($data, $signature, $privateKey, $this->getAlgorithm())) {
            throw new RsaSigningException('An error occurred while signing token');
        }

        return $signature;
    }

    /**
     * @inheritdoc
     *
     * @throws SigningException
     */
    public function verify(string $expected, string $data, ?string $key = null)
    {
        if ($this->publicKey === null) {
            throw new RsaVerificationException('A public key is needed for token verification');
        }

        $publicKey = openssl_pkey_get_public($this->publicKey->getKey());
        if ($publicKey === false) {
            throw new RsaVerificationException('An error occurred while getting the public key; ' . openssl_error_string());
        }

        return (bool) openssl_verify($data, $expected, $publicKey, $this->getAlgorithm());
    }

    /**
     * Retrieves the RSA algorithm.
     *
     * @return int
     */
    abstract public function getAlgorithm();
}
