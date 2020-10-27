<?php

namespace Tests\Mocks\Claims;

use LGrevelink\SimpleJWT\Claims\JwtClaim;
use LGrevelink\SimpleJWT\Conditions\EqualCondition;

final class SomeClaimMock extends JwtClaim
{
    public const JWT_CLAIM_NAME = 'some_claim';

    /**
     * @inheritdoc
     */
    public function name()
    {
        return self::JWT_CLAIM_NAME;
    }

    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        return EqualCondition::passes($value, $this->blueprintValue);
    }
}
