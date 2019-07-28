<?php

namespace StudioAtual\Controllers\Web\Admin;

use StudioAtual\Controllers\Controller;
use StudioAtual\Models\User;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{
    public function getAuth($request, $response, $args)
    {
        $hash = $args['hash'];
        $p = explode(".", $hash);

        if (count($p) !== 2) {
            return '<h1>URL Inválida, tente novamente!</h1>';
        }

        $email = base64_decode(substr($p[0], 1));
        $hash = base64_decode(substr($p[1], 1));

        $user = User::where([
            ['email', $email],
            ['hash', $hash]
        ])->first();

        if (!$user) {
            return '<h1>URL Inválida, tente novamente!</h1>';
        }

        $this->auth->register($user);

        $user->hash = "";
        $user->save();

        return $response->withRedirect(
            $this->router->pathFor('admin.change.password')
        );
    }

    public function getChangePassword($request, $response)
    {
        return $this->view->render($response, 'admin/change_password.twig', [
                'title' => 'StudioAtual - Alteração da Senha',
                'description' => 'Alteração da Senha de Acesso do Software Administrativo',
                'keywords' => 'studioatual, alteração da senha, administrativo, senha de acesso'
            ]);
    }

    public function postChangePassword($request, $response)
    {
        $params = $request->getParams();

        v::with('StudioAtual\\Validation\\Rules');

        $validation = $this->validator->validate($params, [
            'password' => v::notEmpty()->noWhitespace()->setName('Nova Senha'),
            'password_confirm' => v::notEmpty()->noWhitespace()->equals($params['password'])->setName('Confirmar Nova Senha')
        ]);

        if ($validation->failed()) {
            $this->flash->addMessage('error', 'Verifique os erros!');
            return $response->withRedirect(
                $this->router->pathFor('admin.change.password')
            );
        }

        $user = $this->auth->getUser();
        $user->password = password_hash($params['password'], PASSWORD_DEFAULT);
        $user->save();

        $this->flash->addMessage('success', 'Senha alterada com sucesso!');

        return $response->withRedirect(
            $this->router->pathFor('admin.change.password')
        );
    }
}
