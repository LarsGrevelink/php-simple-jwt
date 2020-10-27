<?php

namespace LGrevelink\SimpleJWT\Claims;

use LGrevelink\SimpleJWT\Contracts\ClaimContract;

abstract class JwtClaim implements ClaimContract
{
    /**
     * Preset value of the claim in a blueprint.
     *
     * @var mixed
     */
    protected $blueprintValue;

    /**
     * Constructor.
     *
     * @param mixed|null $blueprintValue
     */
    public function __construct($blueprintValue = null)
    {
        $this->blueprintValue = $blueprintValue;
    }

    /**
     * Gets the blueprint value.
     *
     * @return mixed
     */
    public function getBlueprintValue()
    {
        return $this->blueprintValue;
    }
}
