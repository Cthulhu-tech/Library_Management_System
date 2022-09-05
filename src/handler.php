<?php

require __DIR__ . '/api/user.php';
require __DIR__ . '/api/book.php';
require __DIR__ . '/middleware/middleware.php';
require_once __DIR__ . '/interface/interface.php';
require_once __DIR__ . '/middleware/handler/jwt.php';

class Handler extends Middleware implements IHandler
{
    private $book;
    private $user;
    private $url;
    private $jwt;

    function __construct()
    {
        header('Content-Type: application/json');
        $this->user = new User();
        $this->book = new Book();
        $this->jwt = new JWTmiddleware();
    }

    public function handleMethod(): void
    {
        $this->url = strtok($_SERVER["REQUEST_URI"], '?');

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->get();
                break;
            case 'POST':
                $this->post();
                break;
            case 'PUT':
                $this->put();
                break;
            case 'DELETE':
                $this->fn([$this, 'delete'])->handler([$this->jwt, 'checkAdmin']);
                break;
            default:
                echo 'this method not implemented';
        }
    }

    private function get()
    {

        switch ($this->url) {

            case '/allusers':
                return $this->fn([$this->user, 'getUsers'])->handler([$this->jwt, 'checkAdmin']); // end
            case '/getbook':
                return $this->book->getBook(); // end
            case '/getganre':
                return $this->book->getGanre(); //end 
            case '/getganrebook':
                return $this->book->getGanreBook(); //end
            default:
                echo 'this method not implemented';
        }
    }

    private function post()
    {

        switch ($this->url) {

            case '/user':
                return $this->fn([$this->user, 'getUser'])->handler([$this->jwt, 'checkAdmin']); // end
            case '/setuser':
                return $this->fn([$this->user, 'setUser'])->handler([$this->jwt, 'checkAdmin']); // end
            case '/setbook':
                return $this->fn([$this->book, 'setBook'])->handler([$this->jwt, 'checkAdmin']); // end
            default:
                echo 'this method not implemented';
        }
    }

    private function put()
    {

        switch ($this->url) {

            case '/updateuser':
                return $this->fn([$this->user, 'updateUser'])->handler([$this->jwt, 'checkAdmin']); // end
            case '/updatebook':
                return $this->fn([$this->book, 'updateBook'])->handler([$this->jwt, 'checkAdmin']); // end
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