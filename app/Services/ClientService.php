<?php

namespace App\Services;

use App\Client;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;

class ClientService
{
  public function __construct(ClientRepository $client){
    $this->client = $client;
  }
  public function index(){
    return $this->client->all();
  }
  public function create(Request $request){
    $attributes = $request->all();
    return $this->client->create($attributes);
  }
public function read($id){
  return $this->client->find($id);
}
public function update(Request $request,$id){
  $attributes = $request->all();
  return $this->client->update($id,$attributes);
}
public function delete($id){
  return $this->client->delete($id);
}
}
