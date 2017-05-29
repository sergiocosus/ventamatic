<?php namespace Ventamatic\Http\Controllers;


use Illuminate\Http\Request;
use Ventamatic\Core\External\Client;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function get(Request $request)
    {
        $this->can('client-get');

        $query = Client::query();
        if ($request->get('deleted')) {
            $this->can('client-delete');
            $query->withTrashed();
        }
        $clients = $query->get();

        return $this->success(compact('clients'));
    }

    public function getClient(Client $client)
    {
        $this->can('client-get-detail');

        return $this->success(compact('client'));
    }

    public function getSearch(Request $request)
    {
        $this->can('client-get');

        $clients = Client::search($request->get('search'))
            ->get();

        return $this->success(compact('clients'));
    }

    public function post(Request $request)
    {
        $this->can('client-create');

        $client = Client::create($request->all());
        return $this->success(compact('client'));
    }

    public function delete(Request $request, Client $client)
    {
        $this->can('client-delete');

        if($client->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function patchRestore(Client $client)
    {
        $this->can('client-delete');

        if($client->restore()){
            return $this->success(compact('client'));
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, Client $client)
    {
        $this->can('client-edit');

        $client->fill($request->all());
        $client->update();
        
        return $this->success(compact('client'));
    }

}