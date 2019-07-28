<?php

namespace StudioAtual\Controllers\Api\Panel\Admin;

use StudioAtual\Controllers\Controller;
use StudioAtual\Models\Contact;

class ContactsController extends Controller
{
    public function index($request, $response, $args)
    {
        $page = $request->getParam('page');
        $per_page = $request->getParam('per_page');
        $columns = ['id', 'name', 'email', 'created_at'];
        $order = ($request->getParam('order') !== null) ? $request->getParam('order') : 'id';
        $direction = ($request->getParam('direction') !== null) ? $request->getParam('direction') : 'desc';
        $search = ($request->getParam('search') !== null && !empty($request->getParam('search'))) ? $request->getParam('search') : null;
        $type = ($request->getParam('type') !== null) ? $request->getParam('type') : null;

        if ($search && $type) {
            $contacts = Contact::where($type, 'like', '%'.$search.'%')
                ->orderBy($order, $direction)
                ->paginate($per_page, $columns, 'page', $page);
        } else {
            $contacts = Contact::orderBy($order, $direction)
                ->paginate($per_page, $columns, 'page', $page);
        }

        return $response->withJson($contacts);
    }

    public function show($request, $response, $params)
    {
        $contact = Contact::find($params['id']);

        if (!$contact) {
            return $response->withJson([ 'erro' => 'Contato não encontrado!' ], 400);
        }

        return $response->withJson($contact);
    }

    public function destroy($request, $response, $args)
    {
        $contact = Contact::find($args['id']);

        if (!$contact) {
            return $response->withJson([ 'erro' => 'Contato não encontrado!' ], 400);
        }

        $contact->delete();
        return $response->withJson([ 'result' => 'ok' ]);
    }
}
