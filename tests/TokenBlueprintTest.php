<?php

namespace Tests;

use LGrevelink\SimpleJWT\TokenBlueprint;
use Tests\Mocks\Blueprints\AudienceBlueprintMock;
use Tests\Mocks\Blueprints\EmptyBlueprintMock;
use Tests\Mocks\Blueprints\ExpirationTimeBlueprintMock;
use Tests\Mocks\Blueprints\FullBlueprintMock;
use Tests\Mocks\Blueprints\IssuedAtBlueprintMock;
use Tests\Mocks\Blueprints\IssuerBlueprintMock;
use Tests\Mocks\Blueprints\JwtIdBlueprintMock;
use Tests\Mocks\Blueprints\NotBeforeBlueprintMock;
use Tests\Mocks\Blueprints\SubjectBlueprintMock;

final class TokenBlueprintTest extends TestCase
{
    public function testGetBlueprintClaims()
    {
        $claims = TestUtil::invokeStaticMethod(FullBlueprintMock::class, 'getBlueprintClaims');

        $this->assertSame([
            'audience' => 'Tests',
            'expirationTime' => 3600,
            'issuedAt' => 0,
            'issuer' => 'Test suite',
            'jwtId' => 'my-jwt-id',
            'notBefore' => 0,
            'subject' => 'Test validation',
        ], $claims);
    }

    public function testGetGetterMethod()
    {
        $getter = TestUtil::invokeStaticMethod(TokenBlueprint::class, 'getGetterMethod', ['someClaim']);

        $this->assertSame('getSomeClaim', $getter);
    }

    public function testGetSetterMethod()
    {
        $setter = TestUtil::invokeStaticMethod(TokenBlueprint::class, 'getSetterMethod', ['someClaim']);

        $this->assertSame('setSomeClaim', $setter);
    }

    public function testGenerate()
    {
        $time = time();

        $token = FullBlueprintMock::generate([
            'additional' => 'claim',
        ]);

        // Static claims
        $this->assertSame(TestUtil::getStaticProperty(FullBlueprintMock::class, 'audience'), $token->getAudience());
        $this->assertSame(TestUtil::getStaticProperty(FullBlueprintMock::class, 'issuer'), $token->getIssuer());
        $this->assertSame(TestUtil::getStaticProperty(FullBlueprintMock::class, 'jwtId'), $token->getJwtId());
        $this->assertSame(TestUtil::getStaticProperty(FullBlueprintMock::class, 'subject'), $token->getSubject());

        // Relative time claims
        $this->assertSame(
            TestUtil::getStaticProperty(FullBlueprintMock::class, 'expirationTime') + $time,
            $token->getExpirationTime()
        );

        $this->assertSame(
            TestUtil::getStaticProperty(FullBlueprintMock::class, 'issuedAt') + $time,
            $token->getIssuedAt()
        );

        $this->assertSame(
            TestUtil::getStaticProperty(FullBlueprintMock::class, 'notBefore') + $time,
            $token->getNotBefore()
        );

        // Custom claims
        $this->assertSame('claim', $token->getPayload('additional'));
    }

    public function testValidateAudience()
    {
        $token = AudienceBlueprintMock::generate();

        $this->assertTrue(AudienceBlueprintMock::validate($token));

        $token->setAudience('Invalid audience');

        $this->assertFalse(AudienceBlueprintMock::validate($token));
    }

    public function testValidateExpirationTime()
    {
        $token = ExpirationTimeBlueprintMock::generate();

        $this->assertTrue(ExpirationTimeBlueprintMock::validate($token));

        $token->setExpirationTime(-3600);

        $this->assertFalse(ExpirationTimeBlueprintMock::validate($token));
    }

    public function testValidateIssuedAt()
    {
        $token = IssuedAtBlueprintMock::generate();

        $this->assertTrue(IssuedAtBlueprintMock::validate($token));

        $token->setIssuedAt(-3600);

        $this->assertFalse(IssuedAtBlueprintMock::validate($token));
    }

    public function testValidateIssuer()
    {
        $token = IssuerBlueprintMock::generate();

        $this->assertTrue(IssuerBlueprintMock::validate($token));

        $token->setIssuer('Invalid issuer');

        $this->assertFalse(IssuerBlueprintMock::validate($token));
    }

    public function testValidateJwtId()
    {
        $token = JwtIdBlueprintMock::generate();

        $this->assertTrue(JwtIdBlueprintMock::validate($token));

        $token->setJwtId('Invalid JWT ID');

        $this->assertFalse(JwtIdBlueprintMock::validate($token));
    }

    public function testValidateNotBefore()
    {
        $token = NotBeforeBlueprintMock::generate();

        $this->assertTrue(NotBeforeBlueprintMock::validate($token));

        $token->setNotBefore(3600);

        $this->assertFalse(NotBeforeBlueprintMock::validate($token));
    }

    public function testValidateSubject()
    {
        $token = SubjectBlueprintMock::generate();

        $this->assertTrue(SubjectBlueprintMock::validate($token));

        $token->setSubject('Invalid subject');

        $this->assertFalse(SubjectBlueprintMock::validate($token));
    }

    public function testValidateCustomClaims()
    {
        $token = EmptyBlueprintMock::generate([
            'some' => 'claim',
        ]);

        $this->assertTrue(EmptyBlueprintMock::validate($token, [
            'some' => 'claim',
        ]));

        $this->assertFalse(EmptyBlueprintMock::validate($token, [
            'some' => 'other claim',
        ]));
    }
}
