<?php

namespace App\Repositories;

use App\Client;

class ClientRepository
{
  protected $client;

  public function __construct(Client $client){
    $this->client = $client;
  }
  public function create($attributes){
    return $this->client->create($attributes);
  }
  public function all(){
    return $this->client->all();
  }
  public function find($id){
    return $this->client->find($id);
  }
  public function update($id,$attributes){
    return $this->client->find($id)->update($attributes);
  }
  public function delete($id){
    return $this->client->find($id)->delete();
  }
}
