<?php

class Sql
{
    public $hostname = "127.0.0.1";
    public $username = "root";
    public $password = "";
    public $dbname = "ifbawebii";

    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=ifbawebii", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }

    public function getConexao() {
        return $this->pdo;
    }
}
