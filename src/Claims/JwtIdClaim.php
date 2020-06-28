<?php

namespace LGrevelink\SimpleJWT\Claims;

use LGrevelink\SimpleJWT\Conditions\EqualCondition;

final class JwtIdClaim extends JwtClaim
{
    public const JWT_CLAIM_NAME = 'jti';

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
