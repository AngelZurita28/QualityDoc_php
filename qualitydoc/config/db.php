<?php
class Database
{
    private $host;
    private $db;
    private $user;
    private $pass;
    private $port;
    public $conn;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db = getenv('DB_NAME') ?: 'qualitydoc';
        $this->user = getenv('DB_USER') ?: 'postgres';
        $this->pass = getenv('DB_PASS') ?: 'Clave123.';
        $this->port = getenv('DB_PORT') ?: '5432';
    }

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