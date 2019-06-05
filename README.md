# PHP Simple JWT

Simple package for encoding and decoding JSON Web Tokens (JWT) and using them in a PHP application. More information can be found in [RFC 7519](https://tools.ietf.org/html/rfc7519).

Supported encryption methods;

| HMAC     | RSA      | ECDSA   | RSASSA-PSS |
| -------- | -------- | ------- | ---------- |
| ✅ HS256 | ✅ RS256 | ✕ ES256 | ✕ PS256    |
| ✅ HS384 | ✅ RS384 | ✕ ES384 | ✕ PS384    |
| ✅ HS512 | ✅ RS512 | ✕ ES512 | ✕ PS512    |


## Installation

```bash
composer require lgrevelink/php-simple-jwt
```


## Example

Basic unsigned JWT but please, **always sign your tokens**.

```php
use LGrevelink\SimpleJWT\Token;

$token = new Token([
    'custom' => 'payload',
]);

$token->toString();
// > eyJ0eXAiOiJKV1QiLCJhbGciOiJub25lIn0.eyJjdXN0b20iOiJwYXlsb2FkIn0.
```


## Example with blueprints

Blueprints make generating and validating the already created tokens easier. They act as an abstract version of your actual token and will set up all the general claims when defined.

```php
use LGrevelink\SimpleJWT\TokenBlueprint;

class MyToken extends TokenBlueprint
{
    protected static $audience = 'GitHub users';

    protected static $expirationTime = 3600;

    protected static $issuedAt = 0;

    protected static $issuer = 'Developer';

    protected static $subject = 'Blueprint example';
}

$token = MyToken::generate([ /* Custom claims */ ]);

$token->toString();
// > eyJ0eXAiOiJKV1QiLCJhbGciOiJub25lIn0.eyJhdWQiOiJHaXRIdWIgdXNlcnMiLCJleHAiOjE1NTk1NzkwNzgsImlhdCI6MTU1OTU3NTQ3OCwiaXNzIjoiRGV2ZWxvcGVyIiwic3ViIjoiQmx1ZXByaW50IGV4YW1wbGUifQ.

MyToken::validate($token);
// > true
```

All date-related parameters in the blueprints are treated relative to the current time on the system.


## Signing & verifying

### HMAC

```php
use LGrevelink\SimpleJWT\Token;
use LGrevelink\SimpleJWT\Signing\Hmac\HmacSha256;

$token = new Token();
$token->sign(new HmacSha256(), 'signing secret');

$token->toString();
// > eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W10.o5-rpJi4_bEYcIWisa6qD7rFX6fk4Jh0FfNyOTmDbsI

$token->verify(new HmacSha256(), 'signing secret');
// true
```

### RSA

```php
use LGrevelink\SimpleJWT\Signing\Rsa\Keys\PrivateKey;
use LGrevelink\SimpleJWT\Signing\Rsa\Keys\PublicKey;
use LGrevelink\SimpleJWT\Signing\Rsa\RsaSha256;
use LGrevelink\SimpleJWT\Token;

$privateKey = new PrivateKey('private_rsa.pem');

$token = new Token();
$token->sign(new RsaSha256($privateKey), 'possible passphrase');

$token->toString();
// > eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.W10.Tsm8x3WxQUa12n2FedJIHlObnLZBbPF_IczvcTAt25ZIaJlYOuka8S5GzdmJ6ZfD5UiHLTgbJG0pdpSwdnsKg44TyWj5Yl19qx6ddDKcfQxk7zaPTy6kDaCi5Hl6yC0WiXjgnvolD9Hp8fBYoYmcer-V0cFr50Ee9SfBuIejQPddlGvx7EfjZ0yIVNuxBD7Uzimio3VacomolpFAmJHPqLLSfrHKI9ITncyg9U_IpnwHcBUe3BBeHEUzeL8k9nvUKZof5vIAGsZ7o3xi0NbOMfYw5DhP8jCTgjKlqMfxlQVRI8cNPj852qfrf8CzYHvYuR_7uN1s8a_ooBfHjOxeYg

$publicKey = new PublicKey('public_rsa.pem');

$token->verify(new RsaSha256(null, $publicKey));
// true
```


## Parsing tokens

This part should be easy as  🍰. Just throw in your stringified token and it should be ready to use after parsing. If a given token is not parseable, an `InvalidFormatException` is thrown. In case a part of the token cannot be parsed it will default back to the default of `null`.

```php
use LGrevelink\SimpleJWT\Token;

$token = Token::parse('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W10.o5-rpJi4_bEYcIWisa6qD7rFX6fk4Jh0FfNyOTmDbsI');
```


## Tests

Tests are written with PHPUnit and can be run via the following composer command;

```bash
composer run test
```
