<?php

namespace Tests;

use LGrevelink\SimpleJWT\Exceptions\Blueprint\SignatureNotImplementedException;
use LGrevelink\SimpleJWT\Signing\Hmac\HmacSha256;
use LGrevelink\SimpleJWT\TokenBlueprint;
use LGrevelink\SimpleJWT\TokenSignature;
use Tests\Mocks\Blueprints\AudienceBlueprintMock;
use Tests\Mocks\Blueprints\EmptyBlueprintMock;
use Tests\Mocks\Blueprints\ExpirationTimeBlueprintMock;
use Tests\Mocks\Blueprints\FullBlueprintMock;
use Tests\Mocks\Blueprints\IssuedAtBlueprintMock;
use Tests\Mocks\Blueprints\IssuerBlueprintMock;
use Tests\Mocks\Blueprints\JwtIdBlueprintMock;
use Tests\Mocks\Blueprints\NotBeforeBlueprintMock;
use Tests\Mocks\Blueprints\SignatureBlueprintMock;
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

    public function testGenerateAndSign()
    {
        $token = SignatureBlueprintMock::generateAndSign([
            'additional' => 'claim',
        ]);

        $this->assertSame($token->toString(), 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhZGRpdGlvbmFsIjoiY2xhaW0ifQ.fzZBLQfFRdMzDzJbgNc-0iVOi2UuyTLhFVZqgqUbUU0');

        $token = SignatureBlueprintMock::generateAndSign([
            'additional' => 'claim',
        ], 'custom-key');

        $this->assertSame($token->toString(), 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhZGRpdGlvbmFsIjoiY2xhaW0ifQ.C7bk4bT1GtU3q8ByI6dqhelAqEEzf4FqOpQjksUgsOo');

        $signature = SignatureBlueprintMock::signature('custom-key');
        $tokenVerification1 = SignatureBlueprintMock::generate([
            'additional' => 'claim',
        ])->sign($signature->signMethod(), $signature->signatureKey());

        $this->assertSame($token->toString(), $tokenVerification1->toString());

        $tokenVerification2 = SignatureBlueprintMock::generate([
            'additional' => 'claim',
        ])->sign(new HmacSha256(), 'custom-key');

        $this->assertSame($token->toString(), $tokenVerification2->toString());
    }

    public function testSignature()
    {
        $defaultValueSignature = SignatureBlueprintMock::signature();

        $this->assertInstanceOf(TokenSignature::class, $defaultValueSignature);
        $this->assertSame('default-key', $defaultValueSignature->signatureKey());

        $customValueSignature = SignatureBlueprintMock::signature('custom-key');

        $this->assertInstanceOf(TokenSignature::class, $customValueSignature);
        $this->assertSame('custom-key', $customValueSignature->signatureKey());
    }

    public function testSignatureWithoutOverride()
    {
        $this->expectException(SignatureNotImplementedException::class);
        $this->expectExceptionMessage(sprintf('Missing signature implementation on %s', EmptyBlueprintMock::class));

        EmptyBlueprintMock::signature(new TokenSignature(new HmacSha256(), 'key'));
        EmptyBlueprintMock::generateAndSign();
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

        $token->setIssuedAt(3600);

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

    public function testGetTokenValue()
    {
        $token = AudienceBlueprintMock::generate();

        $audienceValue = TestUtil::invokeStaticMethod(TokenBlueprint::class, 'getTokenValue', [$token, 'audience']);

        $this->assertSame('Tests', $audienceValue);

        $audienceValue = TestUtil::invokeStaticMethod(TokenBlueprint::class, 'getTokenValue', [$token, 'aud']);

        $this->assertSame('Tests', $audienceValue);

        $unknownValue = TestUtil::invokeStaticMethod(TokenBlueprint::class, 'getTokenValue', [$token, 'unknown']);

        $this->assertNull($unknownValue);
    }

    public function testValidateClaim()
    {
        $now = time();

        $expirationTimeTrue = TestUtil::invokeStaticMethod(TokenBlueprint::class, 'validateClaim', ['expirationTime', null, $now + 10]);
        $expirationTimeFalse = TestUtil::invokeStaticMethod(TokenBlueprint::class, 'validateClaim', ['expirationTime', null, $now - 10]);

        $this->assertTrue($expirationTimeTrue);
        $this->assertFalse($expirationTimeFalse);

        $issuedAtTrue = TestUtil::invokeStaticMethod(TokenBlueprint::class, 'validateClaim', ['issuedAt', null, $now - 10]);
        $issuedAtFalse = TestUtil::invokeStaticMethod(TokenBlueprint::class, 'validateClaim', ['issuedAt', null, $now + 10]);

        $this->assertTrue($issuedAtTrue);
        $this->assertFalse($issuedAtFalse);

        $notBeforeTrue = TestUtil::invokeStaticMethod(TokenBlueprint::class, 'validateClaim', ['notBefore', null, $now - 10]);
        $notBeforeFalse = TestUtil::invokeStaticMethod(TokenBlueprint::class, 'validateClaim', ['notBefore', null, $now + 10]);

        $this->assertTrue($notBeforeTrue);
        $this->assertFalse($notBeforeFalse);

        $othersTrue = TestUtil::invokeStaticMethod(TokenBlueprint::class, 'validateClaim', ['other', '12345', '12345']);
        $othersFalse = TestUtil::invokeStaticMethod(TokenBlueprint::class, 'validateClaim', ['other', '12345', 12345]);

        $this->assertTrue($othersTrue);
        $this->assertFalse($othersFalse);
    }
}
