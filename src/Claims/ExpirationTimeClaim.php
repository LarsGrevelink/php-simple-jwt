<?php

namespace LGrevelink\SimpleJWT\Claims;

use LGrevelink\SimpleJWT\Conditions\DateBeforeCondition;

final class ExpirationTimeClaim extends JwtClaim
{
    public const JWT_CLAIM_NAME = 'exp';

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
        return DateBeforeCondition::passes($value, $this->blueprintValue);
    }
}
