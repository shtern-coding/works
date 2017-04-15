<?php

class RequestWatch
{

    public $ip;

    public function __construct($ip){
        $this->ip = $ip;
    }

    /**
     * Подключение к БД
     */
    private function connect_db() {
        // параметры для подключения к БД - логин, пароль и т.д.
        $db_params = [
            "host" => "localhost",
            "dbname" => "vk_likes",
            "user" => "Rybov",
            "password" => "123"
        ];
        // подключение
        $db = new PDO ("mysql:host={$db_params['host']}; dbname={$db_params['dbname']}", $db_params['user'], $db_params['password']);
        $db->exec('set names utf8');
        return $db;
    }

    /**
     * Создает новую запись в бд для текущего ip (заданного через конструктор), если её ещё не существует
     * или меняет статус  на 2 (рабочий), если запись существует, но статус не 2
     */
    public function createStatus() {
        $status = $this->checkStatus($this->ip);
        if ($status == false) {
            $db = $this->connect_db();
            $result = $db->prepare("INSERT INTO stop_request(ip, status) VALUES (:ip, 2)");
            $result->bindParam(':ip', $this->ip, PDO::PARAM_INT);
            $result->execute();
        } elseif ($status != 2) {
            $this->changeStatus(2);
        }
    }

    /**
     * Меняет статус для текущего ip (заданного через конструктор)
     */
    public function changeStatus($status) {
        $db = $this->connect_db();
        $result = $db->prepare("UPDATE stop_request SET status = :status WHERE ip = :ip");
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        $result->bindParam(':ip', $this->ip, PDO::PARAM_INT);
        $result->execute();
    }

    /**
     * Запрашивает статус для текущего ip (заданного через конструктор)
     */
    public function checkStatus() {
        $db = $this->connect_db();
        $result = $db->prepare("SELECT status FROM stop_request WHERE ip = :ip");
        $result->bindParam(':ip', $this->ip, PDO::PARAM_INT);
        $result->execute();
        $status = $result->fetch();
        if ($status) {
            return $status['status'];
        } else {
            return false;
        }
    }

    /**
     * Проверяет статус запроса и если статус не 2 (рабочий), то отключает текущий скрипт
     */
    public function stopRequest() {
        if ($this->checkStatus() != 2) {
            die;
        }
    }

}

