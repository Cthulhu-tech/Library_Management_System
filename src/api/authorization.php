<?php

require_once __DIR__ . '/../utils/jwt/jwt.php';
require_once __DIR__ . '/../interface/interface.php';
require_once __DIR__ . '/../utils/check/checkAuth.php';

class Authorization extends JWThandler implements IAuthorization
{

    private $accessDate = 900;
    private $refreshDate = 604800;
    private $check;
    private $result;

    function __construct()
    {
        $this->check = new AuthCheck();
        parent::__construct();
    }

    public function loginUser()
    {
        $check = $this->check->checkLoginUser();

        if (!$check) {

            return false;
        }

        $this->createDatabase();

        $this->result = $this->getDB()->prepare("CALL `sp_get_user_in_login`(?)");

        $this->result->execute(array($this->check->getMail()));

        $row = $this->result->fetch(PDO::FETCH_ASSOC);

        if (!isset($row['password'])) {

            echo $this->messageResponse(400, 'login or password incorrect');
            return false;
        }

        if (!password_verify($this->check->getPassword(), $row['password'])) {

            echo $this->messageResponse(400, 'login or password incorrect');
            return false;
        }

        $access = $this->createJWT($this->accessDate, $row['email'], 'user', +$row['id']);
        $refresh = $this->createJWT($this->refreshDate, $row['email'], 'user', +$row['id']);

        $this->result = $this->getDB()->prepare("UPDATE `user_library` SET `refresh` = ? WHERE id = ?");

        $this->result->execute(array($refresh, +$row['id']));

        $this->closeConnection();

        $this->setAccessToken($access, $row['email'], 'user');
        $this->setRefreshToken('refresh', $refresh, 604800);

        return true;
    }

    public function loginAdmin()
    {

        $check = $this->check->checkLoginAdmin();

        if (!$check) {

            return false;
        }

        $this->createDatabase();

        $this->result = $this->getDB()->prepare("SELECT * FROM administrators WHERE name = ? AND surname = ?");

        $this->result->execute(array($this->check->getName(), $this->check->getSurname()));

        $row = $this->result->fetch(PDO::FETCH_ASSOC);

        if (!isset($row['password'])) {

            echo $this->messageResponse(400, 'login or password incorrect');
            return false;
        }

        if (!password_verify($this->check->getPassword(), $row['password'])) {

            echo $this->messageResponse(400, 'login or password incorrect');
            return false;
        }

        $access = $this->createJWT($this->accessDate, $row['name'], 'admin', +$row['id']);
        $refresh = $this->createJWT($this->refreshDate, $row['name'], 'admin', +$row['id']);

        $this->result = $this->getDB()->prepare("UPDATE `administrators` SET `refresh` = ? WHERE id = ?");

        $this->result->execute(array($refresh, +$row['id']));

        $this->closeConnection();

        $this->setAccessToken($access, $row['name'], 'admin');
        $this->setRefreshToken('refresh', $refresh, $this->refreshDate);

        return true;
    }

    public function lagout()
    {
        $this->setRefreshToken('refresh', '', -time());
        echo $this->messageResponse(200, 'You are lagout');
    }

    public function refresh()
    {
        $refresh = $this->getCookie('refresh');

        if ($refresh === 'need cookie') {

            echo $this->messageResponse(403, 'Token not valid');
            return false;
        }

        $check = $this->checkToken($refresh);

        if (!$check) {

            echo $this->messageResponse(403, 'Token not valid');
            return false;
        }

        $this->createDatabase();

        if ($this->getType() === 'admin') {

            $this->result = $this->getDB()->prepare("SELECT * FROM administrators WHERE id = ?");
            $this->result->execute(array($this->getId()));
        } else {

            $this->result = $this->getDB()->prepare("SELECT * FROM user_library WHERE id = ?");
            $this->result->execute(array($this->getId()));
        }

        $row = $this->result->fetch(PDO::FETCH_ASSOC);

        if (!isset($row['name'])) {

            echo $this->messageResponse(403, 'user not found');
            return false;
        }

        if ($this->getType() === 'admin') {

            $access = $this->createJWT($this->accessDate, $row['name'], 'admin', +$row['id']);
            $refresh = $this->createJWT($this->refreshDate, $row['name'], 'admin', +$row['id']);

            $this->result = $this->getDB()->prepare("UPDATE `administrators` SET `refresh` = ? WHERE id = ?");
            $this->result->execute(array($refresh, +$row['id']));

            $this->setTokens($access, $refresh, $row['name'], 'admin');
        } else {

            $access = $this->createJWT($this->accessDate, $row['email'], 'user', +$row['id']);
            $refresh = $this->createJWT($this->refreshDate, $row['email'], 'user', +$row['id']);

            $this->result = $this->getDB()->prepare("UPDATE `user_library` SET `refresh` = ? WHERE id = ?");
            $this->result->execute(array($refresh, +$row['id']));

            $this->setTokens($access, $refresh, $row['email'], 'user');
        }

        $this->closeConnection();

        return true;
    }

    private function setTokens($access, $refresh, $name, $type)
    {
        $this->setAccessToken($access, $name, $type);
        $this->setRefreshToken('refresh', $refresh, $this->refreshDate);

        return true;
    }

    public function registrationAdmin()
    {

        $check = $this->check->checkRegistrationAdmin();

        if (!$check) {

            return false;
        }

        $this->createDatabase();

        $hashPassword = $this->hashPassword($this->check->getPassword());

        $this->result = $this->getDB()->prepare("SELECT `sp_check_admin`(?, ?, ?) AS `count`");

        $this->result->execute(array($this->check->getName(), $this->check->getSurname(), $hashPassword));

        $row = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC)[0]['count'], JSON_UNESCAPED_UNICODE);

        $this->closeConnection();

        if (+$row === 0) {

            echo $this->messageResponse(400, 'Admin not added, name and surname found in database');

            return false;
        }


        echo $this->messageResponse(201, 'Admin added successfully');
    }

    public function registrationUser()
    {

        $check = $this->check->checkRegistrationUser();

        if (!$check) {

            return false;
        }

        $this->createDatabase();

        $hashPassword = $this->hashPassword($this->check->getPassword());

        $this->result = $this->getDB()->prepare("SELECT `sp_find_user`(?, ?, ?, ?, ?, ?) AS `count`");

        $this->result->execute(array($this->check->getPassportfirst(), $this->check->getPassportlast(), $this->check->getMail(), $this->check->getName(), $this->check->getSurname(), $hashPassword));

        $row = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC)[0]['count'], JSON_UNESCAPED_UNICODE);

        $this->closeConnection();

        if (+$row === 0) {

            echo $this->messageResponse(400, 'user not added, passpord data or email is found in database');

            return false;
        }


        echo $this->messageResponse(201, 'user added successfully');
    }
}