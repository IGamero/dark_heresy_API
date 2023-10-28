<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define("SERVERNAME", $_SERVER['DB_HOST']);
define("USERNAME", $_SERVER['DB_USER']);
define("PASSWORD", $_SERVER['DB_PASSWORD']);
define("DATABASE", $_SERVER['DB_DATABASE']);

class DatabaseConnection
{
    private $host = SERVERNAME;
    private $username = USERNAME;
    private $password = PASSWORD;
    private $database = DATABASE;
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        } else {
            // echo "Conectado";
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}

?>