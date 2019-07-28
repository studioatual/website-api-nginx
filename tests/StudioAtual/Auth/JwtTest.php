<?php

namespace Tests\StudioAtual\Auth;

use StudioAtual\Auth\Jwt;
use StudioAtual\Models\User;
use StudioAtual\Tests\DatabaseTestCase;

class JwtTest extends DatabaseTestCase
{
    /**
     * @dataProvider getSetProvider
     */
    public function testMakeHeader($domain, $key, $fields, $model, $bucket)
    {
        $jwt = new Jwt( $domain, $key, $fields, $model, $bucket );

        $header = [ 'alg' => 'HS256', 'typ' => 'JWT' ];
        $header_encode = base64_encode(json_encode($header));

        $this->assertEquals($header_encode, $this->invokeMethod($jwt, 'makeHeader'));
    }

    /**
     * @dataProvider getSetProvider
     */
    public function testMakePayload($domain, $key, $fields, $model, $bucket)
    {
        $jwt = new Jwt( $domain, $key, $fields, $model, $bucket );

        $user = User::find(1);

        $payload = [
            'iss' => $domain,
            'username' => $user->username,
            'email' => $user->email
        ];
        $payload_encode = base64_encode(json_encode($payload));

        $this->assertEquals($payload_encode, $this->invokeMethod($jwt, 'makePayload', [$user]));
    }

    /**
     * @dataProvider getSetProvider
     */
    public function testMakeSignature($domain, $key, $fields, $model, $bucket)
    {
        $jwt = new Jwt( $domain, $key, $fields, $model, $bucket );

        $user = User::find(1);

        $header = [ 'alg' => 'HS256', 'typ' => 'JWT' ];
        $header_encode = base64_encode(json_encode($header));

        $payload = [ 'iss' => $domain, 'username' => $user->username, 'email' => $user->email ];
        $payload_encode = base64_encode(json_encode($payload));

        $signature = hash_hmac( 'sha256', $header_encode.$payload_encode, $key, true );
        $signature_encode = base64_encode($signature);

        $this->assertEquals($signature_encode, $this->invokeMethod($jwt, 'makeSignature', [$header_encode, $payload_encode]));
    }

    /**
     * @dataProvider getSetProvider
     */
    public function testTokenIsEmpty($domain, $key, $fields, $model, $bucket)
    {
        $jwt = new Jwt( $domain, $key, $fields, $model, $bucket );

        $this->assertFalse($this->invokeMethod($jwt, 'checkTokenIsNotEmpty', [null]));
        $this->assertFalse($this->invokeMethod($jwt, 'checkTokenIsNotEmpty', ['']));
        $this->assertTrue($this->invokeMethod($jwt, 'checkTokenIsNotEmpty', ['teste']));
    }

    /**
     * @dataProvider getSetProvider
     */
    public function testTokenParts($domain, $key, $fields, $model, $bucket)
    {
        $jwt = new Jwt( $domain, $key, $fields, $model, $bucket );

        $this->assertFalse($this->invokeMethod($jwt, 'checkTokenParts', ['teste']));
        $this->assertFalse($this->invokeMethod($jwt, 'checkTokenParts', ['teste.teste']));
        $this->assertEquals(['teste','teste','teste'], $this->invokeMethod($jwt, 'checkTokenParts', ['teste.teste.teste']));
    }

    /**
     * @dataProvider getSetProvider
     */
    public function testCheckHeader($domain, $key, $fields, $model, $bucket)
    {
        $jwt = new Jwt( $domain, $key, $fields, $model, $bucket );

        $header = [ 'alg' => 'HS256', 'typ' => 'JWT' ];
        $header_encode = base64_encode(json_encode($header));

        $parts = [$header_encode, 'teste', 'teste'];

        $this->assertFalse($this->invokeMethod($jwt, 'checkHeader', [ [''] ]));
        $this->assertTrue($this->invokeMethod($jwt, 'checkHeader', [$parts]));
    }

    /**
     * @dataProvider getSetProvider
     */
    public function testCheckPayload($domain, $key, $fields, $model, $bucket)
    {
        $jwt = new Jwt( $domain, $key, $fields, $model, $bucket );

        $user = User::find(1);

        $payload = [
            'iss' => $domain,
            'username' => $user->username,
            'email' => $user->email
        ];
        $payload_encode = base64_encode(json_encode($payload));

        $parts = ['teste', $payload_encode, 'teste'];

        $this->assertEquals((object) $payload, $this->invokeMethod($jwt, 'checkPayload', [$parts]));
    }

