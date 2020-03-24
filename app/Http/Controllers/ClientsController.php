<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientRequest;
use App\Services\ClientService;


class ClientsController extends Controller
{
    protected $clientservice;

    public function __construct(ClientService $clientservice){
      $this->clientservice = $clientservice;
    }
    public function index(){
      $clients = $this->clientservice->index();
      return $clients;
    }
    public function show($id){
      $client = $this->clientservice->read($id);
      return $client;
    }
    public function store(ClientRequest $request){
      $this->clientservice->create($request);
      return back()->with(['status' => 'Client added successfully']);
    }
    public function update(ClientRequest $request,$id){
      $client = $this->clientservice->update($request,$id);
      return back()->with(['status' => 'Client has been updated successfully']);
    }
    public function delete($id){
      $this->clientservice->delete($id);
      return back()->with(['status' => 'Deleted successfully']);
    }
}
