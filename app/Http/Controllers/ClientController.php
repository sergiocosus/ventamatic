<?php namespace Ventamatic\Http\Controllers;


use Illuminate\Http\Request;
use Ventamatic\Core\External\Client;

class ClientController extends Controller
{
    public function get()
    {
        return Client::all();
    }

    public function getClient(Client $client)
    {
        return $client;
    }

    public function post(Request $request)
    {
        /* TODO Fill this method*/
    }

    public function delete(Request $request, Client $client)
    {
        /* TODO Fill this method*/
    }

    public function put(Request $request, Client $client)
    {
        /* TODO Fill this method*/
    }

}