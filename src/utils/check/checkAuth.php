<?php

require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../../interface/interface.php';

class AuthCheck extends Database implements IAuthCheck
{

    private $name;
    private $email;
    private $surname;
    private $password_user;
    private $passport_last;
    private $passport_first;

    public function check()
    {

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['password'], $data['name'], $data['surname'], $data['passport_last'], $data['passport_first'], $data['email'])) {

            $this->name = $data['name'];
            $this->email = $data['email'];
            $this->surname = $data['surname'];
            $this->password_user = $data['password'];
            $this->passport_last = $data['passport_last'];
            $this->passport_first = $data['passport_first'];

            return true;
        }

        echo $this->messageResponse(400, 'all fields are required');

        return false;
    }

    public function checkLoginUser()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['password'], $data['email'])) {

            $this->email = $data['email'];
            $this->password_user = $data['password'];

            return true;
        }

        echo $this->messageResponse(400, 'all fields are required');

        return false;
    }

    public function checkLoginAdmin()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['password'], $data['name'], $data['surname'])) {

            $this->name = $data['name'];
            $this->surname = $data['surname'];
            $this->password_user = $data['password'];

            return true;
        }

        echo $this->messageResponse(400, 'all fields are required');

        return false;
    }

    public function checkRegistrationAdmin()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['password'], $data['name'], $data['surname'])) {

            $this->name = $data['name'];
            $this->surname = $data['surname'];
            $this->password_user = $data['password'];

            return true;
        }

        echo $this->messageResponse(400, 'all fields are required');

        return false;
    }

    public function checkRegistrationUser()
    {
        $check = $this->check();

        if ($check) {

            return true;
        }

        return false;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getMail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password_user;
    }

    public function getPassportlast()
    {
        return $this->passport_last;
    }
    public function getPassportfirst()
    {
        return $this->passport_first;
    }
}