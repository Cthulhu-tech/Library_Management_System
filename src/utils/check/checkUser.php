<?php

require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../../interface/interface.php';

class UserCheck extends Database implements IUserCheck
{

    private $maxuser = 0;
    private $id = '';
    private $limit = '';
    private $offset = '';
    private $name = '';
    private $surname = '';
    private $passport_first = '';
    private $passport_last = '';

    function __construct()
    {

        parent::__construct();
    }

    public function checkParams()
    {

        if (isset($_GET['limit'], $_GET['offset'])) {

            $this->limit = $_GET['limit'];
            $this->offset = $_GET['offset'];

            if (!is_int(+$_GET['limit']) || !is_int(+$_GET['offset'])) {

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

            $this->maxuser = $limitUser;

            if ($this->limit > 50) {

                $this->limit = 50;
            }

            if ($this->limit < 0) {

                $this->limit = 10;
            }

            if ($this->offset < 0 || $this->offset >= $this->maxuser) {

                $this->offset = 0;
            }

            $this->closeConnection();

            return true;
        }

        echo $this->messageResponse(400, 'all fields are required');

        return false;
    }

    public function userCheckAllParameters(bool $check)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['name']) || !isset($data['surname']) || $check && (!isset($data['passport_first']) || !isset($data['passport_last']))) {

            echo $this->messageResponse(400, 'all fields are required');

            return false;
        }

        $this->name = $data['name'];
        $this->surname = $data['surname'];

        if ($check) {

            $this->passport_first = $data['passport_first'];
            $this->passport_last = $data['passport_last'];
        }

        return true;
    }

    public function userUpdate()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id']) || !isset($data['name']) || !isset($data['surname']) || !isset($data['passport_first']) || !!isset($data['passport_last'])) {

            echo $this->messageResponse(400, 'fill in at least one field');

            return false;
        }

        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->surname = $data['surname'];
        $this->passport_first = $data['passport_first'];
        $this->passport_last = $data['passport_last'];

        return true;
    }

    public function getThisMaxUser()
    {
        return $this->maxuser;
    }

    public function getThisId()
    {
        return $this->id;
    }

    public function getThisLimit()
    {
        return $this->limit;
    }

    public function getThisOffset()
    {
        return $this->offset;
    }

    public function getThisName()
    {
        return $this->name;
    }

    public function getThisSurName()
    {
        return $this->surname;
    }

    public function getThisPasswordFirst()
    {
        return $this->passport_first;
    }

    public function getThisPasswordLast()
    {
        return $this->passport_last;
    }
}