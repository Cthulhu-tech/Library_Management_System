<?php

require __DIR__ . '/api/user.php';
require __DIR__ . '/api/book.php';
require_once __DIR__ . '/../../interface/interface.php';

class Handler implements IHandler
{
    private $book;
    private $user;
    private $url;

    function __construct()
    {
        header('Content-Type: application/json');
        $this->user = new User();
        $this->book = new Book();
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
                return $this->user->getUsers(); // end
            case '/getbook':
                return $this->book->getBook(); // end
            case '/getganre':
                return $this->book->getGanre(); // end
            case '/getganrebook':
                return $this->book->getGanreBook();
            default:
                echo 'this method not implemented';
        }
    }

    private function post()
    {

        switch ($this->url) {

            case '/user':
                return $this->user->getUser(); // end
            case '/setuser':
                return $this->user->setUser(); // end
            case '/setbook':
                return $this->book->setBook(); // end
            default:
                echo 'this method not implemented';
        }
    }

    private function put()
    {

        switch ($this->url) {

            case '/updateuser':
                return $this->user->updateUser(); // end
            case '/updatebook':
                return $this->book->updateBook(); // end
            default:
                echo 'this method not implemented';
        }
    }

    private function delete()
    {

        switch ($this->url) {

            case '/deleteuser':
                return $this->user->deleteUser(); // end
            case '/deletebook':
                return $this->book->deleteBook(); // end
            default:
                echo 'this method not implemented';
        }
    }
}