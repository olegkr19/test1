<?php

namespace App;

use App\Classes\SysMethods;

session_start();
header("access-control-allow-origin: *");
header("Access-Control-Allow-Headers: origin, content-type, accept");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Content-Type: application/json; charset=utf8');
$errors = array();

if($transaction){
    //Сохраняем транзакцию
    if(!$transaction->save()){
        //Если не удалось то добавляем ошибку
        SysMethods::addError("operation failed");
        SysMethods::sendError(join(', ', $transaction->errors));
    }
}else{
    SysMethods::addError("transaction not start");
}



}else{
SysMethods::addError("action not found");
//throw new Exception("action not found");
}
} catch (Exception $e) {
SysMethods::sendError($e->getMessage());
SysMethods::addError("could not include module");
}
}else{
SysMethods::addError("action not specified");
}



if(count($errors) > 0){
//http_response_code(500);
$json = [
status => "error",
error => $errors
];
}else{
$json = [
status => "done",
data => $answer
];

if(!$answer){
unset($json["data"]);
}
}

echo json_encode($json);
