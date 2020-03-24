<?php

namespace App\Classes;

use PDO;
use PDOException;
/*
Created Hacksli
2019.08.05
Этот класс создан для управления записью данных в базу
Конструктор создает подключение
Метод exec получает обьект в которого 2 метода $sql->getsql() $sql->getdata(), это для PDO. SQL и переменные
Метод save записывает транзакцию
в случае ошибки записи одного из запросов транзакция откатывается
*/
class Transaction {
    public $PDO;
    public $errors = array();
    function __construct($host = "bdcontro.mysql.tools", $user = "bdcontro_oleg", $password = "Zo+;25Fr2d", $dbname = "bdcontro_oleg") {
        try {
            //Установка кодировки
            $driver    = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES `utf8`',
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            );
            //создаем новый объект класса PDO для взаимодействия с БД
            $this->PDO = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8;', $user, $password, $driver);
            //$this->PDO = new PDO('mysql:host=' . $host . ';charset=utf8', $user, $password, $driver);
            try {
                //Если транзакция еще не начата
                if (!$this->PDO->inTransaction()) {
                    //Начинаем транзакцию
                    $this->PDO->beginTransaction();
                } else {
                    $this->errors[] = "NEW: Транзакция уже началась, выполнить задачу невозможно";
                }
            }
            catch (PDOException $e) {
                if ($this->PDO->inTransaction()) {
                    $this->PDO->rollBack();
                    $this->errors[] = 'NEW: Откатываем транзакцию PDOException: ' . $e->getCode() . '|' . $e->getMessage();
                } else {
                    $this->errors[] = "NEW: Транзакция не была начата, изменения не могли быть записаны.";
                }
            }
        }
        catch (PDOException $e) {
            $this->errors[] = 'NEW: Подключение не удалось: ' . $e->getCode() . '|' . $e->getMessage();
        }
    }
    public function prepare($sql) {
            return $this->PDO->prepare($sql);
    }
    public function save() {
        if($this->PDO != null){
            //Если транзакция все еще существует
            if ($this->PDO->inTransaction()) {
                //Записываем изменения
                if (count($this->errors) == 0) {
                    $this->PDO->commit();
                    $response = true;
                } else {
                    $response       = false;
                    $this->errors[] = "SAVE: Есть ошибки, невозможно сохранить изменения";
                }
            } else {
                $response       = false;
                $this->errors[] = "SAVE: Транзакция не была запущена, не удалось записать изменения";
            }
        }else{
                $response       = false;
                $this->errors[] = "SAVE: Не удалось получить PDO, не удалось записать изменения";
        }

        return $response;
    }
    public function exec($sql) {
        try {
            if($this->PDO != null){
              //$this->PDO->beginTransaction(); //my change
                //Если транзакция начата
                if ($this->PDO->inTransaction()) {
                    //Выполняем запрос
                    $p = $this->PDO->prepare($sql->getsql());
                    $p->execute($sql->getdata());
                    $response = $this->PDO->lastInsertId();
                    if ($response == 0) {
                        $response = $p->fetchAll();
                    }
                } else {
                    $response       = false;
                    $this->errors[] = "EXEC: Транзакция не началась, не возможно выполнить запрос";
                }
            }else{
                    $response       = false;
                    $this->errors[] = "EXEC: Не удалось получить PDO, не возможно выполнить запрос";
            }

        }
        catch (PDOException $e) {
            if ($this->PDO->inTransaction()) {
                $this->PDO->rollBack();
                $this->errors[] = "EXEC: Откатываем транзакцию PDOException: " . $e->getCode() . '|' . $e->getMessage();
            } else {
                $this->errors[] = "EXEC: Транзакция не была запущена, не удалось записать изменения";
            }
        }
        return $response;
        //return $this->errors;
    }
}
