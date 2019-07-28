<?php

namespace StudioAtual\Controllers\Api\Panel\Admin;

use StudioAtual\Controllers\Controller;
use StudioAtual\Models\Config;
use Respect\Validation\Validator as v;

class ConfigController extends Controller
{
    public function index($request, $response)
    {
        $config = Config::find(1);
        return $this->response->withJson($config);
    }

    public function update($request, $response)
    {
        $params = $this->filterParams($request->getParams());

        $validation = $this->validateUpdate($params);
        if ($validation->failed()) {
            return $this->response->withJson($validation->getErrors(), 400);
        }

        $config = Config::find(1);
        $config->update($params);
        return $this->response->withJson($config);
    }

    private function validateUpdate($params)
    {
        v::with('StudioAtual\\Validation\\Rules');
        return $this->validator->validate($params, [
                'company' => v::notEmpty()->setName('Empresa'),
                'email1' => v::notEmpty()->email()->setName('Loja E-mail 01'),
                'email2' => v::notEmpty()->email()->setName('Loja E-mail 02'),
                'title' => v::notEmpty()->setName('Titulo'),
                'description' => v::notEmpty()->setName('Descrição'),
                'keywords' => v::notEmpty()->setName('Palavras Chave')
            ]);
    }

    private function filterParams($params)
    {
        $result = [];
        foreach ($params as $key => $value) {
            $result[$key] = trim(strip_tags($value));
        }
        return $result;
    }
}
