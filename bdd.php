<?php

class Database {
    private $host = 'localhost';
    private $username = 'root'; // Remplacez par votre utilisateur MySQL
    private $password = ''; // Remplacez par votre mot de passe MySQL
    private $dbname = 'recycleart'; // Remplacez par le nom de votre base de données
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Erreur de connexion: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}