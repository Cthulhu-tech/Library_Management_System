<?php

require_once __DIR__ . '/../../utils/jwt/jwt.php';
require_once __DIR__ . '/../../interface/interface.php';

class JWTmiddleware extends JWThandler implements IJwt
{

    function __construct()
    {
        parent::__construct();
    }

    function checkUser($next)
    {

        $next();
    }

    function checkAdmin($next)
    {

        $check = false;
        $token = $this->getBearerToken();

        if ($token) {

            $check = $this->checkToken($token);
        }

        if ($check && $this->getType() === 'admin') {

            $next();
        }

        echo 'sorry need authorization';
    }
}