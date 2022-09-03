<?php

require __DIR__ . '/api/user.php';

class Handler
{

    private $user;
    private $url;

    function __construct()
    {
        header('Content-Type: application/json');
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
            default:
                echo 'this method not implemented';
        }
    }

    private function get()
    {

        switch ($this->url) {

            case '/allusers':
                $this->user->getUsers();
                break;
            default:
                echo 'this method not implemented';
        }
    }

    private function post()
    {

        switch ($this->url) {

            case '/user':
                $this->user->getUser();
                break;
            case '/setuser':
                $this->user->setUser();
                break;
            default:
                echo 'this method not implemented';
        }
    }

    private function put()
    {

        switch ($this->url) {

            case '/updateuser':
                $this->user->updateUser();
                break;
            default:
                echo 'this method not implemented';
        }
    }

    private function delete()
    {

        switch ($this->url) {

            case '/deleteuser':
                $this->user->deleteUser();
                break;
            default:
                echo 'this method not implemented';
        }
    }
}