<?php
class Database
{
    private $host = 'localhost';
    private $db = 'qualitydoc';
    private $user = 'postgres';
    private $pass = 'Clave123.'; // <-- Pon tu contraseña
    private $port = '5432';
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db;
            $this->conn = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $exception) {
            throw $exception;
        }
        return $this->conn;
    }
}
?>