<?php

namespace Tests\StudioAtual\Auth;

use StudioAtual\Auth\Auth;
use StudioAtual\Models\User;
use StudioAtual\Tests\DatabaseTestCase;

class AuthTest extends DatabaseTestCase
{
    public function testAttemptAuthentication()
    {
        $auth = new Auth(User::class, 'user');
        $this->assertTrue((bool) $auth->attemptAuthentication([ 'email' => 'teste@studioatual.com', 'password' => '123456' ]));

        $this->assertTrue((bool) $auth->attemptAuthentication([ 'identifier' => 'teste@studioatual.com', 'password' => '123456' ]));
        $this->assertTrue((bool) $auth->attemptAuthentication([ 'identifier' => 'teste', 'password' => '123456' ]));

        $this->assertFalse((bool) $auth->attemptAuthentication([ 'password' => '123456' ]));
        $this->assertEquals('Login não enviado!', $auth->getError());

        $this->assertFalse((bool) $auth->attemptAuthentication([ 'identifier' => 'teste2' ]));
        $this->assertEquals('E-mail ou Usuário inválido!', $auth->getError());

        $this->assertFalse((bool) $auth->attemptAuthentication([ 'email' => 'teste2@studioatual.com' ]));
        $this->assertEquals('E-mail inválido!', $auth->getError());

        $this->assertFalse((bool) $auth->attemptAuthentication([ 'identifier' => 'teste@studioatual.com' ]));
        $this->assertEquals('Senha não enviada!', $auth->getError());

        $this->assertFalse((bool) $auth->attemptAuthentication([ 'identifier' => 'teste@studioatual.com', 'password' => 'teste2' ]));
        $this->assertEquals('Senha inválida!', $auth->getError());
        $auth->clear();
    }

    public function testRegisterUserAndValidate()
    {
        $auth = new Auth(User::class, 'user');
        $user = User::find(1);
        $this->assertFalse($auth->checkUserIsAuthenticated());
        $this->assertEquals($user, $auth->register($user));
        $this->assertTrue($auth->checkUserIsAuthenticated());
        $this->assertTrue($auth->checkUserIsValid());
        $auth->clear();
    }

    public function testIfUserIsAdmin()
    {
        $auth = new Auth(User::class, 'user');
        $user = User::find(1);
        $auth->register($user);
        $this->assertTrue($auth->checkUserIsAuthenticated());
        $this->assertTrue($auth->checkUserIsValid());
        $this->assertTrue($auth->checkUserIsAdmin());
        $auth->clear();
    }

    public function testIfUserIsNotAdminAndGetUser()
    {
        $auth = new Auth(User::class, 'user');
        $newUser = User::create([
                'name' => 'Novo Usuario',
                'email' => 'novo@studioatual.com',
                'username' => 'novo',
                'password' => password_hash('novo', PASSWORD_DEFAULT),
                'hash' => '',
                'admin' => 0,
            ]);
        $this->assertFalse($auth->checkUserIsAdmin());
        $auth->register($newUser);
        $this->assertTrue($auth->checkUserIsAuthenticated());
        $this->assertTrue($auth->checkUserIsValid());
        $this->assertFalse($auth->checkUserIsAdmin());
        $this->assertEquals($newUser->id, $auth->getUser()->id);
        $auth->clear();

        $_SESSION['user'] = 3;
        $this->assertFalse($auth->checkUserIsAdmin());
    }

    public function testIfUserIsNotValid()
    {
        $auth = new Auth(User::class, 'user');
        $this->assertFalse($auth->checkUserIsValid());
        $_SESSION['user'] = 3;
        $this->assertFalse($auth->checkUserIsValid());
    }

    public function testGetUser()
    {
        $auth = new Auth(User::class, 'user');
        $this->assertFalse($auth->getUser());
        $user = User::find(1);
        $this->assertEquals($user, $auth->register($user));
        $this->assertEquals($user, $auth->getUser());
    }
}
