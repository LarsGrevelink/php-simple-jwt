<?php

namespace Tests\Mocks\Signing\Rsa\Keys;

use LGrevelink\SimpleJWT\Signing\Rsa\Keys\PublicKey;

class PublicKeyInvalidMock extends PublicKey
{
    protected $key = 'invalid public key';
}
