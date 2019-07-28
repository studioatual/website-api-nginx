<?php

namespace StudioAtual\Controllers\Api\Panel;

use StudioAtual\Controllers\Controller;
use Respect\Validation\Validator as v;
use StudioAtual\Models\User;

class AuthController extends Controller
{
    public function autologin($request, $response)
    {
        $user = $this->jwt->getUser();
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'token' => $this->jwt->generateToken($user),
            'access' => $user->access
        ];
        $user->updateAccess();
        return $response->withJson($data);
    }

    public function login($request, $response)
    {
        $params = $request->getParams();

        if (!$this->auth->attemptAuthentication($params)) {
            return $response->withJson([ 'erro' => $this->auth->getError() ], 400);
        }

        if (!$this->auth->checkUserIsAdmin()) {
            return $response->withJson([ 'erro' => 'no-authorization' ], 401);
        }

        $user = $this->auth->getUser();
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'token' => $this->jwt->generateToken($user),
            'access' => $user->access
        ];
        $user->updateAccess();
        return $response->withJson($data);
    }

    public function recoverPassword($request, $response)
    {
        $params = $request->getParams();

        if (!isset($params['email']) || $params['email'] == "") {
            return $response->withJson([ 'erro' => 'Não foi enviado o E-mail!' ], 400);
        }

        $user = User::where('email', $params['email'])->first();

        if (!$user) {
            return $response->withJson([ 'erro' => 'E-mail não cadastrado!' ], 400);
        }

        $hash = $this->random_string(16);
        $user->hash = $hash;
        $user->save();

        $token = rand(1,9) . base64_encode($user->email) . "." . rand(1,9) . base64_encode($hash);

        $this->sendRecoverMail($user, $token);

        return $response->withJson([ 'result' => 'Ok' ]);
    }

    private function sendRecoverMail($user, $token)
    {
        $this->mailer->send('emails/admin/recover_password.twig', ['user' => $user, 'token'  => $token], function ($message) use ($user) {
            $message->from(['contato@studioatual.com' => 'Studio Atual']);
            $message->to([$user->email => $user->name]);
            $message->replyTo(['contato@studioatual.com' => 'Studio Atual']);
            $message->subject('Link para Troca da Senha de Acesso.');
        });
    }

    private function random_string($length)
    {
        $chars ="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $string = '';
        for ($i=0; $i<$length; $i++) {
            $string .= $chars[rand(0,strlen($chars)-1)];
        }
        return $string;
    }
}
