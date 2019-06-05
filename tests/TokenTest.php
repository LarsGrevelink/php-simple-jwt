<?php

namespace Tests;

use LGrevelink\SimpleJWT\Exceptions\DataGuardedException;
use LGrevelink\SimpleJWT\Signing\Hmac\HmacSha256;
use LGrevelink\SimpleJWT\Token;

final class TokenTest extends TestCase
{
    public function testConstructor()
    {
        $token = new Token();

        $header = TestUtil::getProperty($token, 'header');
        $payload = TestUtil::getProperty($token, 'payload');
        $signature = TestUtil::getProperty($token, 'signature');

        $this->assertSame(Token::JWT_HEADER_TYPE_JWT, $header->get('typ'));
        $this->assertSame(Token::JWT_HEADER_ALGORITHM_NONE, $header->get('alg'));

        $this->assertSame([], $payload->all());

        $this->assertNull($signature);
    }

    public function testConstructorArguments()
    {
        $headerData = ['header' => 'bag'];
        $payloadData = ['payload' => 'bag'];
        $signatureData = 'zqnDp-KImi1zaWduYXR1cmU';

        $token = new Token($payloadData, $headerData, $signatureData);

        $header = TestUtil::getProperty($token, 'header');
        $payload = TestUtil::getProperty($token, 'payload');
        $signature = TestUtil::getProperty($token, 'signature');

        $this->assertSame(Token::JWT_HEADER_TYPE_JWT, $header->get('typ'));
        $this->assertSame(Token::JWT_HEADER_ALGORITHM_NONE, $header->get('alg'));
        $this->assertSame('bag', $header->get('header'));

        $this->assertSame($payloadData, $payload->all());

        $this->assertSame($signatureData, $signature);
    }

    public function testGetPayload()
    {
        $token = new Token([
            'test' => 'payload',
        ]);

        $this->assertSame('payload', $token->getPayload('test'));
        $this->assertSame(null, $token->getPayload('unknown'));
        $this->assertSame('fallback', $token->getPayload('unknown', 'fallback'));
    }

    public function testSetPayload()
    {
        $token = new Token();

        $this->assertNull($token->getPayload('test'));

        $token->setPayload('test', 'payload');

        $this->assertSame('payload', $token->getPayload('test'));

        $token->setPayload('test', 'override');

        $this->assertSame('override', $token->getPayload('test'));
    }

    public function testSetPayloadAlreadySignedException()
    {
        $this->expectException(DataGuardedException::class);
        $this->expectExceptionMessage('Token needs to be unsigned before the payload can be changed');

        $token = new Token(null, null, 'signature');
        $token->setPayload('test', 'payload');
    }

    public function testGettersSetters()
    {
        $time = time();

        $token = new Token();

        $this->assertSame(null, $token->getAudience());
        $this->assertSame(null, $token->getExpirationTime());
        $this->assertSame(null, $token->getIssuedAt());
        $this->assertSame(null, $token->getIssuer());
        $this->assertSame(null, $token->getJwtId());
        $this->assertSame(null, $token->getNotBefore());
        $this->assertSame(null, $token->getSubject());

        $token->setAudience('audience')
            ->setExpirationTime(0)
            ->setIssuedAt(0)
            ->setIssuer('issuer')
            ->setJwtId('jwt-id')
            ->setNotBefore(0)
            ->setSubject('subject');

        $this->assertSame('audience', $token->getAudience());
        $this->assertSame($time, $token->getExpirationTime());
        $this->assertSame($time, $token->getIssuedAt());
        $this->assertSame('issuer', $token->getIssuer());
        $this->assertSame('jwt-id', $token->getJwtId());
        $this->assertSame($time, $token->getNotBefore());
        $this->assertSame('subject', $token->getSubject());

        $token->setExpirationTime(0, false)
            ->setIssuedAt(0, false)
            ->setNotBefore(0, false);

        $this->assertSame(0, $token->getExpirationTime());
        $this->assertSame(0, $token->getIssuedAt());
        $this->assertSame(0, $token->getNotBefore());
    }

    public function testSignVerifyUnsign()
    {
        $algorithm = new HmacSha256();

        $token = new Token();
        $token->sign($algorithm, 'very secret key');

        $this->assertSame($algorithm->getAlgorithmId(), TestUtil::getProperty($token, 'header')->get('alg'), 'Token should have the correct algorithm ID');
        $this->assertNotNull(TestUtil::getProperty($token, 'signature'), 'Token should contain a signature');

        $verified = $token->verify($algorithm, 'very incorrect key');

        $this->assertFalse($verified);

        $verified = $token->verify($algorithm, 'very secret key');

        $this->assertTrue($verified);

        $token->unsign();

        $this->assertNull(TestUtil::getProperty($token, 'signature'));
    }

    public function testConvertRelativeTime()
    {
        $time = time();
        $token = new Token();

        $convertedTime = TestUtil::invokeMethod($token, 'convertRelativeTime', [
            0,
            true,
        ]);

        $this->assertSame($convertedTime, $time);

        $convertedTime = TestUtil::invokeMethod($token, 'convertRelativeTime', [
            0,
            false,
        ]);

        $this->assertSame($convertedTime, 0);
    }

    public function testToString()
    {
        $payloadData = ['payload' => 'bag'];

        // Unsigned token composing
        $token = new Token($payloadData);
        $this->assertSame('eyJ0eXAiOiJKV1QiLCJhbGciOiJub25lIn0.eyJwYXlsb2FkIjoiYmFnIn0.', $token->toString());

        // Signed token composing
        $token = new Token($payloadData, null, 'signature');
        $this->assertSame('eyJ0eXAiOiJKV1QiLCJhbGciOiJub25lIn0.eyJwYXlsb2FkIjoiYmFnIn0.c2lnbmF0dXJl', $token->toString());
    }

    public function testToStringMagic()
    {
        $payloadData = ['payload' => 'bag'];

        // Unsigned token composing
        $token = new Token($payloadData);
        $this->assertSame('eyJ0eXAiOiJKV1QiLCJhbGciOiJub25lIn0.eyJwYXlsb2FkIjoiYmFnIn0.', (string) $token);

        // Signed token composing
        $token = new Token($payloadData, null, 'signature');
        $this->assertSame('eyJ0eXAiOiJKV1QiLCJhbGciOiJub25lIn0.eyJwYXlsb2FkIjoiYmFnIn0.c2lnbmF0dXJl', (string) $token);
    }
}
