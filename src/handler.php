<?php

require __DIR__ . '/api/user.php';
require __DIR__ . '/api/book.php';
require __DIR__ . '/api/authorization.php';
require __DIR__ . '/middleware/middleware.php';
require_once __DIR__ . '/interface/interface.php';
require_once __DIR__ . '/middleware/handler/jwt.php';

class Handler extends Middleware implements IHandler
{
    private $book;
    private $user;
    private $auth;
    private $url;
    private $jwt;

    function __construct()
    {
        $this->headers();
        $this->user = new User();
        $this->book = new Book();
        $this->jwt = new JWTmiddleware();
        $this->auth = new Authorization();
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
            case 'OPTIONS':
                $this->options();
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
            case '/loginuser':
                return $this->auth->loginUser();
            case '/loginadmin':
                return $this->auth->loginAdmin();
            case '/refresh':
                return $this->auth->refresh();
            case '/registrationuser':
                return $this->auth->registrationUser();
            case '/registrationadmin':
                return $this->fn([$this->auth, 'registrationAdmin'])->handler([$this->jwt, 'checkAdmin']); // end
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

    private function options()
    {

        http_response_code(200);

        return true;
    }

    private function headers()
    {

        header("Access-Control-Allow-Origin: http://localhost:8081 ", true);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Max-Age: 1000");
        header('Content-Type: application/json');
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, X-Requested-With, Content-Type, Accept, Authorization");
        header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
    }
}