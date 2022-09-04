<?php

require __DIR__ . '/../utils/check/checkBook.php';
require_once __DIR__ . '/../utils/database/db.php';

class Book extends Database
{
    function __construct()
    {

        $this->checkBook = new BookCheck();
        parent::__construct();
    }

    public function getBook()
    {

        $protected = $this->checkBook->checkBook();

        if (!$protected) {

            echo $this->messageResponse(400, 'all fields are required');

            return false;
        }

        $this->createDatabase();

        $this->result = $this->getDB()->prepare("SELECT * FROM books WHERE book_name LIKE ? LIMIT 10");

        $this->result->execute(array($this->checkBook->getBook() . "%"));

        $row = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);

        $this->closeConnection();

        print_r($row);
    }
    public function setBook()
    {
        $protected = $this->checkBook->checkBookAll(false);

        if (!$protected) {

            echo $this->messageResponse(400, 'all fields are required');

            return false;
        }

        $this->createDatabase();

        $this->result = $this->getDB()->prepare(" SELECT `sp_book_add`(?, ?, ?, ?, ?) AS `status`");

        $this->result->execute(array($this->checkBook->getName(), $this->checkBook->getGanre(), $this->checkBook->getDateCreated(), $this->checkBook->getCount(), $this->checkBook->getCreator()));

        $status = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC)[0]['status'], JSON_UNESCAPED_UNICODE);

        $this->closeConnection();

        if (+$status === 0) {

            echo $this->messageResponse(400, 'book cannot be added. The book already exists');

            return false;
        }

        echo $this->messageResponse(201, 'book added successfully');
    }
    public function updateBook()
    {

        $protected = $this->checkBook->checkBookAll(true);

        if (!$protected) {

            echo $this->messageResponse(400, 'all fields are required');

            return false;
        }

        $this->createDatabase();

        $this->result = $this->getDB()->prepare(" SELECT `sp_update_book`(?, ?, ?, ?, ?, ?) AS `status`");

        $this->result->execute(array($this->checkBook->getName(), $this->checkBook->getGanre(), $this->checkBook->getDateCreated(), $this->checkBook->getCount(), $this->checkBook->getCreator(), $this->checkBook->getId()));

        $status = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC)[0]['status'], JSON_UNESCAPED_UNICODE);

        $this->closeConnection();

        if (+$status === 0) {

            echo $this->messageResponse(400, 'book cannot be update. This book is not found.');

            return false;
        }

        echo $this->messageResponse(200, 'book update successfully');
    }
    public function deleteBook()
    {
    }
    public function getGanreBook()
    {
    }
    public function getGanre()
    {

        $protected = $this->checkBook->checkGanre();

        if (!$protected) {

            $this->limitGanre();

            return false;
        }

        $this->ganreWithFilter();
    }

    private function limitGanre()
    {

        $this->createDatabase();

        $this->result = $this->getDB()->query('SELECT * FROM ganre LIMIT 5');

        $row = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);

        $this->closeConnection();

        print_r($row);
    }

    private function ganreWithFilter()
    {

        $this->createDatabase();

        $this->result = $this->getDB()->prepare("SELECT * FROM ganre WHERE ganre LIKE ? LIMIT 5");

        $this->result->execute(array($this->checkBook->getGanre() . "%"));

        $row = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);

        $this->closeConnection();

        print_r($row);
    }
}