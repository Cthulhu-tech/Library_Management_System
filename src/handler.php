<?php

require __DIR__ . '/api/user.php';

class Handler
{

    private $user;

    function __construct()
    {

        $this->user = new User();
    }

    public function handleMethod()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                return $this->get();
            case 'POST':
                return $this->post();
            case 'PUT':
                return $this->put();
            case 'DELETE':
                return $this->delete();
        }
    }

    private function get()
    {

        switch ($_SERVER['REQUEST_URI']) {

            case '/allusers':
                $this->user->getAllUsers();
                break;
            default:
                echo 'this method not implemented';
        }
    }

    private function post()
    {

        switch ($_SERVER['REQUEST_URI']) {

            case '/':
                echo '/';
                return;
        }
    }

    private function put()
    {

        switch ($_SERVER['REQUEST_URI']) {

            case '/':
                echo '/';
                return;
        }
    }

    private function delete()
    {

        switch ($_SERVER['REQUEST_URI']) {

            case '/':
                echo '/';
                return;
        }
    }
}