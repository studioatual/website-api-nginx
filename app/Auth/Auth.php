<?php

namespace StudioAtual\Auth;

class Auth
{
    private $model;
    private $bucket;
    private $error;

    public function __construct($model, $bucket)
    {
        $this->model = $model;
        $this->bucket = $bucket;
    }

    public function attemptAuthentication($params)
    {
        if (!isset($params['identifier']) && !isset($params['email'])) {
            $this->error = 'Login não enviado!';
            return false;
        }

        if (isset($params['identifier'])) {
            $user = $this->attemptWithIndentifier($params['identifier']);
        }

        if (isset($params['email'])) {
            $user = $this->attemptWithEmail($params['email']);
        }

        if (!$user) {
            return false;
        }

        if (!isset($params['password'])) {
            $this->error = 'Senha não enviada!';
            return false;
        }

        if (!$this->checkPasswordIsMatch($user, $params['password'])) {
            return false;
        }

        return $this->register($user);
    }

    public function attemptWithIndentifier($identifier)
    {
        $user = $this->model::where('email', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        if (!$user) {
            $this->error = 'E-mail ou Usuário inválido!';
            return false;
        }

        return $user;
    }

    public function attemptWithEmail($email)
    {
        $user = $this->model::where('email', $email)
            ->first();

        if (!$user) {
            $this->error = 'E-mail inválido!';
            return false;
        }

        return $user;
    }

    public function checkPasswordIsMatch($user, $password)
    {
        if (!password_verify($password, $user->password)) {
            $this->error = 'Senha inválida!';
            return false;
        }

        return $user;
    }

    public function checkUserIsAuthenticated()
    {
        if (!isset($_SESSION[$this->bucket])) {
            return false;
        }

        return true;
    }

    public function checkUserIsValid()
    {
        if (!isset($_SESSION[$this->bucket])) {
            return false;
        }

        if (!$this->model::find($_SESSION[$this->bucket])) {
            $this->clear();
            return false;
        }

        return true;
    }

    public function checkUserIsAdmin()
    {
        if (!isset($_SESSION[$this->bucket])) {
            return false;
        }

        $user = $this->model::find($_SESSION[$this->bucket]);

        if (!$user) {
            $this->clear();
            return false;
        }

        return (bool) $user->admin;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getUser()
    {
        if (!isset($_SESSION[$this->bucket])) {
            return false;
        }

        return $this->model::find($_SESSION[$this->bucket]);
    }

    public function register($user)
    {
        $_SESSION[$this->bucket] = $user->id;
        return $user;
    }

    public function clear()
    {
        if (isset($_SESSION[$this->bucket])) {
            unset($_SESSION[$this->bucket]);
        }
    }
}
