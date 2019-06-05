<?php

namespace Tests\Mocks\Signing\Rsa\Keys;

use LGrevelink\SimpleJWT\Signing\Rsa\Keys\PrivateKey;

class PrivateKeyInvalidMock extends PrivateKey
{
    protected $key = 'invalid private key';
}
