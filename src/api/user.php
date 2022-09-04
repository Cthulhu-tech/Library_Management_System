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
        $offset = $this->checkUser->getThisOffset();
        $max = $this->checkUser->getThisMaxUser();

        $this->createDatabase();

        $this->result = $this->getDB()->prepare("SELECT * FROM user_library LIMIT :offset , :limit");
        $this->result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $this->result->bindParam(':offset', $offset, PDO::PARAM_INT);
        $this->result->execute();
        
        $row['users'] = $this->result->fetchAll(PDO::FETCH_ASSOC);

        $this->closeConnection();

        $row['max'] = $max;

        $row = json_encode($row, JSON_UNESCAPED_UNICODE);

        print_r($row);
    }

    public function getUser()
    {

        $protected = $this->checkUser->userCheckAllParameters(false);

        if (!$protected) {

            return false;
        }

        $this->createDatabase();

        $this->result = $this->getDB()->prepare("SELECT * FROM user_library WHERE name LIKE ? AND surname LIKE ?");

        $this->result->execute(array($this->checkUser->getThisName() . "%", $this->checkUser->getThisSurName() . "%"));

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

        $this->createDatabase();

        $this->result = $this->getDB()->prepare(" SELECT `sp_check_user_add`(?, ?, ?, ?) AS `status`");

        $this->result->execute(array($this->checkUser->getThisName(), $this->checkUser->getThisSurName(), $this->checkUser->getThisPasswordLast(), $this->checkUser->getThisPasswordFirst()));

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

        $this->createDatabase();

        $this->result = $this->getDB()->prepare(" SELECT `sp_update_user`(?, ?, ?, ?, ?) AS `status`");

        $this->result->execute(array($this->checkUser->getThisId(), $this->checkUser->getThisName(), $this->checkUser->getThisSurName(), $this->checkUser->getThisPasswordLast(), $this->checkUser->getThisPasswordFirst()));

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

        $this->createDatabase();

        $this->result = $this->getDB()->prepare(" SELECT `sp_delete_user`(?, ?, ?, ?, ?) AS `status`");

        $this->result->execute(
            array($this->checkUser->getThisId(), $this->checkUser->getThisName(), $this->checkUser->getThisSurName(), $this->checkUser->getThisPasswordLast(), $this->checkUser->getThisPasswordFirst())
        );

        $status = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC)[0]['status'], JSON_UNESCAPED_UNICODE);

        $this->closeConnection();

        if (+$status === 0) {

            echo $this->messageResponse(400, 'error, user not found');

            return false;
        }

        echo $this->messageResponse(201, 'user delete successfully');
    }
}