    /**
     * @dataProvider getSetProvider
     */
    public function testCheckPayloadFailed($domain, $key, $fields, $model, $bucket)
    {
        $jwt = new Jwt( $domain, $key, $fields, $model, $bucket );

        $user = User::find(1);

        $payload = [
            'username' => $user->username,
            'email' => $user->email
        ];
        $payload_encode = base64_encode(json_encode($payload));

        $parts = ['teste', $payload_encode, 'teste'];

        $this->assertFalse($this->invokeMethod($jwt, 'checkPayload', [$parts]));

        $payload = [
            'iss' => $domain,
            'username' => $user->username,
        ];
        $payload_encode = base64_encode(json_encode($payload));

        $parts = ['teste', $payload_encode, 'teste'];

        $this->assertFalse($this->invokeMethod($jwt, 'checkPayload', [$parts]));
    }

    /**
     * @dataProvider getSetProvider
     */
    public function testCheckUserExists($domain, $key, $fields, $model, $bucket)
    {
        $jwt = new Jwt( $domain, $key, $fields, $model, $bucket );

        $user = User::find(1);

        $payload = [
            'iss' => $domain,
            'username' => $user->username,
            'email' => $user->email
        ];

        $this->assertEquals($user, $this->invokeMethod($jwt, 'checkUserExists', [ (object) $payload ]));
    }

    /**
     * @dataProvider getSetProvider
     */
    public function testCheckUserExistsFailed($domain, $key, $fields, $model, $bucket)
    {
        $jwt = new Jwt( $domain, $key, $fields, $model, $bucket );

        $payload = [
            'iss' => $domain,
            'username' => 'zequalquer',
            'email' => 'teste@studioatual.com'
        ];

        $this->assertEquals(null, $this->invokeMethod($jwt, 'checkUserExists', [ (object) $payload ]));
    }

    /**
     * @dataProvider getSetProvider
     */
    public function testCheckTokenIsValid($domain, $key, $fields, $model, $bucket)
    {
        $jwt = new Jwt( $domain, $key, $fields, $model, $bucket );

        $user = User::find(1);

        $header = base64_encode(json_encode([ 'alg' => 'HS256', 'typ' => 'JWT' ]));
        $payload = base64_encode(json_encode([ 'iss' => $domain, 'username' => $user->username, 'email' => $user->email ]));
        $signature = base64_encode(hash_hmac( 'sha256', $header.$payload, $key, true ));

        $token = "$header.$payload.$signature";

        $this->assertTrue($this->invokeMethod($jwt, 'checkTokenIsValid', [ $token, $user ]));
    }

    /**
     * @dataProvider getSetProvider
     */
    public function testCheckTokenAndGetUser($domain, $key, $fields, $model, $bucket)
    {
        $jwt = new Jwt( $domain, $key, $fields, $model, $bucket );

        $user = User::find(1);

        $header = base64_encode(json_encode([ 'alg' => 'HS256', 'typ' => 'JWT' ]));
        $payload = base64_encode(json_encode([ 'iss' => $domain, 'username' => $user->username, 'email' => $user->email ]));
        $signature = base64_encode(hash_hmac( 'sha256', $header.$payload, $key, true ));

        $token = "$header.$payload.$signature";

        $this->assertTrue($jwt->checkToken($token));
        $this->assertEquals($user, $jwt->getUser());
    }

    /**
     * @dataProvider getSetProvider
     */
    public function testCheckTokenFailed($domain, $key, $fields, $model, $bucket)
    {
        $jwt = new Jwt( $domain, $key, $fields, $model, $bucket );

        $this->assertFalse($jwt->checkToken(null));
        $this->assertFalse($jwt->checkToken('testedepartes'));

        $header = base64_encode(json_encode([ 'alg' => 'HS256', 'typ' => 'JWT' ]));
        $payload = base64_encode(json_encode([ 'iss' => $domain, 'username' => 'meuusuario', 'email' => 'email@email.com' ]));
        $signature = base64_encode(hash_hmac( 'sha256', $header.$payload, $key, true ));

        $this->assertFalse($jwt->checkToken("header.$payload.$signature"));
        $this->assertFalse($jwt->checkToken("$header.payload.$signature"));
        $this->assertFalse($jwt->checkToken("$header.$payload.$signature"));

        $user = User::find(1);

        $header2 = base64_encode(json_encode([ 'alg' => 'HS256', 'typ' => 'JWT' ]));
        $payload2 = base64_encode(json_encode([ 'iss' => $domain, 'username' => $user->username, 'email' => $user->email ]));
        $signature2 = base64_encode(hash_hmac( 'sha256', 'header2.payload2', $key, true ));

        $token = "$header2.$payload2.$signature2";

        $this->assertFalse($jwt->checkToken($token));
    }

    public function getSetProvider()
    {
        return [
            [
                'domain' => 'http://localhost',
                'key' => 'C43575gHvQCHwlfs1sdS23kox811a2L988WB4pl',
                'fields' => ['username', 'email'],
                'model' => User::class,
                'bucket' => 'user'
            ]
        ];
    }
}
