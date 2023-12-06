<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/config/config.php";

class Database{
    protected $connection;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
    }
    public function getConnection() {
        return $this->connection;
    }
}