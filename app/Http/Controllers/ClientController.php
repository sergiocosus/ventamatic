<?php namespace Ventamatic\Http\Controllers;


use Illuminate\Http\Request;
use Ventamatic\Core\External\Client;

class ClientController extends Controller
{
    public function get()
    {
        $clients =  Client::all();
        return compact('clients');
    }

    public function getClient(Client $client)
    {
        return compact('client');
    }

    public function post(Request $request)
    {
        $client = Client::create($request->all());
        return compact('client');
    }

    public function delete(Request $request, Client $client)
    {
        if($client->delete()){
            return ['success'=>true];
        }else{
            \Response::json(['success'=>false], 500);
        }
    }

    public function put(Request $request, Client $client)
    {
        $client->fill($request->all());
        $client->update();
        return compact('client');
    }

}