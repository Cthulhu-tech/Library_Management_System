<?php

require __DIR__ . '/../utils/check/checkUser.php';
require_once __DIR__ . '/../utils/database/db.php';

class User extends Database
{

    private $result;
    private $checkUser;

    function __construct()
    {

        $this->checkUser = new UserCheck();
        parent::__construct();
    }

    public function getUsers()
    {

        $protected = $this->checkUser->checkParams();

        if (!$protected) {

            return false;
        }

        $limit = $this->checkUser->getThisLimit();
        $offset = $this->ckeckcheckUser->getThisOffset();

        $this->createDatabase();

        $this->result = $this->getDB()->prepare("SELECT * FROM user_library WHERE LIMIT ? , ?");

        $this->result->execute(array($offset,  $limit));

        $row = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);

        print_r($row);

        $this->closeConnection();
    }

    public function getUser()
    {

        $protected = $this->checkUser->userCheckAllParameters(false);

        if (!$protected) {

            return false;
        }

        $name = $this->checkUser->getThisName();
        $surname = $this->checkUser->getThisSurName();

        $this->createDatabase();

        $this->result = $this->getDB()->prepare("SELECT * FROM user_library WHERE name LIKE ? AND surname LIKE ?");

        $this->result->execute(array($name . "%", $surname . "%"));

        $row = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);

        print_r($row);

        $this->closeConnection();
    }

    public function setUser()
    {

        $protected = $this->checkUser->userCheckAllParameters(true);

        if (!$protected) {

            return false;
        }

        $name = $this->checkUser->getThisName();
        $surname = $this->checkUser->getThisSurName();
        $passport_last = $this->checkUser->getThisPasswordLast();
        $passport_first = $this->checkUser->getThisPasswordFirst();

        $this->createDatabase();

        $this->result = $this->getDB()->prepare(" SELECT `sp_check_user_add`(?, ?, ?, ?) AS `status`");

        $this->result->execute(array($name, $surname, $passport_first, $passport_last));

        $status = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC)[0]['status'], JSON_UNESCAPED_UNICODE);

        $this->closeConnection();

        if (+$status === 0) {

            echo $this->messageResponse(400, 'user cannot be added. Passport details match with another user');

            return false;
        }

        echo $this->messageResponse(201, 'user added successfully');
    }

    public function updateUser()
    {

        $protected = $this->checkUser->userUpdate();

        if (!$protected) {

            return false;
        }

        $id = $this->checkUser->getThisId();
        $name = $this->checkUser->getThisName();
        $surname = $this->checkUser->getThisSurName();
        $passport_last = $this->checkUser->getThisPasswordLast();
        $passport_first = $this->checkUser->getThisPasswordFirst();

        $this->createDatabase();

        $this->result = $this->getDB()->prepare(" SELECT `sp_update_user`(?, ?, ?, ?, ?) AS `status`");

        $this->result->execute(array($id, $name, $surname, $passport_first, $passport_last));

        $status = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC)[0]['status'], JSON_UNESCAPED_UNICODE);

        $this->closeConnection();

        if (+$status === 0) {

            echo $this->messageResponse(400, 'error, user not found');

            return false;
        }

        echo $this->messageResponse(201, 'user updated successfully');
    }

    public function deleteUser()
    {

        $protected = $this->checkUser->userUpdate();

        if (!$protected) {

            return false;
        }

        $id = $this->checkUser->getThisId();
        $name = $this->checkUser->getThisName();
        $surname = $this->checkUser->getThisSurName();
        $passport_last = $this->checkUser->getThisPasswordLast();
        $passport_first = $this->checkUser->getThisPasswordFirst();

        $this->createDatabase();

        $this->result = $this->getDB()->prepare(" SELECT `sp_delete_user`(?, ?, ?, ?, ?) AS `status`");

        $this->result->execute(array($id, $name, $surname, $passport_first, $passport_last));

        $status = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC)[0]['status'], JSON_UNESCAPED_UNICODE);

        $this->closeConnection();

        if (+$status === 0) {

            echo $this->messageResponse(400, 'error, user not found');

            return false;
        }

        echo $this->messageResponse(201, 'user delete successfully');
    }
}