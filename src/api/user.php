<?php

require __DIR__ . '/../utils/database/db.php';

class User extends Database
{

    private $result;

    function __construct()
    {
        header('Content-Type: application/json');

        parent::__construct();
    }

    private function checkParams()
    {

        if (isset($_GET['limit'], $_GET['offset'])) {

            $this->limit = $_GET['limit'];
            $this->offset = $_GET['offset'];

            $this->createDatabase();

            $this->result = $this->getDB()->query("SELECT COUNT(*) as count FROM user_library");

            $limitUser = $this->result->fetchAll(PDO::FETCH_ASSOC)[0]['count'];

            if ($limitUser === 0) {

                echo $this->messageResponse(400, 'sorry your database is empty');

                return false;
            }

            if ($this->limit === null || $this->offset === null) {

                echo $this->messageResponse(400, 'exceeding the limit. max limit = ' . $limitUser);

                return false;
            }

            if ($this->limit > 50) {

                $this->limit = 50;
            }

            if ($this->limit < 0) {

                $this->limit = 10;
            }

            return true;
        }

        echo $this->messageResponse(400, 'all fields are required');

        return false;
    }

    public function getAllUsers()
    {

        $check = $this->checkParams();

        if (!$check) {

            return 0;
        }

        $this->createDatabase();

        $this->result = $this->getDB()->query("SELECT * FROM user_library");

        $row = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);

        print_r($row);

        $this->closeConnection();
    }
}