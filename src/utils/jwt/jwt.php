<?php

require 'vendor/autoload.php';
require_once __DIR__ . '/../../utils/database/db.php';
require_once __DIR__ . '/../../interface/interface.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWThandler extends Database implements IJWThandler
{
    private $key = '';
    private $type = '';

    function __construct()
    {
        $this->key = $_ENV['SECRET_KEY'];
    }

    public function getCookie(string $name)
    {

        if (isset($_COOKIE[$name])) {

            return $_COOKIE[$name];
        }

        return "need cookie";
    }

    public function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();

        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {

                return $matches[1];
            }
        }

        return null;
    }

    public function checkToken(string $token)
    {

        $decoded = JWT::decode($token, new Key($this->key, 'HS256'));

        if (isset($decoded->iat, $decoded->type) && $decoded->iat > time()) {

            $this->type = $decoded->type;

            return true;
        }

        return false;
    }

    public function setAccessToken(string $token)
    {

        return true;
    }

    public function setRefreshToken(string $token)
    {
    }

    public function setCookie(string $name, string $value, int $duration)
    {
        setcookie($name, $value, time() + $duration, "/", "", "", true);
    }

    public function createJWT(int $duration, string $login, string $type, int $id)
    {

        $payload = [
            'id' => $id,
            'type' => $type,
            'login' => $login,
            'iat' => time() + $duration,
        ];


        $jwt = JWT::encode($payload, $this->key, 'HS256');

        return $jwt;
    }

    private function getAuthorizationHeader()
    {
        $headers = null;

        if (isset($_SERVER['Authorization'])) {

            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {

            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } else if (function_exists('apache_request_headers')) {

            $requestHeaders = apache_request_headers();

            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));

            if (isset($requestHeaders['Authorization'])) {

                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    public function getType()
    {
        return $this->type;
    }

    public function login()
    {
    }

    public function register()
    {
    }

    public function lagout()
    {
    }
}