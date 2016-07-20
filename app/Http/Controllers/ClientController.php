<?php namespace Ventamatic\Http\Controllers;


use Illuminate\Http\Request;
use Ventamatic\Core\External\Client;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
    public function get()
    {
        $clients =  Client::all();
        return $this->success(compact('clients'));
    }

    public function getClient(Client $client)
    {
        return $this->success(compact('client'));
    }

    public function post(Request $request)
    {
        $client = Client::create($request->all());
        return $this->success(compact('client'));
    }

    public function delete(Request $request, Client $client)
    {
        if($client->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, Client $client)
    {
        $client->fill($request->all());
        $client->update();
        
        return $this->success(compact('client'));
    }

}