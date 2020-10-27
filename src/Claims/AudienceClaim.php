<?php

namespace LGrevelink\SimpleJWT\Claims;

use LGrevelink\SimpleJWT\Conditions\EqualCondition;

final class AudienceClaim extends JwtClaim
{
    public const JWT_CLAIM_NAME = 'aud';

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
