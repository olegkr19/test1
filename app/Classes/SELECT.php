<?php

namespace App\Classes;

class SELECT {
    public $column = "*";
    public $database = "";
    public $tbl_prefix = "";
    public $where = "";
    public $limit = "";
    public $sort = "";
    public $data = null;
    //Конструктор собирает будущий запрос опираясь на входящие данные. База и префикс таблици
    function __construct($dbtables, $requestData = null) {
        //Костыль который позволяет задать базу данных и префикс по-умолчанию зарание глобально
        global $databaseData;
        if ($databaseData != null && $requestData == null) {
            $requestData = $databaseData;
        }
        //Если префикс и (или) база заданы
        if ($requestData != null) {
            if ($requestData["database"] != null) {
                $this->database = $requestData["database"] . ".";
            }
            if ($requestData["tbl_prefix"] != null) {
                $this->tbl_prefix = $requestData["tbl_prefix"] . "_";
            }
        }
        //Если запрос обычный
        if (gettype($dbtables) == "string") {
            $this->db = $this->database . "`" . $this->tbl_prefix . $dbtables . "` ";// . $dbtables;   // my change
        }
        //Если запрос к 2-м базам
        if (gettype($dbtables) == "array") {
            $str = "";
            foreach ($dbtables as &$table) {
                $str .= " " . $this->database . "`" . $this->tbl_prefix . $table . "` " . $table . ",";
            }
            $str      = rtrim($str, ',');
            $this->db = $str;
        }
    }
    //База данных к которой надо обратится если не указать то будет обращаться к той что выбрана
    public function database($db) {
        $this->database = $db;
    }
    //Лимит строк в ответе
    public function limit($limit) {
        $this->limit = $limit;
    }
    public function sort($item, $direction) {
        $this->sort = $item . " " . $direction;
    }
    //Колонки для выборки
    public function collums() {
        //Функция добавляет список колонок которые надо считать
        $str     = "";
        $collums = func_get_args();
        foreach ($collums as &$collum) {
            $str .= " " . $collum . ",";
        }
        //Удаляем последнюю запятую
        $str          = rtrim($str, ',');
        $this->column = $str;
    }
    //Условия
    public function where() {
        $arg_list = func_get_args();
        $str      = "";
        if ($this->where != "") {
            $str .= "and ";
        }
        if ($arg_list[2] == "") {
            $arg_list[2] = '"' . '"';
        }
        $this->data[] = $arg_list[2];
        $str .= $arg_list[0] . " " . $arg_list[1] . " ?";
        $str .= $where . " ";
        $this->where .= $str;
    }
    //Сформировать и выполнить запрос
    public function getsql() {
        $statement = 'SELECT
                    ' . $this->column . '
                FROM
                    ' . $this->db;
        if ($this->where != "") {
            $statement .= ' WHERE
                    ' . $this->where;
        }
        if ($this->sort != "") {
            $statement .= ' ORDER BY
                    ' . $this->sort;
        }
        if ($this->limit != "") {
            $statement .= ' LIMIT
                    ' . $this->limit;
        }
        $statement .= ";";
        $this->result = $statement;
        return $this->result;
    }
    public function getdata() {
        return $this->data;
    }
}
