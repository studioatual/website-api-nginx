<?php

namespace StudioAtual\Controllers\Api\Panel\Admin;

use StudioAtual\Controllers\Controller;
use StudioAtual\Models\User;
use Respect\Validation\Validator as v;

class UsersController extends Controller
{
    public function index($request, $response)
    {
      $users = User::select(['id', 'name', 'email', 'username'])->get();
      return $this->response->withJson($users);
    }

    public function store($request, $response)
    {
        $params = $request->getParams();

        $validation = $this->validateParams($params);
        if ($validation->failed()) {
            return $response->withJson($validation->getErrors(), 400);
        }

        $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);

        $user = User::create($params);

        if (!$user) {
            return $response->withJson([
                'erro' => 'Não foi possível efetuar o cadastro!'
            ], 400);
        }

        return $response->withJson($user);
    }

    private function validateParams($params, $id = null)
    {
        $password = (isset($params['password'])) ? $params['password'] : "";
        v::with('StudioAtual\\Validation\\Rules');

        $rules = [
            'name' => v::notEmpty()->setName('Nome'),
            'email' => v::notEmpty()->email()->fieldAvailable(new User, 'email', $id)->setName('E-mail'),
            'username' => v::notEmpty()->noWhitespace()->fieldAvailable(new User, 'username', $id)->setName('Usuário'),
        ];

        if (!$id) {
            array_merge($rules, [
                'password' => v::notEmpty()->noWhitespace()->setName('Senha'),
                'password_confirm' => v::notEmpty()->equals($password)->setName('Confirmar Senha')
            ]);
        } else {
            array_merge($rules, ['password_confirm' => v::equals($password)->setName('Confirmar Senha')]);
        }

        return $this->validator->validate($params, $rules);
    }

    public function show($request, $response, $args)
    {
        $user = User::find($args['id']);

        if (!$user) {
            return $response->withJson([
                'erro' => 'Usuário não encontrado!'
            ], 400);
        }

        return $response->withJson($user);
    }

    public function update($request, $response, $args)
    {
        $user = User::find($args['id']);

        if (!$user) {
            return $response->withJson([
                'erro' => 'Usuário não encontrado!'
            ], 400);
        }

        $params = $request->getParams();

        $validation = $this->validateParams($params, $user->id);
        if ($validation->failed()) {
            return $response->withJson($validation->getErrors(), 400);
        }

        if (isset($params['password'])) {
            if ($params['password'] == "") {
                unset($params['password']);
            } else {
                $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
            }
        }

        $user->update($params);

        return $this->response->withJson($user);
    }

    public function destroy($request, $response, $args)
    {
        $user = User::find($args['id']);

        if (!$user) {
            return $response->withJson([
                'erro' => 'Usuário não encontrado!'
            ], 400);
        }

        $user->delete();

        return $response->withJson(['result' => 'ok']);
    }
}
