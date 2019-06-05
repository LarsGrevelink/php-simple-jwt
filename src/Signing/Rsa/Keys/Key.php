<?php

namespace LGrevelink\SimpleJWT\Signing\Rsa\Keys;

abstract class Key
{
    /**
     * Path to the RSA key file.
     *
     * @var string
     */
    protected $path;

    /**
     * Contents of the RSA key.
     *
     * @var string
     */
    protected $key;

    /**
     * Constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Gets the RSA key contents.
     *
     * @return string|null
     */
    public function getKey()
    {
        if ($this->key === null) {
            $this->key = $this->loadKey();
        }

        return $this->key;
    }

    /**
     * Loads the RSA key from the known path.
     *
     * @return string|null
     */
    public function loadKey()
    {
        if ($contents = file_get_contents($this->path)) {
            return $contents;
        }
    }
}
