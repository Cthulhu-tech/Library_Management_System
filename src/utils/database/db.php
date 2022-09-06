<?php

require_once __DIR__ . '/../../interface/interface.php';

class Database implements IDatabase
{

    protected $db;
    protected $options = [
        'cost' => 6,
    ];

    protected $pdoOptions = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
    );

    protected $host;
    protected $dbname;
    protected $userdb;
    protected $password;
    protected $port;

    function __construct()
    {

        $this->port = $_ENV['PORT'];
        $this->host = $_ENV['SERVER'];
        $this->dbname = $_ENV['DBNAME'];
        $this->userdb = $_ENV['USERDB'];
        $this->password = $_ENV['PASSWORD'];
    }

    public function createDatabase(): void
    {
        try {

            $this->db = new PDO('mysql:host=' . $this->host . ';' . 'port=' . $this->port . ';' . 'dbname=' . $this->dbname, $this->userdb, $this->password, $this->pdoOptions);
        } catch (PDOException $e) {

            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getDB(): PDO
    {

        return $this->db;
    }

    public function closeConnection(): void
    {

        $this->db = null;
    }

    public function hashPassword(string $password): string
    {

        return password_hash($password, PASSWORD_BCRYPT, $this->options);
    }

    public function messageResponse(int $code = 200, string $message = "Ok"): void
    {

        http_response_code($code);

        $messageObj = new stdClass();

        $messageObj->message = $message;

        echo json_encode($messageObj);
    }

    protected function accestoken($token, $user)
    {

        http_response_code(201);

        $messageObj = new stdClass();

        $messageObj->user = $user;
        $messageObj->access = $token;

        echo json_encode($messageObj);
    }
}