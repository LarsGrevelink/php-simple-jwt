<?php

namespace Tests;

use LGrevelink\SimpleJWT\Exceptions\Blueprint\SignatureNotImplementedException;
use LGrevelink\SimpleJWT\Signing\Hmac\HmacSha256;
use LGrevelink\SimpleJWT\Token;
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
use Tests\Mocks\Claims\SomeClaimMock;

final class TokenBlueprintTest extends TestCase
{
    public function testGetBlueprintClaims()
    {
        $claims = TestUtil::invokeStaticMethod(FullBlueprintMock::class, 'getBlueprintClaimValues');

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

        // Make sure the new variables from the implementation layer do not bubble through
        $this->assertNull($token->getPayload('helloWorld'));
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
    }

    public function testGenerateAndSignWithoutSignatureOverride()
    {
        $this->expectException(SignatureNotImplementedException::class);
        $this->expectExceptionMessage(sprintf('Missing signature implementation on %s', EmptyBlueprintMock::class));

        EmptyBlueprintMock::generateAndSign();
    }

    public function testSign()
    {
        $token = SignatureBlueprintMock::sign(
            SignatureBlueprintMock::generate()
        );

        $this->assertSame($token->toString(), 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W10.IV6Dxx5iWfV76MH4XZw8Pf3upbPnkne-9mu7wrs76dI');

        $token = SignatureBlueprintMock::sign(
            SignatureBlueprintMock::generate(),
            'custom-key'
        );

        $this->assertSame($token->toString(), 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W10.ANuz1Fd6j5iIbJpRckRaKmRlqGdR_Cm6yfBfueHJZck');
    }

    public function testSignWithoutSignatureOverride()
    {
        $this->expectException(SignatureNotImplementedException::class);
        $this->expectExceptionMessage(sprintf('Missing signature implementation on %s', EmptyBlueprintMock::class));

        EmptyBlueprintMock::sign(EmptyBlueprintMock::generate());
    }

    public function testVariousGeneratesAndSigns()
    {
        $customKey = 'custom-key';
        $claims = ['my' => 'claim'];
        $signature = SignatureBlueprintMock::signature($customKey);

        $token1 = (new Token($claims))->sign(new HmacSha256, $customKey);
        $token2 = SignatureBlueprintMock::generate($claims)->sign(new HmacSha256, $customKey);
        $token3 = SignatureBlueprintMock::generate($claims)->sign($signature->signMethod(), $signature->signatureKey());
        $token4 = SignatureBlueprintMock::sign(SignatureBlueprintMock::generate($claims), $customKey);
        $token5 = SignatureBlueprintMock::generateAndSign($claims, $customKey);

        $this->assertSame($token1->toString(), $token2->toString());
        $this->assertSame($token1->toString(), $token3->toString());
        $this->assertSame($token1->toString(), $token4->toString());
        $this->assertSame($token1->toString(), $token5->toString());
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
            'some_claim' => 'some value',
        ]);

        $this->assertTrue(EmptyBlueprintMock::validate($token, [
            new SomeClaimMock('some value'),
        ]));

        $this->assertFalse(EmptyBlueprintMock::validate($token, [
            new SomeClaimMock('some other value'),
        ]));
    }
}
