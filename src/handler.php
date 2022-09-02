<?php

require __DIR__ . '/api/user.php';

class Handler
{

    private $user;
    private $url;

    function __construct()
    {

        $this->user = new User();
    }

    public function handleMethod()
    {
        $this->url = strtok($_SERVER["REQUEST_URI"], '?');

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

        switch ($this->url) {

            case '/allusers':
                $this->user->getAllUsers();
                break;
            default:
                echo 'this method not implemented';
        }
    }

    private function post()
    {

        switch ($this->url) {

            case '/':
                echo '/';
                return;
        }
    }

    private function put()
    {

        switch ($this->url) {

            case '/':
                echo '/';
                return;
        }
    }

    private function delete()
    {

        switch ($this->url) {

            case '/':
                echo '/';
                return;
        }
    }
}