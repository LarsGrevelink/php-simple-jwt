<?php

namespace LGrevelink\SimpleJWT\Claims;

use LGrevelink\SimpleJWT\Conditions\DateAfterOrEqualCondition;

final class IssuedAtClaim extends JwtClaim
{
    public const JWT_CLAIM_NAME = 'iat';

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
        return DateAfterOrEqualCondition::passes($value, $this->blueprintValue);
    }
}
