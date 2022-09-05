<?php

require_once __DIR__ . '/../utils/jwt/jwt.php';
require_once __DIR__ . '/../interface/interface.php';

class Authorization extends JWThandler implements IAuthorization
{

    public function login()
    {

        echo 'login';
    }

    public function refresh()
    {

        echo 'refresh';
    }

    public function registration()
    {

        echo 'registration';
    }
}