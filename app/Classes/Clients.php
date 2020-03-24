<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;

class Clients{

  public static function getAllClients(){

  }
  public static function getClientById($id){
    DB::raw('LOCK TABLES clients READ WRITE');
    $sql = DB::select('select * from clients where client_id = ? LIMIT 1', [$id]);
    DB::raw('UNLOCK clients');
    return $sql;
  }
  public static function createClient(){
    $sql = DB::insert('insert into clients (client_id, client_name,client_phone,client_card,client_money,client_data) values (?, ?)', [1, 'Dayle','5344554','00000444','32.43',array('products' => '')]);

    return $sql;
  }
  public static function updateClientById($id){
    $sql = DB::update('update clients set client_name = John where client_id = ?', [$id]);

    return $sql;
  }
  public static function updateClientBalance($id){
    DB::raw('LOCK TABLES clients READ WRITE');
    DB::update('update clients set client_money = client_money + 10 where client_id = ?', [$id]);
    DB::commit();
    DB::raw('UNLOCK clients');
  }
  public static function deleteClientById($id){
      $sql = DB::delete('delete from users where client_id = ?', [$id]);

      return $sql;
  }
  /*public static function getClientBalance(Request $request){

    $balance = DB::select('select client_money from clients where client_id = ?', [$id]);
    $balance = json_decode($balance);
    //return $balance;
    //$money = $request->input('client_money');
    //$balance = json_decode($balance) + 10;
    $balance = $money + 10;
    $message = "На вашем балансе: " . $balance;
    return response()->json([
    'status' => 'done',
    'message' => $message
  ]);
}*/

}
//$transaction->prepare($sql);
//return $transaction->exec($sql);
