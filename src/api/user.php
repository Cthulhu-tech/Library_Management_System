<?php

require __DIR__ . '/../utils/database/db.php';

class User extends Database
{

    private $result;

    function __construct()
    {

        parent::__construct();
    }

    private function checkParams()
    {

        if (isset($_GET['limit'], $_GET['offset'])) {

            $this->limit = $_GET['limit'];
            $this->offset = $_GET['offset'];

            if(!is_int($_GET['limit']) || !is_int($_GET['offset'])) {

                echo $this->messageResponse(400, 'sorry, limit or offset is not a number.');
                
                return false;

            }

            $this->createDatabase();

            $this->result = $this->getDB()->query("SELECT COUNT(*) as count FROM user_library");

            $limitUser = $this->result->fetchAll(PDO::FETCH_ASSOC)[0]['count'];

            if ($limitUser === 0) {

                echo $this->messageResponse(400, 'sorry your database is empty');

                return false;
            }

            if ($this->offset > $limitUser) {

                echo $this->messageResponse(400, 'exceeding the offset. max offset = ' . $limitUser);

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

    public function getUsers()
    {

        $check = $this->checkParams();

        if (!$check) {

            return 0;
        }

        $this->createDatabase();

        $this->result = $this->getDB()->prepare("SELECT * FROM user_library WHERE LIMIT ? , ?");

        $this->result->execute(array( $this->offset,  $this->limit));

        $row = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);

        print_r($row);

        $this->closeConnection();
    }

    public function getUser() {

        

    }
}