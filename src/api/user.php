<?php

require __DIR__ . '/../utils/database/db.php';

class User extends Database
{

    private $result;

    function __construct()
    {
        parent::__construct();
    }

    public function getAllUsers()
    {
        $this->createDatabase();

        $this->result = $this->getDB()->query("SELECT * FROM user_library");

        while ($row = $this->result->fetch(PDO::FETCH_ASSOC)) {

            echo $row;
        }
    }
}